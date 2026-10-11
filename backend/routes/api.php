<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ShopDress REST API Routes
|--------------------------------------------------------------------------
*/

// Home & Catalog
Route::get('/home', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/filters', [ProductController::class, 'filters']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Customer Authentication
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Cart (Guest & Authenticated supported)
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart', [CartController::class, 'store']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::delete('/cart/{id}', [CartController::class, 'destroy']);
Route::delete('/cart', [CartController::class, 'clear']);

// Checkout Preview & Voucher validation
Route::get('/checkout/preview', [CheckoutController::class, 'preview']);
Route::post('/checkout/coupon', [CheckoutController::class, 'checkCoupon']);

// Midtrans Webhook Notification
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);

// Authenticated Customer Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Order checkout process
    Route::post('/checkout', [CheckoutController::class, 'process']);

    // User Dashboard & Order History
    Route::get('/user/profile', [UserDashboardController::class, 'profile']);
    Route::put('/user/profile', [UserDashboardController::class, 'updateProfile']);
    Route::get('/user/orders', [UserDashboardController::class, 'orders']);
    Route::get('/user/orders/{id}', [UserDashboardController::class, 'orderDetail']);
    Route::post('/user/reviews', [UserDashboardController::class, 'submitReview']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
});
