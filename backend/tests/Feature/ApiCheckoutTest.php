<?php

namespace Tests\Feature;

use App\Mail\OrderCreatedMail;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_checkout_creates_order_and_returns_snap_token(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $coupon = Coupon::create([
            'code' => 'API10',
            'discount_type' => 'percent',
            'discount_amount' => 10,
            'min_spend' => 0,
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);
        Mail::fake();

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-token-api');
        });

        $response = $this->postJson('/api/checkout', [
            'shipping_address' => 'Jl. API No. 1, Jakarta',
            'recipient_name' => 'QA API',
            'recipient_phone' => '081200000001',
            'notes' => 'Test API',
            'coupon_code' => 'API10',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'data' => ['snap_token' => 'snap-token-api'],
            ]);

        $order = Order::firstOrFail();
        $this->assertSame('snap-token-api', $order->snap_token);
        $this->assertSame('Jl. API No. 1, Jakarta', $order->shipping_address);
        $this->assertEquals(200000, (float) $order->total_amount);
        $this->assertEquals(20000, (float) $order->discount_amount);
        $this->assertEquals(200000, (float) $order->grand_total);

        $this->assertSame(3, $variant->fresh()->stock);
        $this->assertSame(1, $coupon->fresh()->used_count);
        $this->assertSame(0, $cart->fresh()->items()->count());
        Mail::assertQueued(OrderCreatedMail::class);
    }

    public function test_api_checkout_leaves_no_side_effects_when_midtrans_fails(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $coupon = Coupon::create([
            'code' => 'APIFAIL',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'min_spend' => 0,
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);
        Mail::fake();

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')
                ->once()
                ->andThrow(new \Exception('Midtrans unavailable'));
        });

        $response = $this->postJson('/api/checkout', [
            'shipping_address' => 'Jl. API No. 1, Jakarta',
            'recipient_name' => 'QA API',
            'recipient_phone' => '081200000001',
            'notes' => null,
            'coupon_code' => 'APIFAIL',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'message' => 'Checkout gagal diproses. Silakan coba lagi atau hubungi CS.',
            ]);
        $this->assertStringNotContainsString('Midtrans unavailable', $response->getContent());

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(5, $variant->fresh()->stock);
        $this->assertSame(0, $coupon->fresh()->used_count);
        $this->assertSame(1, $cart->fresh()->items()->count());
        Mail::assertNothingQueued();
    }

    private function createProductVariant(int $price, int $stock): ProductVariant
    {
        $category = Category::create(['name' => 'Kategori API Checkout']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk API Checkout',
            'description' => 'Produk untuk test API checkout.',
            'price' => $price,
            'is_active' => true,
            'featured' => false,
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-API-CHECKOUT-1',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
