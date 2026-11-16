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
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Display products in a specific category.
     */
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::query()
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->with(['primaryImage', 'variants'])
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('products')
            ->orderBy('name')
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