<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CheckoutException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderCreatedMail;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {
    }

    /**
     * Preview checkout details and calculations.
     */
    public function preview(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart($request->user());

        if ($cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belanja masih kosong.',
            ], 422);
        }

        $totalAmount = $cart->total_amount;
        $shippingCost = CheckoutService::SHIPPING_COST;
        $discountAmount = 0;
        $couponCode = $request->query('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($totalAmount)) {
                $discountAmount = $coupon->calculateDiscount($totalAmount);
            }
        }

        $grandTotal = max(0, $totalAmount + $shippingCost - $discountAmount);

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart,
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
            ],
        ]);
    }

    /**
     * Verify coupon validity.
     */
    public function checkCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->input('code'))->first();

        if (! $coupon || ! $coupon->isValid((float) $request->input('amount'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kupon tidak valid, sudah kedaluwarsa, atau tidak memenuhi syarat minimum pembelian.',
            ], 422);
        }

        $discount = $coupon->calculateDiscount((float) $request->input('amount'));

        return response()->json([
            'status' => 'success',
            'message' => 'Kupon berhasil diterapkan.',
            'data' => [
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_amount' => $discount,
            ],
        ]);
    }

    /**
     * Process checkout, create Order, reserve inventory, generate Midtrans Snap Token.
     */
    public function process(CheckoutRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($this->cartService->getCart($user)->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belanja Anda kosong.',
            ], 422);
        }

        try {
            $order = $this->checkoutService->checkout($user, $request->validated());

            try {
                Mail::to($user->email)->queue(new OrderCreatedMail($order));
            } catch (Exception $e) {
                Log::error('Failed to queue OrderCreatedMail: ' . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat.',
                'data' => [
                    'order' => $order->load('items'),
                    'snap_token' => $order->snap_token,
                ],
            ]);
        } catch (CheckoutException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Checkout API gagal diproses.', [
                'user_id' => $user?->id,
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Checkout gagal diproses. Silakan coba lagi atau hubungi CS.',
            ], 500);
        }
    }
}
