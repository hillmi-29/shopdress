<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display product catalog with filtering and pagination.
     */
    public function index(): View
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category', 'primaryImage', 'variants']);

        // Filters from query string
        if (request()->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', request('category')));
        }

        if (request()->filled('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }

        if (request()->filled('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }

        if (request()->filled('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }

        $sort = request('sort', 'popular');
        match ($sort) {
            'latest' => $query->latest(),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderByPopularity(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::withActiveProductCount()
            ->orderByPopularity()
            ->get();

        $wishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'wishlistProductIds',
        ));
    }

    /**
     * Display single product detail page.
     */
    public function show(string $slug): View
    {
        $product = Product::with([
            'category',
            'primaryImage',
            'images',
            'variants' => fn ($q) => $q->orderBy('additional_price')->orderBy('id'),
            'reviews' => fn ($q) => $q->where('is_visible', true)->with('user:id,name')->latest(),
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $relatedQuery = fn () => Product::with(['category', 'primaryImage', 'variants'])
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderByPopularity()
            ->take(4);

        $relatedProducts = $relatedQuery()
            ->where('category_id', $product->category_id)
            ->get();

        // Sebagian kategori baru punya satu produk; tampilkan produk lain
        // sebagai alternatif agar bagian "Produk Serupa" tidak kosong.
        if ($relatedProducts->isEmpty()) {
            $relatedProducts = $relatedQuery()->get();
        }

        $wishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        $wishlisted = in_array($product->id, $wishlistProductIds, true);

        $userReview = null;
        $hasPurchased = false;

        if (auth()->check()) {
            $user = auth()->user();

            $userReview = Review::query()
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

            $hasPurchased = Review::hasVerifiedPurchase($user, $product->id);
        }

        return view('frontend.products.show', compact(
            'product',
            'relatedProducts',
            'wishlistProductIds',
            'wishlisted',
            'userReview',
            'hasPurchased',
        ));
    }
}
