<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => $overrides['category_name'] ?? 'Gamis & Abaya Modern',
            'slug' => $overrides['category_slug'] ?? 'gamis-abaya-modern',
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => $overrides['name'] ?? 'Aurelia Silk Gamis Dress',
            'slug' => $overrides['slug'] ?? 'aurelia-silk-gamis-dress',
            'description' => $overrides['description'] ?? 'Gamis sutra satin premium yang adem dipakai seharian.',
            'price' => $overrides['price'] ?? 349000,
            'is_active' => $overrides['is_active'] ?? true,
            'featured' => $overrides['featured'] ?? false,
        ]);
    }

    public function test_product_detail_page_displays_product_information(): void
    {
        $product = $this->makeProduct(['featured' => true]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-AURE-SAG-S',
            'size' => 'S',
            'color' => 'Sage Green',
            'stock' => 15,
            'additional_price' => 0,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-AURE-HIT-XL',
            'size' => 'XL',
            'color' => 'Hitam Pekat',
            'stock' => 8,
            'additional_price' => 15000,
        ]);

        $reviewer = User::factory()->create(['name' => 'Siti Nurhaliza']);

        Review::create([
            'user_id' => $reviewer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Bahan silk-nya lembut dan adem.',
        ]);

        $response = $this->get('/produk/'.$product->slug);

        $response->assertOk()
            ->assertSee('Aurelia Silk Gamis Dress')
            ->assertSee('349.000')
            ->assertSee('Sage Green')
            ->assertSee('Hitam Pekat')
            ->assertSee('Siti Nurhaliza')
            ->assertSee('Bahan silk-nya lembut dan adem.')
            ->assertSee('Ulasan Pembeli')
            ->assertSee('Tambah ke Keranjang')
            ->assertSee('Unggulan');
    }

    public function test_product_detail_page_shows_related_products_from_other_categories(): void
    {
        $product = $this->makeProduct();

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-AURE-SAG-S',
            'size' => 'S',
            'color' => 'Sage Green',
            'stock' => 15,
            'additional_price' => 0,
        ]);

        $this->makeProduct([
            'category_name' => 'Korean Style Dress',
            'category_slug' => 'korean-style-dress',
            'name' => 'Ji-Woo Floral Ruffle Midi Dress',
            'slug' => 'ji-woo-floral-ruffle-midi-dress',
            'price' => 279000,
        ]);

        $response = $this->get('/produk/'.$product->slug);

        $response->assertOk()
            ->assertSee('Produk Serupa')
            ->assertSee('Ji-Woo Floral Ruffle Midi Dress');
    }

    public function test_inactive_product_detail_page_returns_not_found(): void
    {
        $product = $this->makeProduct([
            'name' => 'Produk Tidak Aktif',
            'slug' => 'produk-tidak-aktif',
            'is_active' => false,
        ]);

        $this->get('/produk/'.$product->slug)->assertNotFound();
    }
}
