<?php

namespace Tests\Feature;

use App\Filament\Resources\ReviewResource;
use App\Filament\Resources\ReviewResource\Pages\EditReview;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => 'Gamis & Abaya Modern',
            'slug' => 'gamis-abaya-modern',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => $overrides['name'] ?? 'Aurelia Silk Gamis Dress',
            'slug' => $overrides['slug'] ?? 'aurelia-silk-gamis-dress',
            'description' => 'Gamis sutra satin premium yang adem dipakai seharian.',
            'price' => 349000,
            'is_active' => true,
            'featured' => false,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SD-TEST-001',
            'size' => 'M',
            'color' => 'Sage Green',
            'stock' => 10,
            'additional_price' => 0,
        ]);

        return $product;
    }

    private function makeReview(User $user, Product $product, array $overrides = []): Review
    {
        return Review::create(array_merge([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Ulasan pelanggan.',
            'is_visible' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ], $overrides));
    }

    public function test_homepage_prefers_curated_featured_testimonials(): void
    {
        $product = $this->makeProduct();
        $featuredUser = User::factory()->create(['name' => 'Pelanggan Unggulan']);
        $regularUser = User::factory()->create(['name' => 'Pelanggan Biasa']);

        $this->makeReview($featuredUser, $product, [
            'comment' => 'Testimoni pilihan admin untuk beranda.',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $this->makeReview($regularUser, $product, [
            'comment' => 'Ulasan biasa yang tidak dikurasi.',
            'rating' => 5,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Testimoni pilihan admin untuk beranda.')
            ->assertSee('Pelanggan Unggulan')
            ->assertDontSee('Ulasan biasa yang tidak dikurasi.');
    }

    public function test_homepage_falls_back_to_recent_high_rated_visible_reviews(): void
    {
        $product = $this->makeProduct();
        $visibleUser = User::factory()->create();
        $hiddenUser = User::factory()->create();

        $this->makeReview($visibleUser, $product, [
            'comment' => 'Ulasan tinggi yang tetap tampil.',
            'rating' => 5,
        ]);

        $this->makeReview($hiddenUser, $product, [
            'comment' => 'Ulasan yang disembunyikan admin.',
            'rating' => 5,
            'is_visible' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Ulasan tinggi yang tetap tampil.')
            ->assertDontSee('Ulasan yang disembunyikan admin.');
    }

    public function test_homepage_hides_testimonial_section_when_all_reviews_are_hidden(): void
    {
        $product = $this->makeProduct();
        $user = User::factory()->create();

        $this->makeReview($user, $product, [
            'comment' => 'Ulasan tersembunyi.',
            'is_featured' => true,
            'is_visible' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Cerita dari pelanggan kami')
            ->assertDontSee('Ulasan tersembunyi.');
    }

    public function test_hidden_reviews_are_excluded_from_product_page_and_rating_average(): void
    {
        $product = $this->makeProduct();
        $visibleUser = User::factory()->create();
        $hiddenUser = User::factory()->create();

        $this->makeReview($visibleUser, $product, [
            'rating' => 4,
            'comment' => 'Ulasan yang tampil di halaman produk.',
        ]);

        $this->makeReview($hiddenUser, $product, [
            'rating' => 1,
            'comment' => 'Ulasan yang disembunyikan dari pembeli.',
            'is_visible' => false,
        ]);

        $this->get('/produk/'.$product->slug)
            ->assertOk()
            ->assertSee('Ulasan yang tampil di halaman produk.')
            ->assertDontSee('Ulasan yang disembunyikan dari pembeli.')
            ->assertSee('dari 1 ulasan')
            ->assertDontSee('dari 2 ulasan');

        $this->assertSame(4.0, $product->fresh()->average_rating);
    }

    public function test_admin_can_open_and_update_review_curation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->makeProduct();
        $customer = User::factory()->create(['name' => 'Siti Nurhaliza']);

        $review = $this->makeReview($customer, $product, [
            'comment' => 'Ulasan yang akan dikurasi admin.',
        ]);

        $this->actingAs($admin)
            ->get(ReviewResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Ulasan & Testimoni');

        $this->actingAs($admin)
            ->get(ReviewResource::getUrl('edit', ['record' => $review]))
            ->assertOk()
            ->assertSee('Ulasan yang akan dikurasi admin.');

        Livewire::test(EditReview::class, ['record' => $review->getRouteKey()])
            ->fillForm([
                'rating' => 4,
                'is_visible' => false,
                'is_featured' => true,
                'sort_order' => 7,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $review->refresh();

        $this->assertSame(4, $review->rating);
        $this->assertFalse($review->is_visible);
        $this->assertTrue($review->is_featured);
        $this->assertSame(7, $review->sort_order);
    }
}
