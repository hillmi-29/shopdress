<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Provide homepage data: categories, featured dresses, latest arrivals.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')->get();

        $featuredProducts = Product::with(['primaryImage', 'variants'])
            ->where('is_active', true)
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with(['primaryImage', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'featured_products' => $featuredProducts,
                'latest_products' => $latestProducts,
            ],
        ]);
    }
}
