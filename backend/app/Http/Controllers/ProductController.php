<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering, search, and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'primaryImage', 'variants'])
            ->where('is_active', true);

        // Filter by category slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        // Filter by search query
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Size
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size', $request->query('size'));
            });
        }

        // Filter by Color
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color', $request->query('color'));
            });
        }

        // Filter by Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        // Filter featured only
        if ($request->boolean('featured')) {
            $query->where('featured', true);
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $perPage = (int) $request->query('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    /**
     * Display a specific product with its variants, images, and reviews.
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::with([
            'category',
            'images',
            'variants',
            'reviews.user:id,name',
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    /**
     * Get available filter metadata (all categories, sizes, colors, price range).
     */
    public function filters(): JsonResponse
    {
        $categories = Category::select('id', 'name', 'slug')->get();
        $sizes = \App\Models\ProductVariant::distinct()->pluck('size')->values();
        $colors = \App\Models\ProductVariant::distinct()->pluck('color')->values();
        $minPrice = Product::where('is_active', true)->min('price') ?? 0;
        $maxPrice = Product::where('is_active', true)->max('price') ?? 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'sizes' => $sizes,
                'colors' => $colors,
                'price_range' => [
                    'min' => (float) $minPrice,
                    'max' => (float) $maxPrice,
                ],
            ],
        ]);
    }
}
