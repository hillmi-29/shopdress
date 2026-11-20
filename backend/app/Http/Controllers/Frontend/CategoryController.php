<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display category listing page.
     */
    public function index(): View
    {
        $categories = Category::withActiveProductCount()
            ->orderByPopularity()
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Display products in a specific category.
     */
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::query()
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->with(['primaryImage', 'variants']);

        match (request('sort', 'popular')) {
            'latest'     => $query->latest(),
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            default      => $query->orderByPopularity(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::withActiveProductCount()
            ->orderByPopularity()
            ->get();

        $wishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        return view('frontend.categories.show', compact(
            'category',
            'products',
            'categories',
            'wishlistProductIds',
        ));
    }
}
