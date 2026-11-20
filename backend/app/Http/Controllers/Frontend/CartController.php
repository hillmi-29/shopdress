<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\BuyNowService;
use App\Services\CartService;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected BuyNowService $buyNowService,
    ) {}

    /**
     * Display cart page.
     *
     * Opening the cart means the customer is shopping the cart again, so any
     * pending Buy Now draft is discarded to avoid mixing both flows.
     */
    public function index(): View
    {
        $this->buyNowService->forget();

        $cart = $this->cartService->getCart(auth()->user());

        return view('frontend.cart.index', [
            'cart' => $cart,
            'featuredCoupon' => Coupon::firstActive(),
        ]);
    }
}
