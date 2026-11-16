<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    /**
     * Display cart page.
     */
    public function index(): View
    {
        $cart = $this->cartService->getCart(auth()->user());

        return view('frontend.cart.index', compact('cart'));
    }
}