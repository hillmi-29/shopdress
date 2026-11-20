<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Coupon;
use Illuminate\View\View;

class PromoController extends Controller
{
    /**
     * Display active coupons managed from the admin panel.
     */
    public function index(): View
    {
        $coupons = Coupon::query()
            ->active()
            ->orderBy('id')
            ->get();

        return view('frontend.promo', [
            'coupons' => $coupons,
            'featuredCoupon' => $coupons->first(),
            'promoBanner' => Banner::active()
                ->position(Banner::POSITION_PROMO)
                ->ordered()
                ->first(),
        ]);
    }
}
