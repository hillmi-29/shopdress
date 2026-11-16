<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendNavigationTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name = 'Gamis & Abaya Modern', string $slug = 'gamis-abaya-modern'): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    private function makeProduct(Category $category, array $overrides = []): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $overrides['name'] ?? 'Aurelia Silk Gamis Dress',
            'slug' => $overrides['slug'] ?? 'aurelia-silk-gamis-dress',
            'description' => 'Gamis sutra satin premium.',
            'price' => $overrides['price'] ?? 349000,
            'is_active' => true,
            'featured' => $overrides['featured'] ?? false,
        ]);
    }

    public function test_product_links_use_slugs_instead_of_ids(): void
    {
        $category = $this->makeCategory();
        $product = $this->makeProduct($category, ['featured' => true]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-AURE-SAG-S',
            'size' => 'S',
            'color' => 'Sage Green',
            'stock' => 15,
            'additional_price' => 0,
        ]);

        foreach (['/', '/produk'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('/produk/'.$product->slug)
                ->assertDontSee('href="'.url('/produk/'.$product->id).'"', false);
        }
    }

    public function test_category_links_use_slugs_instead_of_ids(): void
    {
        $category = $this->makeCategory();

        $this->get('/kategori')
            ->assertOk()
            ->assertSee('/kategori/'.$category->slug)
            ->assertDontSee('href="'.url('/kategori/'.$category->id).'"', false);
    }

    public function test_help_page_returns_200_with_all_sections(): void
    {
        $this->get('/bantuan')
            ->assertOk()
            ->assertSee('Cara Pemesanan')
            ->assertSee('Pengiriman')
            ->assertSee('Pengembalian')
            ->assertSee('Pertanyaan Umum');
    }
}
