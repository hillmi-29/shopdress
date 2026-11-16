<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\HomeController as ApiHomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\UserDashboardController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Webhooks\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ShopDress REST API Routes
|--------------------------------------------------------------------------
*/

// Home & Catalog
Route::get('/home', [ApiHomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/filters', [ProductController::class, 'filters']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Customer Authentication
Route::post('/auth/register', [AuthController::class, 'register'])
    ->middleware('throttle:10,1');
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1');

// Cart (Guest & Authenticated supported)
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart', [CartController::class, 'store']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::delete('/cart/{id}', [CartController::class, 'destroy']);
Route::delete('/cart', [CartController::class, 'clear']);

// Checkout Preview & Voucher validation
Route::get('/checkout/preview', [CheckoutController::class, 'preview']);
Route::post('/checkout/coupon', [CheckoutController::class, 'checkCoupon']);

// Indonesian Administrative Regions for Checkout
Route::get('/regions/provinces', [RegionController::class, 'provinces']);
Route::get('/regions/regencies', [RegionController::class, 'regencies']);
Route::get('/regions/districts', [RegionController::class, 'districts']);
Route::get('/regions/villages', [RegionController::class, 'villages']);

// Midtrans Webhook Notification
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->middleware('throttle:60,1');

// Authenticated Customer Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Order checkout process
    Route::post('/checkout', [CheckoutController::class, 'process'])
        ->middleware('throttle:5,1');

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
