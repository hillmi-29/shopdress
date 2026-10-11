<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * List user wishlists.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $wishlists = Wishlist::with('product.primaryImage')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $wishlists,
        ]);
    }

    /**
     * Toggle product in wishlist (add or remove).
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $productId = $request->integer('product_id');

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'status' => 'success',
                'action' => 'removed',
                'message' => 'Produk dihapus dari wishlist.',
            ]);
        }

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'status' => 'success',
            'action' => 'added',
            'message' => 'Produk ditambahkan ke wishlist.',
            'data' => $wishlist,
        ]);
    }
}
