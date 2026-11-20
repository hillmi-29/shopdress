<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\FaqResource;
use App\Filament\Resources\StaticPageResource;
use App\Models\Faq;
use App\Models\SiteSetting;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_and_floating_help_use_default_site_settings(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Jakarta, Indonesia')
            ->assertSee('halo@shopdress.com')
            ->assertSee('09.00 – 21.00 WIB')
            ->assertSee('7 hari tukar ukuran')
            ->assertSee('wa.me/6281234567890');
    }

    public function test_admin_settings_override_contact_details_announcement_and_whatsapp(): void
    {
        $settings = [
            'store_address' => 'Bandung, Indonesia',
            'store_email' => 'care@contoh.test',
            'store_phone' => '+62 811-1111-2222',
            'whatsapp_number' => '0811-1111-2222',
            'announcement_enabled' => '1',
            'announcement_text' => 'Gratis ongkir minggu ini',
            'operational_hours' => '08.00 – 20.00 WIB',
            'operational_days' => 'Senin – Sabtu',
            'return_policy' => '14 hari tukar ukuran',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('Bandung, Indonesia')
            ->assertSee('care@contoh.test')
            ->assertSee('+62 811-1111-2222')
            ->assertSee('Gratis ongkir minggu ini')
            ->assertSee('08.00 – 20.00 WIB')
            ->assertSee('Senin – Sabtu')
            ->assertSee('14 hari tukar ukuran')
            ->assertSee('wa.me/6281111112222')
            ->assertDontSee('Jakarta, Indonesia')
            ->assertDontSee('halo@shopdress.com');
    }

    public function test_announcement_bar_is_hidden_when_disabled_or_empty(): void
    {
        SiteSetting::create(['key' => 'announcement_enabled', 'value' => '0']);
        SiteSetting::create(['key' => 'announcement_text', 'value' => 'Promo tersembunyi']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Promo tersembunyi');

        SiteSetting::query()
            ->where('key', 'announcement_enabled')
            ->update(['value' => '1']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Promo tersembunyi');
    }

    public function test_homepage_uses_cms_faq_and_hides_static_fallback(): void
    {
        Faq::create([
            'page' => Faq::PAGE_HOME,
            'question' => 'Pertanyaan dari CMS?',
            'answer' => 'Jawaban dari CMS.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Pertanyaan dari CMS?')
            ->assertSee('Jawaban dari CMS.')
            ->assertDontSee('Bagaimana cara memilih ukuran yang tepat?');
    }

    public function test_help_page_uses_cms_page_content_and_faqs(): void
    {
        StaticPage::create([
            'slug' => 'help',
            'kicker' => 'Pusat bantuan CMS',
            'title' => 'Bantuan dari',
            'highlight' => 'CMS',
            'subtitle' => 'Pembuka bantuan dari CMS.',
            'content' => '<p>Konten tambahan dari CMS.</p>',
            'meta_description' => 'Meta bantuan CMS.',
            'is_active' => true,
        ]);

        Faq::create([
            'page' => Faq::PAGE_HELP,
            'question' => 'FAQ bantuan dari CMS?',
            'answer' => 'Jawaban bantuan dari CMS.',
            'is_active' => true,
        ]);

        $this->get('/bantuan')
            ->assertOk()
            ->assertSee('Pusat bantuan CMS')
            ->assertSee('Bantuan dari')
            ->assertSee('Pembuka bantuan dari CMS.')
            ->assertSee('Konten tambahan dari CMS.')
            ->assertSee('FAQ bantuan dari CMS?')
            ->assertSee('Jawaban bantuan dari CMS.')
            ->assertDontSee('Apakah bisa belanja tanpa membuat akun?');
    }

    public function test_about_page_uses_cms_content(): void
    {
        StaticPage::create([
            'slug' => 'about',
            'kicker' => 'Kicker CMS',
            'title' => 'Judul tentang dari',
            'highlight' => 'CMS',
            'subtitle' => 'Pembuka tentang dari CMS.',
            'content' => '<p>Cerita tambahan dari CMS.</p>',
            'meta_description' => 'Meta tentang CMS.',
            'is_active' => true,
        ]);

        $this->get('/tentang-kami')
            ->assertOk()
            ->assertSee('Kicker CMS')
            ->assertSee('Judul tentang dari')
            ->assertSee('Pembuka tentang dari CMS.')
            ->assertSee('Cerita tambahan dari CMS.')
            ->assertDontSee('Busana yang tumbuh');
    }

    public function test_inactive_static_page_falls_back_to_built_in_content(): void
    {
        StaticPage::create([
            'slug' => 'about',
            'title' => 'Judul nonaktif',
            'is_active' => false,
        ]);

        $this->get('/tentang-kami')
            ->assertOk()
            ->assertDontSee('Judul nonaktif')
            ->assertSee('Busana yang tumbuh')
            ->assertSee('bersama ceritamu.');

        $this->get('/bantuan')
            ->assertOk()
            ->assertSee('Ada yang bisa')
            ->assertSee('kami bantu?')
            ->assertSee('Apakah bisa belanja tanpa membuat akun?');
    }

    public function test_whatsapp_number_is_normalized_for_wa_me_links(): void
    {
        $this->assertSame('6281234567890', SiteSetting::normalizeWhatsappNumber('0812-3456-7890'));
        $this->assertSame('6281234567890', SiteSetting::normalizeWhatsappNumber('+62 812 3456 7890'));
        $this->assertNull(SiteSetting::normalizeWhatsappNumber(''));
    }

    public function test_admin_can_save_site_settings_from_filament_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'store_address' => 'Surabaya, Indonesia',
                'store_email' => 'halo@contoh.test',
                'whatsapp_number' => '0812 9876 5432',
                'announcement_enabled' => true,
                'announcement_text' => 'Pengumuman baru',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $settings = SiteSetting::values();

        $this->assertSame('Surabaya, Indonesia', $settings['store_address']);
        $this->assertSame('halo@contoh.test', $settings['store_email']);
        $this->assertSame('6281298765432', $settings['whatsapp_number']);
        $this->assertTrue($settings['announcement_enabled']);
        $this->assertSame('Pengumuman baru', $settings['announcement_text']);
    }

    public function test_admin_can_open_content_management_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(FaqResource::getUrl('index'))
            ->assertOk()
            ->assertSee('FAQ');

        $this->actingAs($admin)
            ->get(FaqResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Konten FAQ');

        $this->actingAs($admin)
            ->get(StaticPageResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Halaman Statis');

        $this->actingAs($admin)
            ->get(StaticPageResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Identitas Halaman');

        $this->actingAs($admin)
            ->get(ManageSiteSettings::getUrl())
            ->assertOk()
            ->assertSee('Pengaturan Toko')
            ->assertSee('Kontak Toko');
    }
}
