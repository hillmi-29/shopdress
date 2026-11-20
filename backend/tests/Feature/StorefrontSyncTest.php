<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StorefrontSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_promo_page_lists_only_active_coupons_from_database(): void
    {
        $this->makeCoupon([
            'code' => 'AKTIF20',
            'discount_type' => 'percent',
            'discount_amount' => 20,
            'min_spend' => 150000,
        ]);
        $this->makeCoupon(['code' => 'NONAKTIF', 'is_active' => false]);
        $this->makeCoupon(['code' => 'KEDALUWARSA', 'expires_at' => now()->subDay()]);
        $this->makeCoupon(['code' => 'KUOTAHABIS', 'usage_limit' => 5, 'used_count' => 5]);

        $this->get('/promo')
            ->assertOk()
            ->assertSee('AKTIF20')
            ->assertSee('Diskon 20%')
            ->assertSee('Min. belanja Rp150.000')
            ->assertDontSee('NONAKTIF')
            ->assertDontSee('KEDALUWARSA')
            ->assertDontSee('KUOTAHABIS');
    }

    public function test_promo_page_shows_empty_state_when_no_active_coupon_exists(): void
    {
        $this->makeCoupon(['is_active' => false]);

        $this->get('/promo')
            ->assertOk()
            ->assertSee('Belum ada kupon aktif')
            ->assertSee('Belanja sekarang');
    }

    public function test_homepage_fallback_uses_the_first_active_coupon(): void
    {
        $this->makeCoupon([
            'code' => 'WELCOME15',
            'discount_type' => 'percent',
            'discount_amount' => 15,
            'min_spend' => 100000,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('WELCOME15')
            ->assertSee('Diskon 15%')
            ->assertSee('Min. belanja Rp100.000');
    }

    public function test_cart_and_about_only_reference_the_active_coupon(): void
    {
        $this->makeCoupon([
            'code' => 'CARTKUPON',
            'discount_type' => 'fixed',
            'discount_amount' => 25000,
        ]);
        $this->makeCoupon(['code' => 'KODELAMA', 'is_active' => false]);

        $user = User::factory()->create();
        $variant = $this->makeVariant(price: 50000, stock: 3);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->get('/keranjang')
            ->assertOk()
            ->assertSee('CARTKUPON')
            ->assertSee('potongan Rp25.000')
            ->assertDontSee('KODELAMA');

        $this->actingAs($user)
            ->get('/tentang-kami')
            ->assertOk()
            ->assertSee('CARTKUPON')
            ->assertDontSee('KODELAMA');
    }

    public function test_help_page_shows_the_actual_shipping_cost(): void
    {
        SiteSetting::create(['key' => 'shipping_cost', 'value' => '45000']);

        $this->get('/bantuan')
            ->assertOk()
            ->assertSee('Ongkos kirim tetap Rp45.000 per pesanan')
            ->assertDontSee('Dihitung otomatis saat checkout berdasarkan alamat dan kurir yang dipilih');
    }

    public function test_checkout_page_and_order_use_the_shipping_cost_setting(): void
    {
        SiteSetting::create(['key' => 'shipping_cost', 'value' => '35000']);

        $user = User::factory()->create();
        $variant = $this->makeVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Rp 35.000');

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-shipping-test');
        });

        $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address' => 'Jl. Ongkir No. 1, Bandung',
            'recipient_name' => 'QA Ongkir',
            'recipient_phone' => '081234567890',
            'notes' => null,
        ])->assertRedirect();

        $order = Order::firstOrFail();

        $this->assertEquals(35000, (float) $order->shipping_cost);
        $this->assertEquals(235000, (float) $order->grand_total);
    }

    public function test_api_checkout_preview_uses_the_shipping_cost_setting(): void
    {
        SiteSetting::create(['key' => 'shipping_cost', 'value' => '32500']);

        $user = User::factory()->create();
        $variant = $this->makeVariant(price: 50000, stock: 3);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/checkout/preview');

        $response->assertOk();
        $this->assertEquals(32500, $response->json('data.shipping_cost'));
    }

    public function test_api_product_detail_hides_reviews_marked_invisible_by_admin(): void
    {
        $product = $this->makeProduct();
        $visibleUser = User::factory()->create();
        $hiddenUser = User::factory()->create();

        Review::create([
            'user_id' => $visibleUser->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Ulasan tampil via API.',
            'is_visible' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ]);

        Review::create([
            'user_id' => $hiddenUser->id,
            'product_id' => $product->id,
            'rating' => 1,
            'comment' => 'Ulasan tersembunyi via API.',
            'is_visible' => false,
            'is_featured' => false,
            'sort_order' => 0,
        ]);

        $this->getJson('/api/products/'.$product->slug)
            ->assertOk()
            ->assertJsonFragment(['comment' => 'Ulasan tampil via API.'])
            ->assertJsonMissing(['comment' => 'Ulasan tersembunyi via API.']);
    }

    private function makeCoupon(array $overrides = []): Coupon
    {
        return Coupon::create(array_merge([
            'code' => 'PROMO10',
            'discount_type' => 'percent',
            'discount_amount' => 10,
            'min_spend' => 0,
            'expires_at' => now()->addMonth(),
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ], $overrides));
    }

    private function makeProduct(int $price = 100000): Product
    {
        $category = Category::create([
            'name' => 'Kategori Sync',
            'slug' => 'kategori-sync',
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Sync',
            'slug' => 'produk-sync',
            'description' => 'Produk untuk pengujian sinkronisasi.',
            'price' => $price,
            'is_active' => true,
            'featured' => false,
        ]);
    }

    private function makeVariant(int $price, int $stock): ProductVariant
    {
        $product = $this->makeProduct($price);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-SYNC-1',
            'size' => 'M',
            'color' => 'Hitam',
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
