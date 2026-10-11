<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create the cart for current session or authenticated user.
     */
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Merge guest session cart if exists
            $sessionId = Session::getId();
            $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();

            if ($guestCart && $guestCart->id !== $cart->id) {
                foreach ($guestCart->items as $guestItem) {
                    $existingItem = $cart->items()->where('product_variant_id', $guestItem->product_variant_id)->first();
                    if ($existingItem) {
                        $existingItem->increment('quantity', $guestItem->quantity);
                    } else {
                        $guestItem->update(['cart_id' => $cart->id]);
                    }
                }
                $guestCart->delete();
            }

            return $cart->load(['items.variant.product.primaryImage']);
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null]
        );

        return $cart->load(['items.variant.product.primaryImage']);
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
