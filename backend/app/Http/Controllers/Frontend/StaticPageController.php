<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    /**
     * Display the Tentang Kami page with optional CMS content.
     */
    public function about(): View
    {
        $siteSettings = SiteSetting::values();

        $customerCount = User::query()->where('role', 'customer')->count();
        $reviewCount = Review::query()->visible()->count();
        $averageRating = $reviewCount > 0
            ? round((float) Review::query()->visible()->avg('rating'), 1)
            : null;

        return view('frontend.about', [
            'page' => StaticPage::findActive('about'),
            'siteSettings' => $siteSettings,
            'featuredCoupon' => Coupon::firstActive(),
            'aboutStats' => [
                [
                    'value' => number_format($customerCount, 0, ',', '.').($customerCount > 0 ? '+' : ''),
                    'label' => 'Pelanggan bahagia',
                ],
                [
                    'value' => $averageRating !== null ? number_format($averageRating, 1, ',', '.').'/5' : '—',
                    'label' => 'Rating pelanggan',
                ],
                [
                    'value' => '100%',
                    'label' => 'Bahan dikurasi',
                ],
                [
                    'value' => $siteSettings['return_policy'] ?? SiteSetting::DEFAULTS['return_policy'],
                    'label' => 'Garansi tukar ukuran',
                ],
            ],
        ]);
    }

    /**
     * Display the Syarat & Ketentuan page with optional CMS content.
     */
    public function terms(): View
    {
        return $this->legalPage('terms', [
            'kicker' => 'Ketentuan belanja',
            'title' => 'Syarat &',
            'highlight' => 'Ketentuan',
            'subtitle' => 'Panduan singkat agar pengalaman berbelanja di toko kami tetap aman, nyaman, dan saling menguntungkan.',
            'meta_description' => 'Syarat dan ketentuan belanja, pembayaran, pengiriman, serta pengembalian produk.',
        ]);
    }

    /**
     * Display the Kebijakan Privasi page with optional CMS content.
     */
    public function privacy(): View
    {
        return $this->legalPage('privacy', [
            'kicker' => 'Perlindungan data',
            'title' => 'Kebijakan',
            'highlight' => 'Privasi',
            'subtitle' => 'Kami memahami bahwa data pribadi Anda adalah titipan. Berikut cara kami mengumpulkan, memakai, dan menjaga informasi tersebut.',
            'meta_description' => 'Kebijakan privasi tentang cara kami mengumpulkan, memakai, dan melindungi data pelanggan.',
        ]);
    }

    private function legalPage(string $slug, array $fallback): View
    {
        return view('frontend.legal', [
            'page' => StaticPage::findActive($slug),
            'slug' => $slug,
            'fallback' => $fallback,
            'siteSettings' => SiteSetting::values(),
        ]);
    }

    /**
     * Display the Bantuan page with CMS FAQs and optional CMS content.
     */
    public function help(): View
    {
        return view('frontend.help', [
            'page' => StaticPage::findActive('help'),
            'faqs' => Faq::query()
                ->active()
                ->forPage(Faq::PAGE_HELP)
                ->ordered()
                ->get(),
            'siteSettings' => SiteSetting::values(),
        ]);
    }
}
