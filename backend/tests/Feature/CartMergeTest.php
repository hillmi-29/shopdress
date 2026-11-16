<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_into_user_cart_after_login(): void
    {
        $user = User::factory()->create(['email' => 'merge-login@shopdress.test']);
        $variant = $this->createProductVariant(stock: 10);

        $guestCart = Cart::create(['session_id' => 'guest-session-login']);
        $guestCart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->withSession([CartService::GUEST_CART_SESSION_KEY => 'guest-session-login'])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

        $userCart = Cart::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(2, (int) $userCart->items()->where('product_variant_id', $variant->id)->value('quantity'));
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    public function test_duplicate_items_are_merged_and_capped_at_available_stock(): void
    {
        $user = User::factory()->create(['email' => 'merge-duplicate@shopdress.test']);
        $variant = $this->createProductVariant(stock: 4);

        $userCart = Cart::create(['user_id' => $user->id]);
        $userCart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $guestCart = Cart::create(['session_id' => 'guest-session-duplicate']);
        $guestCart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->withSession([CartService::GUEST_CART_SESSION_KEY => 'guest-session-duplicate'])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('home'));

        $this->assertSame(4, (int) $userCart->items()->where('product_variant_id', $variant->id)->value('quantity'));
        $this->assertSame(1, $userCart->items()->count());
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    public function test_guest_cart_is_merged_when_a_new_account_is_registered(): void
    {
        $variant = $this->createProductVariant(stock: 10);

        $guestCart = Cart::create(['session_id' => 'guest-session-register']);
        $guestCart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->withSession([CartService::GUEST_CART_SESSION_KEY => 'guest-session-register'])
            ->post(route('register.post'), [
                'name' => 'Pendaftar Baru',
                'email' => 'merge-register@shopdress.test',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms' => 'on',
            ])
            ->assertRedirect(route('home'));

        $user = User::where('email', 'merge-register@shopdress.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);

        $userCart = Cart::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(1, (int) $userCart->items()->where('product_variant_id', $variant->id)->value('quantity'));
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    public function test_pending_guest_cart_is_merged_on_next_authenticated_cart_access(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(stock: 10);

        $guestCart = Cart::create(['session_id' => 'guest-session-lazy']);
        $guestCart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->withSession([CartService::GUEST_CART_SESSION_KEY => 'guest-session-lazy'])
            ->get(route('cart.index'))
            ->assertOk();

        $userCart = Cart::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(2, (int) $userCart->items()->where('product_variant_id', $variant->id)->value('quantity'));
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
    }

    private function createProductVariant(int $stock): ProductVariant
    {
        $category = Category::create(['name' => 'Kategori Cart Merge']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Cart Merge',
            'description' => 'Produk untuk test merge keranjang.',
            'price' => 100000,
            'is_active' => true,
            'featured' => false,
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-CART-MERGE-1',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
