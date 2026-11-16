<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'homepage'])->name('home');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::view('/tentang-kami', 'frontend.about')->name('about');
Route::view('/promo', 'frontend.promo')->name('promo');
Route::view('/bantuan', 'frontend.help')->name('help');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

Route::get('/masuk', [HomeController::class, 'login'])->name('login');
Route::post('/masuk', [HomeController::class, 'loginPost'])->name('login.post');
Route::get('/daftar', [HomeController::class, 'register'])->name('register');
Route::post('/daftar', [HomeController::class, 'registerPost'])->name('register.post');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [HomeController::class, 'profile'])->name('profile.edit');
    Route::get('/pesanan', [HomeController::class, 'orders'])->name('orders.index');
    Route::get('/pesanan/{id}', [HomeController::class, 'orderDetail'])->name('orders.show');
    Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});
