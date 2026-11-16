<?php

namespace App\View\Composers;

use App\Models\Cart;
use App\Models\Category;
use App\Support\FrontendUrls;
use Illuminate\View\View;

class FrontendLayoutComposer
{
    /**
     * Provide the URLs and data required by the customer layout.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();

        $cart = $user
            ? Cart::query()
                ->where('user_id', $user->id)
                ->with(['items.variant.product.primaryImage'])
                ->first()
            : Cart::query()
                ->where('session_id', session()->getId())
                ->whereNull('user_id')
                ->with(['items.variant.product.primaryImage'])
                ->first();

        $view->with([
            'homeUrl' => FrontendUrls::home(),
            'catalogUrl' => FrontendUrls::catalog(),
            'categoryIndexUrl' => FrontendUrls::categoryIndex(),
            'promoUrl' => FrontendUrls::promo(),
            'aboutUrl' => FrontendUrls::about(),
            'wishlistUrl' => FrontendUrls::wishlist(),
            'cartUrl' => FrontendUrls::cart(),
            'loginUrl' => FrontendUrls::login(),
            'registerUrl' => FrontendUrls::register(),
            'profileUrl' => FrontendUrls::profile(),
            'ordersUrl' => FrontendUrls::orders(),
            'logoutUrl' => FrontendUrls::logout(),
            'helpUrl' => FrontendUrls::help(),
            'navCategories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'wishlistCount' => $user ? $user->wishlists()->count() : 0,
            'cartCount' => $cart ? (int) $cart->items->sum('quantity') : 0,
            'cartDrawer' => $cart,
        ]);
    }
}
