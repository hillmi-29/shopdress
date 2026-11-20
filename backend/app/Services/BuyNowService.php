<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class BuyNowService
{
    /**
     * Session key that stores the item bought through "Beli Sekarang".
     */
    public const SESSION_KEY = 'buy_now';

    /**
     * Remember the product variant the customer wants to buy right away.
     */
    public function remember(int $variantId, int $quantity = 1): void
    {
        Session::put(self::SESSION_KEY, [
            'product_variant_id' => $variantId,
            'quantity' => max(1, $quantity),
        ]);
    }

    /**
     * Read the raw Buy Now draft.
     *
     * @return array{product_variant_id: int, quantity: int}|null
     */
    public function draft(): ?array
    {
        $draft = Session::get(self::SESSION_KEY);

        if (! is_array($draft) || empty($draft['product_variant_id'])) {
            return null;
        }

        return [
            'product_variant_id' => (int) $draft['product_variant_id'],
            'quantity' => max(1, (int) ($draft['quantity'] ?? 1)),
        ];
    }

    /**
     * Remove the Buy Now draft, e.g. after the order is paid or cancelled.
     */
    public function forget(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Build a transient cart that contains only the Buy Now item.
     *
     * The returned cart is never persisted, so the customer's real cart and
     * its items stay untouched. Returns null when there is no usable draft.
     */
    public function cart(): ?Cart
    {
        $draft = $this->draft();

        if (! $draft) {
            return null;
        }

        $variant = ProductVariant::query()
            ->with(['product.primaryImage', 'product.category'])
            ->find($draft['product_variant_id']);

        if (! $variant || ! $variant->product || ! $variant->product->is_active) {
            $this->forget();

            return null;
        }

        $item = new CartItem([
            'product_variant_id' => $variant->id,
            'quantity' => $draft['quantity'],
        ]);
        $item->setRelation('variant', $variant);

        $cart = new Cart;
        $cart->setRelation('items', collect([$item]));

        return $cart;
    }
}
