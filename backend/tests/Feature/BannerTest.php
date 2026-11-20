<?php

namespace Tests\Feature;

use App\Filament\Resources\BannerResource;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_falls_back_to_static_hero_and_promo_when_no_banners_exist(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Temukan dress yang membuatmu merasa')
            ->assertSee('paling percaya diri')
            ->assertSee('Siap menemukan dress favoritmu')
            ->assertSee('Ada penawaran baru setiap bulan');
    }

    public function test_active_hero_banner_replaces_static_slides(): void
    {
        Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Judul hero dari CMS {highlight}',
            'highlight' => 'bagian aksen',
            'subtitle' => 'Deskripsi hero dari CMS.',
            'image' => 'banners/hero-cms.jpg',
            'image_alt' => 'Gambar hero CMS',
            'cta_label' => 'Belanja via CMS',
            'cta_url' => '/produk',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Judul hero dari CMS')
            ->assertSee('bagian aksen')
            ->assertSee('Belanja via CMS')
            ->assertDontSee('Temukan dress yang membuatmu merasa');
    }

    public function test_inactive_hero_banner_is_hidden(): void
    {
        Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Judul hero nonaktif',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertDontSee('Judul hero nonaktif')
            ->assertSee('Temukan dress yang membuatmu merasa');
    }

    public function test_promo_banner_renders_content_and_coupon_from_cms(): void
    {
        Banner::create([
            'position' => Banner::POSITION_PROMO,
            'eyebrow' => 'Promo CMS',
            'title' => 'Judul promo dari CMS',
            'highlight' => 'Diskon spesial minggu ini.',
            'subtitle' => 'Gunakan kupon di bawah.',
            'coupon_code' => 'CMSMERIAH',
            'image' => 'banners/promo-cms.jpg',
            'badge' => '35%',
            'cta_label' => 'Lihat promo CMS',
            'cta_url' => '/promo',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Judul promo dari CMS')
            ->assertSee('Diskon spesial minggu ini')
            ->assertSee('CMSMERIAH')
            ->assertSee('35%')
            ->assertSee('Lihat promo CMS');
    }

    public function test_inactive_promo_banner_falls_back_to_static_promo(): void
    {
        Banner::create([
            'position' => Banner::POSITION_PROMO,
            'title' => 'Judul promo nonaktif',
            'coupon_code' => 'NONAKTIF',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertDontSee('Judul promo nonaktif')
            ->assertDontSee('NONAKTIF')
            ->assertSee('Siap menemukan dress favoritmu')
            ->assertSee('Ada penawaran baru setiap bulan');
    }

    public function test_relative_cta_url_is_resolved_and_external_url_is_kept(): void
    {
        $relative = Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Banner relatif',
            'cta_url' => '/produk',
        ]);

        $external = Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Banner eksternal',
            'cta_url' => 'https://example.com/promo',
        ]);

        $this->assertSame(url('/produk'), $relative->resolvedCtaUrl());
        $this->assertSame('https://example.com/promo', $external->resolvedCtaUrl());
        $this->assertNull($external->resolvedSecondaryUrl());
    }

    public function test_footnote_lines_are_parsed_one_per_line(): void
    {
        $banner = Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Banner dengan poin',
            'footnote' => "Poin pertama\nPoin kedua\n\n",
        ]);

        $this->assertSame(['Poin pertama', 'Poin kedua'], $banner->footnoteLines());
    }

    public function test_admin_can_open_banner_resource_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $banner = Banner::create([
            'position' => Banner::POSITION_HERO,
            'title' => 'Banner yang bisa diedit admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(BannerResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Banner & Hero');

        $this->actingAs($admin)
            ->get(BannerResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Konten Banner')
            ->assertSee('Simpan Banner');

        $this->actingAs($admin)
            ->get(BannerResource::getUrl('edit', ['record' => $banner]))
            ->assertOk()
            ->assertSee('Banner yang bisa diedit admin');
    }
}
