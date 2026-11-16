<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Session key that remembers the guest session ID across session regeneration.
     */
    public const GUEST_CART_SESSION_KEY = 'cart_session_id';

    /**
     * Get or create the cart for current session or authenticated user.
     */
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            $this->mergeGuestCart($user);

            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            return $cart->load(['items.variant.product.primaryImage']);
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null]
        );

        // Session IDs change on login. Keep the current guest ID in the session
        // payload so the cart can still be found afterwards.
        Session::put(self::GUEST_CART_SESSION_KEY, $sessionId);

        return $cart->load(['items.variant.product.primaryImage']);
    }

    /**
     * Move all guest cart items into the authenticated user's cart.
     *
     * Safe to call more than once: the guest marker is removed after merging.
     */
    public function mergeGuestCart(?User $user, ?string $guestSessionId = null): void
    {
        if (! $user) {
            return;
        }

        $guestSessionId ??= Session::get(self::GUEST_CART_SESSION_KEY);

        if (! $guestSessionId) {
            return;
        }

        DB::transaction(function () use ($user, $guestSessionId) {
            $guestCart = Cart::query()
                ->where('session_id', $guestSessionId)
                ->whereNull('user_id')
                ->lockForUpdate()
                ->first();

            if (! $guestCart) {
                return;
            }

            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

            if ($guestCart->id === $userCart->id) {
                return;
            }

            foreach ($guestCart->items()->with('variant')->get() as $guestItem) {
                $existingItem = $userCart->items()
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                $stock = $guestItem->variant?->stock;
                $quantity = $guestItem->quantity + ($existingItem?->quantity ?? 0);

                // Never merge a quantity that the current stock cannot fulfil.
                if ($stock !== null && $quantity > $stock) {
                    $quantity = $stock;
                }

                if ($quantity <= 0) {
                    continue;
                }

                if ($existingItem) {
                    $existingItem->update(['quantity' => $quantity]);
                } else {
                    $guestItem->update([
                        'cart_id' => $userCart->id,
                        'quantity' => $quantity,
                    ]);
                }
            }

            $guestCart->delete();
        });

        Session::forget(self::GUEST_CART_SESSION_KEY);
    }

    /**
     * Add item to cart with stock validation.
     */
    public function addItem(?User $user, int $variantId, int $quantity = 1): CartItem
    {
        $variant = ProductVariant::findOrFail($variantId);

        if ($variant->stock < $quantity) {
            throw new Exception("Stok untuk varian ini tersisa {$variant->stock} pcs.");
        }

        $cart = $this->getCart($user);

        $cartItem = $cart->items()->where('product_variant_id', $variantId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($variant->stock < $newQuantity) {
                throw new Exception("Stok tidak mencukupi untuk menambah jumlah yang dipilih (Sisa stok: {$variant->stock}).");
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem;
    }

    /**
     * Update quantity of an item in the cart.
     */
    public function updateQuantity(?User $user, int $cartItemId, int $quantity): CartItem
    {
        $cart = $this->getCart($user);
        $cartItem = $cart->items()->findOrFail($cartItemId);
        $variant = $cartItem->variant;

        if ($quantity <= 0) {
            $cartItem->delete();
            return $cartItem;
        }

        if ($variant->stock < $quantity) {
            throw new Exception("Stok untuk varian ini hanya tersisa {$variant->stock} pcs.");
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(?User $user, int $cartItemId): void
    {
        $cart = $this->getCart($user);
        $cart->items()->where('id', $cartItemId)->delete();
    }

    /**
     * Clear all items in the cart.
     */
    public function clearCart(?User $user): void
    {
        $cart = $this->getCart($user);
        $cart->items()->delete();
    }
}
