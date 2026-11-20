<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /**
     * Get authenticated customer profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }

    /**
     * Update customer profile.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user,
        ]);
    }

    /**
     * List user order history.
     */
    public function orders(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    /**
     * Get specific order detail.
     */
    public function orderDetail(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $order = Order::with(['items.variant.product.primaryImage'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * Submit product review for a purchased product.
     */
    public function submitReview(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $productId = $request->integer('product_id');

        // Check if user has bought this product in a paid or completed order
        $hasPurchased = Review::hasVerifiedPurchase($user, $productId);

        if (! $hasPurchased) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda hanya dapat memberikan ulasan untuk produk yang sudah Anda beli dan bayar.',
            ], 403);
        }

        $review = Review::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['rating' => $request->integer('rating'), 'comment' => $request->input('comment')]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan Anda berhasil dikirim.',
            'data' => $review,
        ]);
    }
}
