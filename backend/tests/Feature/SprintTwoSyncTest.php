<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Pages\ManageSiteSettings;
use App\Mail\OrderCreatedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SprintTwoSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_stats_come_from_customers_reviews_and_return_policy(): void
    {
        SiteSetting::create(['key' => 'return_policy', 'value' => '14 hari tukar ukuran']);

        $product = $this->makeProduct();

        foreach ([5, 4, 3] as $rating) {
            $this->makeReview(User::factory()->create(), $product, $rating);
        }

        $this->makeReview(User::factory()->create(), $product, 1, false);

        $this->get('/tentang-kami')
            ->assertOk()
            ->assertSee('4+')
            ->assertSee('Pelanggan bahagia')
            ->assertSee('4,0/5')
            ->assertSee('Rating pelanggan')
            ->assertSee('14 hari tukar ukuran')
            ->assertDontSee('3,3/5')
            ->assertDontSee('10.000+')
            ->assertDontSee('4,9/5');
    }

    public function test_emails_use_store_identity_brand_color_and_correct_order_link(): void
    {
        $this->setSettings([
            'store_name' => 'Atelier Nusa',
            'brand_color' => '#123456',
        ]);

        $order = $this->makeOrder();

        $mailables = [
            new OrderCreatedMail($order),
            new OrderStatusUpdatedMail($order),
            new PaymentSuccessMail($order),
        ];

        foreach ($mailables as $mail) {
            $html = $mail->render();

            $this->assertStringContainsString('Atelier Nusa', $html);
            $this->assertStringContainsString('#123456', $html);
            $this->assertStringContainsString(route('orders.show', $order->id), $html);
            $this->assertStringNotContainsString(url('/orders/'.$order->id), $html);
            $this->assertStringContainsString('Atelier Nusa', $mail->envelope()->subject);
        }
    }

    public function test_brand_payment_methods_and_legal_links_come_from_site_settings(): void
    {
        $this->setSettings([
            'store_name' => 'Atelier Nusa',
            'brand_color' => '#123456',
            'payment_methods' => 'BCA, QRIS SAJA',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Atelier Nusa')
            ->assertSee('BCA')
            ->assertSee('QRIS SAJA')
            ->assertSee('#123456', false)
            ->assertSee(route('terms'))
            ->assertSee(route('privacy'))
            ->assertDontSee('ShopDress')
            ->assertDontSee('MANDIRI');

        $this->get('/tentang-kami')
            ->assertOk()
            ->assertSee('Tentang Kami — Atelier Nusa');

        $this->get('/bantuan')
            ->assertOk()
            ->assertSee('Bantuan — Atelier Nusa');

        $this->get('/promo')
            ->assertOk()
            ->assertSee('Promo & Kupon Diskon — Atelier Nusa');
    }

    public function test_legal_pages_render_fallback_and_cms_content(): void
    {
        $this->assertArrayHasKey('terms', StaticPage::slugOptions());
        $this->assertArrayHasKey('privacy', StaticPage::slugOptions());

        $this->get('/syarat-ketentuan')
            ->assertOk()
            ->assertSee('Syarat &')
            ->assertSee('Ketentuan')
            ->assertSee('Ketentuan umum')
            ->assertSee('Pengembalian dan penukaran')
            ->assertSee('Masih ada pertanyaan?');

        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertSee('Kebijakan')
            ->assertSee('Privasi')
            ->assertSee('Informasi yang kami kumpulkan')
            ->assertSee('Hak Anda')
            ->assertSee('Masih ada pertanyaan?');

        StaticPage::create([
            'slug' => 'terms',
            'title' => 'Syarat CMS',
            'content' => '<p>Konten syarat dari CMS.</p>',
            'is_active' => true,
        ]);

        $this->get('/syarat-ketentuan')
            ->assertOk()
            ->assertSee('Syarat CMS')
            ->assertSee('Konten syarat dari CMS.')
            ->assertDontSee('Ketentuan umum');
    }

    public function test_auth_pages_link_to_legal_pages(): void
    {
        $this->get('/daftar')
            ->assertOk()
            ->assertSee(route('terms'))
            ->assertSee(route('privacy'));

        $this->get('/masuk')
            ->assertOk()
            ->assertSee(route('terms'))
            ->assertSee(route('privacy'));
    }

    public function test_admin_can_save_store_identity_brand_color_and_payment_methods(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'store_name' => 'Atelier Nusa',
                'brand_color' => '#123456',
                'payment_methods' => 'BCA, QRIS SAJA',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $settings = SiteSetting::values();

        $this->assertSame('Atelier Nusa', $settings['store_name']);
        $this->assertSame('#123456', $settings['brand_color']);
        $this->assertSame('BCA, QRIS SAJA', $settings['payment_methods']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Atelier Nusa')
            ->assertSee('QRIS SAJA');
    }

    private function setSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            SiteSetting::create(['key' => $key, 'value' => $value]);
        }
    }

    private function makeProduct(): Product
    {
        $category = Category::create([
            'name' => 'Kategori Sprint Dua',
            'slug' => 'kategori-sprint-dua',
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Sprint Dua',
            'slug' => 'produk-sprint-dua',
            'description' => 'Produk untuk pengujian Sprint 2.',
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
            'comment' => 'Ulasan pengujian Sprint 2.',
            'is_visible' => $visible,
            'is_featured' => false,
            'sort_order' => 0,
        ]);
    }

    private function makeOrder(): Order
    {
        $user = User::factory()->create();

        return Order::create([
            'order_number' => 'ORD-SPRINT2-1',
            'user_id' => $user->id,
            'total_amount' => 250000,
            'shipping_cost' => 20000,
            'discount_amount' => 0,
            'grand_total' => 270000,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::UNPAID,
            'shipping_address' => 'Jl. Pengujian No. 2, Jakarta',
            'recipient_name' => 'Pembeli Uji',
            'recipient_phone' => '081234567890',
        ]);
    }
}
