<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyNowTest extends TestCase
{
    use RefreshDatabase;

    public function test_single_variant_product_card_offers_direct_buy_now(): void
    {
        $product = $this->makeProduct('Produk Varian Tunggal', 'produk-varian-tunggal');
        $variant = $this->makeVariant($product, 'M', 'Hitam', 5);

        $this->get('/produk')
            ->assertOk()
            ->assertSee('Beli Sekarang')
            ->assertSee("buy-now', { variantId: {$variant->id}, quantity: 1 }", false);
    }

    public function test_multi_variant_product_card_links_to_product_page_for_variant_selection(): void
    {
        $product = $this->makeProduct('Produk Banyak Varian', 'produk-banyak-varian');
        $this->makeVariant($product, 'S', 'Hitam', 3);
        $this->makeVariant($product, 'M', 'Hitam', 3);

        $this->get('/produk')
            ->assertOk()
            ->assertSee('Beli Sekarang')
            ->assertSee('Pilih ukuran dan warna terlebih dahulu')
            ->assertSee(route('products.show', $product), false)
            ->assertDontSee("buy-now', { variantId:", false);
    }

    public function test_out_of_stock_product_card_shows_stock_label_without_buy_now(): void
    {
        $product = $this->makeProduct('Produk Habis', 'produk-habis');
        $this->makeVariant($product, 'M', 'Hitam', 0);

        $this->get('/produk')
            ->assertOk()
            ->assertSee('Stok Habis')
            ->assertDontSee("buy-now', { variantId:", false);
    }

    public function test_product_detail_offers_buy_now_with_selected_variant(): void
    {
        $product = $this->makeProduct('Produk Detail', 'produk-detail');
        $this->makeVariant($product, 'M', 'Hitam', 5);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Beli Sekarang')
            ->assertSee("this.\$dispatch('buy-now'", false)
            ->assertSee('Tambah ke Keranjang');
    }

    public function test_buy_now_checkout_only_orders_selected_product_and_keeps_cart(): void
    {
        $user = User::factory()->create();

        $cartVariant = $this->makeVariant(
            $this->makeProduct('Produk di Keranjang', 'produk-di-keranjang', 150000),
            'M',
            'Hitam',
            4,
        );
        $buyNowVariant = $this->makeVariant(
            $this->makeProduct('Produk Beli Sekarang', 'produk-beli-sekarang', 250000),
            'L',
            'Putih',
            3,
        );

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $cartVariant->id,
            'quantity' => 2,
        ]);

        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->once()->andReturn('snap-buy-now');
        });

        $response = $this->actingAs($user)
            ->withSession([
                'buy_now' => [
                    'product_variant_id' => $buyNowVariant->id,
                    'quantity' => 1,
                ],
            ])
            ->post(route('checkout.process'), [
                'checkout_mode' => 'buy_now',
                'shipping_address' => 'Jl. Beli Sekarang No. 1, Bandung',
                'recipient_name' => 'Pembeli Sekarang',
                'recipient_phone' => '081200000001',
            ]);

        $order = Order::with('items')->firstOrFail();

        $response->assertRedirect(route('checkout.payment', ['order' => $order->id]));
        $response->assertSessionMissing('buy_now');

        $this->assertCount(1, $order->items);
        $this->assertSame($buyNowVariant->id, $order->items->first()->product_variant_id);
        $this->assertSame(1, $order->items->first()->quantity);
        $this->assertEquals(250000, (float) $order->total_amount);

        // Keranjang lama dan stoknya tidak boleh ikut berubah.
        $this->assertSame(1, $cart->fresh()->items()->count());
        $this->assertSame(2, $cart->fresh()->items()->first()->quantity);
        $this->assertSame(4, $cartVariant->fresh()->stock);
        $this->assertSame(2, $buyNowVariant->fresh()->stock);
    }

    public function test_buy_now_checkout_page_shows_only_the_selected_product(): void
    {
        $user = User::factory()->create();

        $cartVariant = $this->makeVariant(
            $this->makeProduct('Produk Keranjang Lama', 'produk-keranjang-lama', 150000),
            'M',
            'Hitam',
            4,
        );
        $buyNowVariant = $this->makeVariant(
            $this->makeProduct('Produk Pilihan Sekarang', 'produk-pilihan-sekarang', 250000),
            'L',
            'Putih',
            3,
        );

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $cartVariant->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'buy_now' => [
                    'product_variant_id' => $buyNowVariant->id,
                    'quantity' => 1,
                ],
            ])
            ->get(route('checkout.index'));

        $response->assertOk()
            ->assertSee('Produk Pilihan Sekarang')
            ->assertDontSee('Produk Keranjang Lama')
            ->assertSee('name="checkout_mode" value="buy_now"', false);
    }

    public function test_opening_cart_page_discards_buy_now_draft(): void
    {
        $user = User::factory()->create();
        $variant = $this->makeVariant(
            $this->makeProduct('Produk Draft', 'produk-draft', 200000),
            'M',
            'Hitam',
            3,
        );

        $response = $this->actingAs($user)
            ->withSession([
                'buy_now' => [
                    'product_variant_id' => $variant->id,
                    'quantity' => 1,
                ],
            ])
            ->get(route('cart.index'));

        $response->assertOk()->assertSessionMissing('buy_now');
    }

    public function test_buy_now_api_only_remembers_draft_without_touching_cart(): void
    {
        $user = User::factory()->create();

        $cartVariant = $this->makeVariant(
            $this->makeProduct('Produk Keranjang', 'produk-keranjang', 150000),
            'M',
            'Hitam',
            4,
        );
        $buyNowVariant = $this->makeVariant(
            $this->makeProduct('Produk API Beli Sekarang', 'produk-api-beli-sekarang', 250000),
            'L',
            'Putih',
            3,
        );

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $cartVariant->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->postJson('/api/buy-now', [
            'product_variant_id' => $buyNowVariant->id,
            'quantity' => 2,
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'redirect' => route('checkout.index'),
            ])
            ->assertSessionHas('buy_now', [
                'product_variant_id' => $buyNowVariant->id,
                'quantity' => 2,
            ]);

        $this->assertSame(1, $cart->fresh()->items()->count());
        $this->assertSame($cartVariant->id, $cart->fresh()->items()->first()->product_variant_id);
        $this->assertSame(3, $buyNowVariant->fresh()->stock);
    }

    public function test_cart_checkout_mode_ignores_buy_now_draft(): void
    {
        $user = User::factory()->create();

        $cartVariant = $this->makeVariant(
            $this->makeProduct('Produk Keranjang Mode', 'produk-keranjang-mode', 150000),
            'M',
            'Hitam',
            4,
        );
        $buyNowVariant = $this->makeVariant(
            $this->makeProduct('Produk Draft Mode', 'produk-draft-mode', 250000),
            'L',
            'Putih',
            3,
        );

        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $cartVariant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'buy_now' => [
                    'product_variant_id' => $buyNowVariant->id,
                    'quantity' => 1,
                ],
            ])
            ->get(route('checkout.index', ['mode' => 'cart']));

        $response->assertOk()
            ->assertSee('Produk Keranjang Mode')
            ->assertDontSee('Produk Draft Mode')
            ->assertSee('name="checkout_mode" value="cart"', false)
            ->assertSessionMissing('buy_now');
    }

    private function makeProduct(string $name, string $slug, int $price = 250000): Product
    {
        $category = Category::firstOrCreate(
            ['slug' => 'kategori-buy-now'],
            ['name' => 'Kategori Buy Now'],
        );

        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'description' => 'Produk untuk pengujian tombol Beli Sekarang.',
            'price' => $price,
            'is_active' => true,
            'featured' => false,
        ]);
    }

    private function makeVariant(Product $product, string $size, string $color, int $stock): ProductVariant
    {
        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => strtoupper($product->slug.'-'.$size.'-'.$color),
            'size' => $size,
            'color' => $color,
            'stock' => $stock,
            'additional_price' => 0,
        ]);
    }
}
