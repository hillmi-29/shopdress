<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogCategoryHeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_shows_default_header_when_no_category_selected(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog lengkap');
        $response->assertSee('Produk (');
        $response->assertDontSee('Koleksi kategori');
        $response->assertDontSee('Semua produk');
    }

    public function test_catalog_shows_category_header_when_category_is_selected(): void
    {
        $category = Category::create([
            'name' => 'Gamis & Abaya Modern',
            'slug' => 'gamis-abaya-modern',
            'description' => 'Koleksi busana muslimah gamis dan abaya anggun dengan bahan premium adem dan jatuh.',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Nadia Pleated Abaya',
            'slug' => 'nadia-pleated-abaya',
            'description' => 'Abaya cantik',
            'price' => 289000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index', ['category' => 'gamis-abaya-modern']));

        $response->assertStatus(200);
        $response->assertSee('Koleksi kategori');
        $response->assertSee('Gamis & Abaya Modern');
        $response->assertSee('Koleksi busana muslimah gamis dan abaya anggun dengan bahan premium adem dan jatuh.');
        $response->assertSee('Semua produk');
        $response->assertDontSee('Katalog lengkap');
    }
}
