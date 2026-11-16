<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display wishlist page.
     */
    public function index(): View
    {
        $wishlists = auth()->user()
            ? Wishlist::with('product.primaryImage')
                ->where('user_id', auth()->id())
                ->latest()
                ->get()
            : collect();

        return view('frontend.wishlist.index', compact('wishlists'));
    }
}