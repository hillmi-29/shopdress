<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\CheckoutException;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    /**
     * Nilai cadangan bila pengaturan toko belum pernah disimpan.
     */
    public const SHIPPING_COST = 20000;

    /**
     * Ongkos kirim yang berlaku, diatur admin lewat Pengaturan Toko.
     */
    public function shippingCost(): float
    {
        return (float) SiteSetting::get('shipping_cost', self::SHIPPING_COST);
    }

    public function __construct(
        protected CartService $cartService,
        protected InventoryService $inventoryService,
        protected MidtransService $midtransService,
    ) {}

    /**
     * Place an order and generate its Midtrans Snap token.
     *
     * The Snap token is created before anything is written to the database.
     * If Midtrans fails, the cart, stock, and coupon usage are untouched.
     * If it succeeds, the order, stock reservation, coupon usage, and cart
     * cleanup are committed together in one local transaction.
     *
     * @param  array{shipping_address: string, recipient_name: string, recipient_phone: string, notes?: string|null, coupon_code?: string|null}  $checkoutData
     * @param  Cart|null  $cart  Cart to order from. Defaults to the customer's real cart;
     *                           the Buy Now flow passes a transient cart so the real cart stays intact.
     */
    public function checkout(User $user, array $checkoutData, ?Cart $cart = null): Order
    {
        $cart ??= $this->cartService->getCart($user);

        if ($cart->items->isEmpty()) {
            throw new CheckoutException('Keranjang belanja Anda kosong.');
        }

        $draft = $this->buildDraft($user, $cart, $checkoutData);

        // External call, intentionally outside the database transaction.
        // A failure here must not leave an order, reserved stock, or a used coupon.
        $snapToken = $this->midtransService->generateSnapToken($draft);

        return DB::transaction(function () use ($user, $cart, $checkoutData, $draft, $snapToken) {
            $this->applyCoupon($draft, $checkoutData['coupon_code'] ?? null);

            $order = Order::create([
                'order_number' => $draft->order_number,
                'user_id' => $user->id,
                'total_amount' => $draft->total_amount,
                'shipping_cost' => $draft->shipping_cost,
                'discount_amount' => $draft->discount_amount,
                'grand_total' => $draft->grand_total,
                'status' => OrderStatus::PENDING,
                'payment_status' => PaymentStatus::UNPAID,
                'snap_token' => $snapToken,
                'shipping_address' => $draft->shipping_address,
                'recipient_name' => $draft->recipient_name,
                'recipient_phone' => $draft->recipient_phone,
                'notes' => $draft->notes,
                'landmark_photo' => $checkoutData['landmark_photo'] ?? null,
            ]);

            foreach ($draft->items as $draftItem) {
                $order->items()->create([
                    'product_variant_id' => $draftItem->product_variant_id,
                    'product_name' => $draftItem->product_name,
                    'variant_details' => $draftItem->variant_details,
                    'price' => $draftItem->price,
                    'quantity' => $draftItem->quantity,
                    'subtotal' => $draftItem->subtotal,
                ]);
            }

            // Re-check and decrement stock while holding row locks.
            $this->inventoryService->reserveStock($order->load('items'));

            // A Buy Now cart is transient and must not clear the real cart.
            if ($cart->exists) {
                $cart->items()->delete();
            }

            return $order->load('items.variant.product');
        });
    }

    /**
     * Build an unsaved order with the totals and items that Midtrans will see.
     */
    private function buildDraft(User $user, Cart $cart, array $checkoutData): Order
    {
        $totalAmount = (float) $cart->total_amount;
        $shippingCost = $this->shippingCost();
        $discountAmount = 0.0;
        $couponCode = $checkoutData['coupon_code'] ?? null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();

            if (! $coupon || ! $coupon->isValid($totalAmount)) {
                throw new CheckoutException('Kupon tidak valid, sudah kedaluwarsa, atau tidak memenuhi syarat minimum pembelian.');
            }

            $discountAmount = $coupon->calculateDiscount($totalAmount);
        }

        $items = $cart->items->map(function ($cartItem) {
            $variant = $cartItem->variant;

            if (! $variant) {
                throw new CheckoutException('Produk dalam keranjang tidak tersedia lagi.');
            }

            if ($variant->stock < $cartItem->quantity) {
                throw new CheckoutException("Stok untuk varian {$variant->product->name} tidak mencukupi.");
            }

            return new OrderItem([
                'product_variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'variant_details' => [
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color,
                ],
                'price' => $variant->final_price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $variant->final_price * $cartItem->quantity,
            ]);
        });

        $order = new Order([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'grand_total' => max(0, $totalAmount + $shippingCost - $discountAmount),
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'shipping_address' => $checkoutData['shipping_address'],
            'recipient_name' => $checkoutData['recipient_name'],
            'recipient_phone' => $checkoutData['recipient_phone'],
            'notes' => $checkoutData['notes'] ?? null,
        ]);

        $order->setRelation('user', $user);
        $order->setRelation('items', $items->values());

        return $order;
    }

    /**
     * Lock and increment the coupon after the Snap token was generated.
     */
    private function applyCoupon(Order $draft, ?string $couponCode): void
    {
        if (! $couponCode) {
            return;
        }

        $coupon = Coupon::where('code', $couponCode)->lockForUpdate()->first();

        if (! $coupon || ! $coupon->isValid((float) $draft->total_amount)) {
            throw new CheckoutException('Kupon tidak valid, sudah kedaluwarsa, atau tidak memenuhi syarat minimum pembelian.');
        }

        // Make sure the locked coupon still matches the amount sent to Midtrans.
        if (abs($coupon->calculateDiscount((float) $draft->total_amount) - (float) $draft->discount_amount) > 0.01) {
            throw new CheckoutException('Nilai kupon berubah. Silakan ulangi checkout.');
        }

        $coupon->increment('used_count');
    }
}
