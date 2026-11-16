<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartWiringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Endpoint /api/cart harus bisa dipakai halaman Blade frontend
     * (session stateful) tanpa login — sesuai wiring di homepage.
     */
    public function test_guest_can_add_product_variant_to_cart(): void
    {
        $variant = $this->createProductVariant(stock: 10);

        $response = $this->postJson('/api/cart', [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ], [
            'Referer' => 'http://localhost/wiring-test',
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
            ]);

        $this->assertSame(1, CartItem::query()->count());
    }

    public function test_authenticated_user_can_toggle_wishlist_with_token(): void
    {
        $variant = $this->createProductVariant(stock: 10);
        $user = User::factory()->create(['role' => 'customer']);
        $token = $user->createToken('wiring-test')->plainTextToken;

        $response = $this->postJson('/api/wishlist/toggle', [
            'product_id' => $variant->product_id,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'action' => 'added',
            ]);
        $response = $this->postJson('/api/wishlist/toggle', [
            'product_id' => $variant->product_id,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);
        $response->assertStatus(200)->assertJson([
            'status' => 'success',
            'action' => 'removed',
        ]);
    }

    public function test_wishlist_rejects_unauthenticated_request(): void
    {
        $variant = $this->createProductVariant(stock: 10);

        $response = $this->postJson('/api/wishlist/toggle', [
            'product_id' => $variant->product_id,
        ], [
            'Referer' => 'http://localhost/wiring-test',
        ]);

        $response->assertStatus(401);
    }

    private function createProductVariant(int $stock): ProductVariant
    {
        $category = Category::create([
            'name' => 'Gamis & Abaya Wiring',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Aurelia Wiring Dress',
            'description' => 'Dress uji wiring untuk homepage.',
            'price' => 249000,
            'is_active' => true,
            'featured' => false,
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-WIRING-TEST-1',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
