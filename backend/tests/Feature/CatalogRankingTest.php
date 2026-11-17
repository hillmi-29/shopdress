<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogRankingTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    private function makeProduct(Category $category, string $name, string $slug): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'description' => 'Deskripsi '.$name,
            'price' => 250000,
            'is_active' => true,
            'featured' => false,
        ]);
    }

    private function makeVariant(Product $product, string $sku): ProductVariant
    {
        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'size' => 'M',
            'color' => 'Black',
            'stock' => 10,
            'additional_price' => 0,
        ]);
    }

    private function recordOrder(User $user, Product $product, ProductVariant $variant, int $quantity, PaymentStatus $paymentStatus): void
    {
        $order = Order::create([
            'order_number' => 'ORD-RANK-'.str_pad((string) Order::count(), 4, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'total_amount' => $product->price * $quantity,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'grand_total' => $product->price * $quantity,
            'status' => OrderStatus::PAID,
            'payment_status' => $paymentStatus,
            'shipping_address' => 'Jl. Pengujian No. 1',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'variant_details' => [
                'size' => $variant->size,
                'color' => $variant->color,
                'sku' => $variant->sku,
            ],
            'price' => $product->price,
            'quantity' => $quantity,
            'subtotal' => $product->price * $quantity,
        ]);
    }

    public function test_catalog_and_category_rankings_follow_settled_sales(): void
    {
        $user = User::factory()->create();
        $slowCategory = $this->makeCategory('Kategori Sepi', 'kategori-sepi');
        $fastCategory = $this->makeCategory('Kategori Laris', 'kategori-laris');

        $slowProduct = $this->makeProduct($slowCategory, 'Produk Baru Belum Terjual', 'produk-baru-belum-terjual');
        $fastProduct = $this->makeProduct($fastCategory, 'Produk Lama Paling Laris', 'produk-lama-paling-laris');

        $fastVariant = $this->makeVariant($fastProduct, 'SD-FAST-BLK-M');
        $this->recordOrder($user, $fastProduct, $fastVariant, 5, PaymentStatus::SETTLED);

        $this->assertSame(
            [$fastProduct->id, $slowProduct->id],
            Product::where('is_active', true)->orderByPopularity()->pluck('id')->all()
        );

        $this->assertSame(
            [$fastCategory->id, $slowCategory->id],
            Category::withActiveProductCount()->orderByPopularity()->pluck('id')->all()
        );

        $this->get('/produk')
            ->assertOk()
            ->assertSeeInOrder([$fastProduct->name, $slowProduct->name]);
    }

    public function test_unsettled_orders_do_not_affect_rankings(): void
    {
        $user = User::factory()->create();
        $newerCategory = $this->makeCategory('Kategori Baru', 'kategori-baru');
        $olderCategory = $this->makeCategory('Kategori Lama', 'kategori-lama');

        $olderProduct = $this->makeProduct($olderCategory, 'Produk Lama', 'produk-lama');
        $newerProduct = $this->makeProduct($newerCategory, 'Produk Baru', 'produk-baru');

        $olderVariant = $this->makeVariant($olderProduct, 'SD-OLD-BLK-M');
        $this->recordOrder($user, $olderProduct, $olderVariant, 100, PaymentStatus::UNPAID);

        $this->assertSame(
            [$newerProduct->id, $olderProduct->id],
            Product::where('is_active', true)->orderByPopularity()->pluck('id')->all()
        );

        $this->assertSame(
            [$newerCategory->id, $olderCategory->id],
            Category::withActiveProductCount()->orderByPopularity()->pluck('id')->all()
        );
    }

    public function test_category_product_count_only_includes_active_products(): void
    {
        $category = $this->makeCategory('Kategori Aktif', 'kategori-aktif');
        $this->makeProduct($category, 'Produk Aktif', 'produk-aktif');

        Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Nonaktif',
            'slug' => 'produk-nonaktif',
            'description' => 'Produk yang disembunyikan.',
            'price' => 150000,
            'is_active' => false,
            'featured' => false,
        ]);

        $rankedCategory = Category::withActiveProductCount()
            ->orderByPopularity()
            ->firstOrFail();

        $this->assertSame(1, $rankedCategory->products_count);
    }
}
