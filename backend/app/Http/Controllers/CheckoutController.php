<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderCreatedMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\InventoryService;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected InventoryService $inventoryService,
        protected MidtransService $midtransService
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
        $shippingCost = 20000; // Flat shipping rate
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
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'notes' => 'nullable|string',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        $user = $request->user();
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan masuk atau daftar terlebih dahulu untuk melakukan checkout.',
            ], 401);
        }

        $cart = $this->cartService->getCart($user);
        if ($cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belanja Anda kosong.',
            ], 422);
        }

        try {
            $order = DB::transaction(function () use ($request, $user, $cart) {
                $totalAmount = $cart->total_amount;
                $shippingCost = 20000;
                $discountAmount = 0;

                if ($request->filled('coupon_code')) {
                    $coupon = Coupon::where('code', $request->input('coupon_code'))->lockForUpdate()->first();
                    if ($coupon && $coupon->isValid($totalAmount)) {
                        $discountAmount = $coupon->calculateDiscount($totalAmount);
                        $coupon->increment('used_count');
                    }
                }

                $grandTotal = max(0, $totalAmount + $shippingCost - $discountAmount);

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'shipping_cost' => $shippingCost,
                    'discount_amount' => $discountAmount,
                    'grand_total' => $grandTotal,
                    'status' => OrderStatus::PENDING,
                    'payment_status' => PaymentStatus::UNPAID,
                    'shipping_address' => $request->input('shipping_address'),
                    'recipient_name' => $request->input('recipient_name'),
                    'recipient_phone' => $request->input('recipient_phone'),
                    'notes' => $request->input('notes'),
                ]);

                // Create Order Items
                foreach ($cart->items as $cartItem) {
                    $variant = $cartItem->variant;
                    $product = $variant->product;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'product_name' => $product->name,
                        'variant_details' => [
                            'sku' => $variant->sku,
                            'size' => $variant->size,
                            'color' => $variant->color,
                        ],
                        'price' => $variant->final_price,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $cartItem->subtotal,
                    ]);
                }

                // Reserve Stock
                $this->inventoryService->reserveStock($order);

                // Clear Cart
                $this->cartService->clearCart($user);

                return $order;
            });

            // Generate Midtrans Snap Token
            $snapToken = $this->midtransService->createSnapToken($order);

            // Send confirmation email asynchronously
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
                    'snap_token' => $snapToken,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage(),
            ], 422);
        }
    }
}
