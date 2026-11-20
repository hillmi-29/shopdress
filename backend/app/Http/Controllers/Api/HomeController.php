<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Provide homepage data: CMS content, categories, and product collections.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withActiveProductCount()
            ->orderByPopularity()
            ->get();

        $featuredProducts = Product::with(['primaryImage', 'variants'])
            ->where('is_active', true)
            ->where('featured', true)
            ->orderByPopularity()
            ->take(8)
            ->get();

        $latestProducts = Product::with(['primaryImage', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Samakan dengan storefront: favorit pelanggan tidak menduplikasi
        // produk unggulan agar setiap bagian beranda tetap terasa berbeda.
        $featuredIds = $featuredProducts->pluck('id')->all();

        $customerFavorites = Product::with(['primaryImage', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', $featuredIds)
            ->orderByPopularity()
            ->take(12)
            ->get();

        $testimonials = Review::with(['user:id,name', 'product:id,name,slug'])
            ->visible()
            ->featured()
            ->ordered()
            ->take(2)
            ->get();

        if ($testimonials->isEmpty()) {
            $testimonials = Review::with(['user:id,name', 'product:id,name,slug'])
                ->visible()
                ->where('rating', '>=', 4)
                ->latest()
                ->take(2)
                ->get();
        }

        $testimonialStats = [
            'count' => Review::visible()->count(),
            'average' => round((float) (Review::visible()->avg('rating') ?? 0), 1),
        ];

        $heroBanners = Banner::active()
            ->position(Banner::POSITION_HERO)
            ->ordered()
            ->get();

        $promoBanner = Banner::active()
            ->position(Banner::POSITION_PROMO)
            ->ordered()
            ->first();

        $homeFaqs = Faq::query()
            ->active()
            ->forPage(Faq::PAGE_HOME)
            ->ordered()
            ->get();

        $featuredCoupon = Coupon::firstActive();

        return response()->json([
            'status' => 'success',
            'data' => [
                'site_settings' => SiteSetting::values(),
                'hero_banners' => $heroBanners
                    ->map(fn (Banner $banner) => $this->bannerPayload($banner))
                    ->values(),
                'promo_banner' => $promoBanner ? $this->bannerPayload($promoBanner) : null,
                'featured_coupon' => $this->couponPayload($featuredCoupon),
                'home_faqs' => $homeFaqs
                    ->map(fn (Faq $faq) => [
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                    ])
                    ->values(),
                'testimonials' => $testimonials
                    ->map(fn (Review $review) => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'customer_name' => $review->user?->name,
                        'product_name' => $review->product?->name,
                        'product_slug' => $review->product?->slug,
                    ])
                    ->values(),
                'testimonial_stats' => $testimonialStats,
                'customer_favorites' => $customerFavorites,
                'categories' => $categories,
                'featured_products' => $featuredProducts,
                'latest_products' => $latestProducts,
            ],
        ]);
    }

    /**
     * Bentuk payload banner yang stabil untuk client mobile/SPA.
     *
     * @return array<string, mixed>
     */
    private function bannerPayload(Banner $banner): array
    {
        return [
            'id' => $banner->id,
            'position' => $banner->position,
            'eyebrow' => $banner->eyebrow,
            'title' => $banner->title,
            'highlight' => $banner->highlight,
            'subtitle' => $banner->subtitle,
            'image_url' => $banner->image_url,
            'image_alt' => $banner->image_alt,
            'caption' => $banner->caption,
            'caption_sub' => $banner->caption_sub,
            'badge' => $banner->badge,
            'badge_style' => $banner->badge_style,
            'cta_label' => $banner->cta_label,
            'cta_url' => $banner->resolvedCtaUrl(),
            'secondary_label' => $banner->secondary_label,
            'secondary_url' => $banner->resolvedSecondaryUrl(),
            'footnote' => $banner->footnoteLines(),
            'coupon_code' => $banner->coupon_code,
            'rating_text' => $banner->rating_text,
            'show_rating' => (bool) $banner->show_rating,
        ];
    }

    /**
     * Bentuk payload kupon aktif beserta label siap tampil.
     *
     * @return array<string, mixed>|null
     */
    private function couponPayload(?Coupon $coupon): ?array
    {
        if (! $coupon) {
            return null;
        }

        return [
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_amount' => $coupon->discount_amount,
            'min_spend' => $coupon->min_spend,
            'expires_at' => $coupon->expires_at?->toIso8601String(),
            'discount_label' => $coupon->discountLabel(),
            'minimum_label' => $coupon->minimumLabel(),
            'promo_title' => $coupon->promoTitle(),
            'promo_description' => $coupon->promoDescription(),
            'promo_highlight' => $coupon->promoHighlight(),
            'promo_note' => $coupon->promoNote(),
        ];
    }
}
