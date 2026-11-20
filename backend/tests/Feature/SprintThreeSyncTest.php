<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SprintThreeSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_home_serves_cms_content_managed_from_admin(): void
    {
        SiteSetting::create(['key' => 'store_name', 'value' => 'Atelier Nusa']);
        SiteSetting::create(['key' => 'payment_methods', 'value' => 'BCA, QRIS']);

        $product = $this->makeProduct();

        $reviewer = User::factory()->create(['name' => 'Rina Pembeli']);

        $this->makeReview($reviewer, $product, 5);

        // Ulasan tersembunyi tidak boleh muncul di API maupun statistik.
        $this->makeReview(User::factory()->create(), $product, 1, false);

        Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Koleksi Uji',
            'eyebrow' => 'Koleksi 2026',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Banner Nonaktif',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        Banner::create([
            'position' => Banner::POSITION_PROMO,
            'title' => 'Promo Uji',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Faq::create([
            'page' => Faq::PAGE_HOME,
            'question' => 'Apa keunggulan bahan?',
            'answer' => 'Bahan dikurasi dan nyaman dipakai.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Faq::create([
            'page' => Faq::PAGE_HELP,
            'question' => 'Pertanyaan halaman bantuan',
            'answer' => 'Tidak boleh ikut di API beranda.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Coupon::create([
            'code' => 'UJI10',
            'discount_type' => 'percent',
            'discount_amount' => 10,
            'min_spend' => 0,
            'is_active' => true,
        ]);

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonPath('data.site_settings.store_name', 'Atelier Nusa')
            ->assertJsonPath('data.site_settings.payment_methods', 'BCA, QRIS')
            ->assertJsonPath('data.hero_banners.0.title', 'Koleksi Uji')
            ->assertJsonCount(1, 'data.hero_banners')
            ->assertJsonPath('data.promo_banner.title', 'Promo Uji')
            ->assertJsonPath('data.featured_coupon.code', 'UJI10')
            ->assertJsonPath('data.featured_coupon.discount_label', 'Diskon 10%')
            ->assertJsonPath('data.home_faqs.0.question', 'Apa keunggulan bahan?')
            ->assertJsonCount(1, 'data.home_faqs')
            ->assertJsonPath('data.testimonials.0.customer_name', 'Rina Pembeli')
            ->assertJsonPath('data.testimonial_stats.count', 1)
            ->assertJsonPath('data.testimonial_stats.average', 5)
            ->assertJsonPath('data.customer_favorites.0.slug', 'produk-sprint-tiga')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'site_settings',
                    'hero_banners',
                    'promo_banner',
                    'featured_coupon',
                    'home_faqs',
                    'testimonials',
                    'testimonial_stats' => ['count', 'average'],
                    'customer_favorites',
                    'categories',
                    'featured_products',
                    'latest_products',
                ],
            ]);
    }

    public function test_not_found_page_is_fully_in_indonesian(): void
    {
        $this->get('/halaman-tidak-ada')
            ->assertNotFound()
            ->assertSee('HALAMAN TIDAK DITEMUKAN')
            ->assertSee('Maaf, halaman yang Anda cari tidak tersedia.')
            ->assertSee('BERANDA')
            ->assertDontSee('PAGE NOT FOUND')
            ->assertDontSee('We are sorry');
    }

    private function makeProduct(): Product
    {
        $category = Category::create([
            'name' => 'Kategori Sprint Tiga',
            'slug' => 'kategori-sprint-tiga',
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Sprint Tiga',
            'slug' => 'produk-sprint-tiga',
            'description' => 'Produk untuk pengujian Sprint 3.',
            'price' => 250000,
            'is_active' => true,
            'featured' => false,
        ]);
    }

    private function makeReview(User $user, Product $product, int $rating, bool $visible = true): Review
    {
        return Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $rating,
            'comment' => 'Ulasan pengujian Sprint 3.',
            'is_visible' => $visible,
            'is_featured' => true,
            'sort_order' => 0,
        ]);
    }
}
