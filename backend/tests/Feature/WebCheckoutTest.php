<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_checkout_creates_order_with_shipping_data_coupon_and_variant_details(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $coupon = Coupon::create([
            'code' => 'TEST10',
            'discount_type' => 'percent',
            'discount_amount' => 10,
            'min_spend' => 0,
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-token-test');
        });

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address' => 'Jl. QA No. 1, Bandung',
            'recipient_name' => 'QA Penerima',
            'recipient_phone' => '081234567890',
            'notes' => 'Tolong dibungkus rapi.',
            'coupon_code' => 'TEST10',
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('checkout.payment', ['order' => $order->id]));
        $response->assertSessionHas('snap_token', 'snap-token-test');

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('Jl. QA No. 1, Bandung', $order->shipping_address);
        $this->assertSame('QA Penerima', $order->recipient_name);
        $this->assertSame('081234567890', $order->recipient_phone);
        $this->assertSame('Tolong dibungkus rapi.', $order->notes);
        $this->assertEquals(200000, (float) $order->total_amount);
        $this->assertEquals(20000, (float) $order->shipping_cost);
        $this->assertEquals(20000, (float) $order->discount_amount);
        $this->assertEquals(200000, (float) $order->grand_total);
        $this->assertSame(OrderStatus::PENDING, $order->status);
        $this->assertSame(PaymentStatus::UNPAID, $order->payment_status);
        $this->assertSame('snap-token-test', $order->snap_token);

        $orderItem = $order->items()->firstOrFail();
        $this->assertSame($variant->id, $orderItem->product_variant_id);
        $this->assertSame('SD-WEB-CHECKOUT-1', $orderItem->variant_details['sku']);
        $this->assertSame('M', $orderItem->variant_details['size']);
        $this->assertSame('Sage Green', $orderItem->variant_details['color']);
        $this->assertSame(2, $orderItem->quantity);
        $this->assertEquals(200000, (float) $orderItem->subtotal);

        $this->assertSame(3, $variant->fresh()->stock);
        $this->assertSame(1, $coupon->fresh()->used_count);
        $this->assertSame(0, $cart->fresh()->items()->count());
    }

    public function test_web_checkout_rejects_invalid_coupon_without_side_effects(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        Coupon::create([
            'code' => 'EXPIRED',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'min_spend' => 0,
            'expires_at' => now()->subDay(),
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldNotReceive('createSnapToken');
        });

        $response = $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.process'), [
                'shipping_address' => 'Jl. QA No. 1, Bandung',
                'recipient_name' => 'QA Penerima',
                'recipient_phone' => '081234567890',
                'notes' => null,
                'coupon_code' => 'EXPIRED',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(5, $variant->fresh()->stock);
        $this->assertSame(1, $cart->fresh()->items()->count());
    }

    public function test_web_checkout_leaves_no_side_effects_when_midtrans_fails(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $coupon = Coupon::create([
            'code' => 'FAIL10',
            'discount_type' => 'percent',
            'discount_amount' => 10,
            'min_spend' => 0,
            'expires_at' => now()->addDay(),
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')
                ->once()
                ->andThrow(new \Exception('Midtrans unavailable'));
        });

        $response = $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.process'), [
                'shipping_address' => 'Jl. QA No. 1, Bandung',
                'recipient_name' => 'QA Penerima',
                'recipient_phone' => '081234567890',
                'notes' => null,
                'coupon_code' => 'FAIL10',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error', 'Checkout gagal diproses. Silakan coba lagi atau hubungi CS.');
        $this->assertStringNotContainsString('Midtrans unavailable', (string) session('error'));

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(5, $variant->fresh()->stock);
        $this->assertSame(0, $coupon->fresh()->used_count);
        $this->assertSame(1, $cart->fresh()->items()->count());
    }

    public function test_web_checkout_fails_safely_when_midtrans_is_not_configured(): void
    {
        config([
            'midtrans.server_key' => 'SB-Mid-server-YOUR_SERVER_KEY',
            'midtrans.client_key' => 'SB-Mid-client-YOUR_CLIENT_KEY',
        ]);

        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.process'), [
                'shipping_address' => 'Jl. QA No. 1, Bandung',
                'recipient_name' => 'QA Penerima',
                'recipient_phone' => '081234567890',
                'notes' => null,
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error', 'Pembayaran online belum dikonfigurasi. Silakan hubungi admin.');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(5, $variant->fresh()->stock);
        $this->assertSame(1, $cart->fresh()->items()->count());
    }

    public function test_checkout_page_renders_region_dropdowns(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 100000, stock: 5);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertOk()
            ->assertSee('Provinsi')
            ->assertSee('Kota / Kabupaten')
            ->assertSee('Kecamatan')
            ->assertSee('Kelurahan / Desa')
            ->assertSee('RT / RW')
            ->assertSee('Kode Pos')
            ->assertSee('Detail Jalan')
            ->assertSee('Foto Patokan Lokasi')
            ->assertDontSee('Format Alamat Pengiriman:');
    }

    public function test_web_checkout_can_upload_landmark_photo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();
        $variant = $this->createProductVariant(price: 150000, stock: 3);

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-landmark-test');
        });

        $fakePhoto = \Illuminate\Http\UploadedFile::fake()->image('patokan_rumah.jpg', 600, 600);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address' => 'Jl. Braga No. 10, RT 001/RW 002, Desa/Kel. Braga, Kec. Sumur Bandung, Kota Bandung, Jawa Barat, 40111',
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081298765432',
            'notes' => 'Pagar warna hitam',
            'landmark_photo' => $fakePhoto,
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('checkout.payment', ['order' => $order->id]));
        $this->assertNotNull($order->landmark_photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($order->landmark_photo);
    }

    private function createProductVariant(int $price, int $stock): ProductVariant
    {
        $category = Category::create(['name' => 'Kategori Web Checkout']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Web Checkout',
            'description' => 'Produk untuk test web checkout.',
            'price' => $price,
            'is_active' => true,
            'featured' => false,
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-WEB-CHECKOUT-1',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
