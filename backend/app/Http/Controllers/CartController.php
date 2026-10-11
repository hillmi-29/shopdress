<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    /**
     * Get current cart content and summary.
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart($request->user());

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart,
                'total_amount' => $cart->total_amount,
                'total_quantity' => $cart->total_quantity,
            ],
        ]);
    }

    /**
     * Add product variant to cart.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        try {
            $cartItem = $this->cartService->addItem(
                $request->user(),
                $request->integer('product_variant_id'),
                $request->integer('quantity', 1)
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'data' => $cartItem->load('variant.product'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $cartItem = $this->cartService->updateQuantity(
                $request->user(),
                $id,
                $request->integer('quantity')
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Keranjang berhasil diperbarui.',
                'data' => $cartItem,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->cartService->removeItem($request->user(), $id);

        return response()->json([
            'status' => 'success',
            'message' => 'Item berhasil dihapus dari keranjang.',
        ]);
    }

    /**
     * Clear all items in cart.
     */
    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Keranjang berhasil dikosongkan.',
        ]);
    }
}
