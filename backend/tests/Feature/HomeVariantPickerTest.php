<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeVariantPickerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_featured_card_opens_variant_picker_for_multi_variant_product(): void
    {
        $product = $this->makeProduct('Dress Pilihan Beranda', 'dress-pilihan-beranda');
        $this->makeVariant($product, 'S', 'Hitam', 3);
        $this->makeVariant($product, 'M', 'Hitam', 2);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Dress Pilihan Beranda')
            ->assertSee('Pilih varian')
            ->assertSee('pickVariantOnAdd: true', false)
            ->assertSee("this.\$dispatch('open-variant-picker'", false);
    }

    public function test_home_single_variant_card_also_opens_variant_picker(): void
    {
        $product = $this->makeProduct('Dress Satu Varian', 'dress-satu-varian');
        $this->makeVariant($product, 'M', 'Hitam', 4);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Dress Satu Varian')
            ->assertSee('pickVariantOnAdd: true', false)
            ->assertSee("this.\$dispatch('open-variant-picker'", false);
    }

    public function test_catalog_card_opens_variant_picker(): void
    {
        $product = $this->makeProduct('Dress Katalog', 'dress-katalog');
        $this->makeVariant($product, 'S', 'Hitam', 3);
        $this->makeVariant($product, 'M', 'Hitam', 2);

        $response = $this->get('/produk');

        $response->assertOk()
            ->assertSee('Dress Katalog')
            ->assertSee('pickVariantOnAdd: true', false)
            ->assertSee("this.\$dispatch('open-variant-picker'", false);
    }

    public function test_category_page_card_opens_variant_picker(): void
    {
        $product = $this->makeProduct('Dress Halaman Kategori', 'dress-halaman-kategori');
        $this->makeVariant($product, 'S', 'Hitam', 3);
        $this->makeVariant($product, 'M', 'Hitam', 2);

        $response = $this->get('/kategori/'.$product->category->slug);

        $response->assertOk()
            ->assertSee('Dress Halaman Kategori')
            ->assertSee('pickVariantOnAdd: true', false)
            ->assertSee("this.\$dispatch('open-variant-picker'", false);
    }

    public function test_wishlist_card_opens_variant_picker(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct('Dress Wishlist', 'dress-wishlist');
        $this->makeVariant($product, 'S', 'Hitam', 3);
        $this->makeVariant($product, 'M', 'Hitam', 2);

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get('/wishlist');

        $response->assertOk()
            ->assertSee('Dress Wishlist')
            ->assertSee('pickVariantOnAdd: true', false)
            ->assertSee("this.\$dispatch('open-variant-picker'", false);
    }

    private function makeProduct(string $name, string $slug): Product
    {
        $category = Category::firstOrCreate(
            ['slug' => 'kategori-variant-picker'],
            ['name' => 'Kategori Variant Picker'],
        );

        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'description' => 'Produk untuk pengujian popup varian di beranda.',
            'price' => 250000,
            'is_active' => true,
            'featured' => true,
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
