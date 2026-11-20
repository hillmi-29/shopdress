<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Services\BuyNowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuyNowController extends Controller
{
    public function __construct(protected BuyNowService $buyNowService) {}

    /**
     * Start a Buy Now session that is isolated from the customer's cart.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $variant = ProductVariant::with('product')->findOrFail($validated['product_variant_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        if (! $variant->product || ! $variant->product->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk ini sedang tidak tersedia.',
            ], 422);
        }

        if ($variant->stock < $quantity) {
            return response()->json([
                'status' => 'error',
                'message' => "Stok untuk varian ini tersisa {$variant->stock} pcs.",
            ], 422);
        }

        $this->buyNowService->remember($variant->id, $quantity);

        return response()->json([
            'status' => 'success',
            'message' => 'Lanjut ke checkout.',
            'redirect' => route('checkout.index'),
        ]);
    }

    /**
     * Update the quantity of the Buy Now item.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $draft = $this->buyNowService->draft();

        if (! $draft) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi Beli Sekarang sudah berakhir. Silakan pilih produk kembali.',
            ], 422);
        }

        $variant = ProductVariant::find($draft['product_variant_id']);

        if (! $variant) {
            $this->buyNowService->forget();

            return response()->json([
                'status' => 'error',
                'message' => 'Produk sudah tidak tersedia.',
            ], 422);
        }

        if ($variant->stock < $validated['quantity']) {
            return response()->json([
                'status' => 'error',
                'message' => "Stok untuk varian ini hanya tersisa {$variant->stock} pcs.",
            ], 422);
        }

        $this->buyNowService->remember($variant->id, $validated['quantity']);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah diperbarui.',
        ]);
    }

    /**
     * Cancel the Buy Now session without touching the real cart.
     */
    public function destroy(): JsonResponse
    {
        $this->buyNowService->forget();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi Beli Sekarang dibatalkan.',
        ]);
    }
}
