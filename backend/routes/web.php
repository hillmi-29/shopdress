<?php

use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\PromoController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\StaticPageController;
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

Route::get('/tentang-kami', [StaticPageController::class, 'about'])->name('about');
Route::get('/promo', [PromoController::class, 'index'])->name('promo');
Route::get('/bantuan', [StaticPageController::class, 'help'])->name('help');
Route::get('/syarat-ketentuan', [StaticPageController::class, 'terms'])->name('terms');
Route::get('/kebijakan-privasi', [StaticPageController::class, 'privacy'])->name('privacy');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

Route::get('/masuk', [HomeController::class, 'login'])->name('login');
Route::post('/masuk', [HomeController::class, 'loginPost'])->name('login.post');
Route::get('/daftar', [HomeController::class, 'register'])->name('register');
Route::post('/daftar', [HomeController::class, 'registerPost'])->name('register.post');

Route::middleware('auth')->group(function () {
    Route::post('/produk/{product:slug}/ulasan', [ReviewController::class, 'store'])->name('products.reviews.store');

    Route::get('/profil', [HomeController::class, 'profile'])->name('profile.edit');
    Route::post('/profil/alamat', [AddressController::class, 'store'])->name('profile.addresses.store');
    Route::put('/profil/alamat/{address}', [AddressController::class, 'update'])->name('profile.addresses.update');
    Route::delete('/profil/alamat/{address}', [AddressController::class, 'destroy'])->name('profile.addresses.destroy');
    Route::post('/profil/alamat/{address}/default', [AddressController::class, 'setDefault'])->name('profile.addresses.default');
    Route::get('/pesanan', [HomeController::class, 'orders'])->name('orders.index');
    Route::get('/pesanan/{id}', [HomeController::class, 'orderDetail'])->name('orders.show');
    Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

