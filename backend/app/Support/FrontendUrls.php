<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

class FrontendUrls
{
    public static function home(): string
    {
        return self::route('home', '/');
    }

    public static function catalog(): string
    {
        return self::route('products.index', '/produk');
    }

    public static function categoryIndex(): string
    {
        return self::route('categories.index', '/kategori');
    }

    public static function category(Category $category): string
    {
        return self::route('categories.show', '/kategori/'.$category->slug, ['slug' => $category->slug]);
    }

    public static function product(Product $product): string
    {
        return self::route('products.show', '/produk/'.$product->slug, ['slug' => $product->slug]);
    }

    public static function promo(): string
    {
        return self::route('promo', '/promo');
    }

    public static function about(): string
    {
        return self::route('about', '/tentang-kami');
    }

    public static function help(): string
    {
        return self::route('help', '/bantuan');
    }

    public static function terms(): string
    {
        return self::route('terms', '/syarat-ketentuan');
    }

    public static function privacy(): string
    {
        return self::route('privacy', '/kebijakan-privasi');
    }

    public static function wishlist(): string
    {
        return self::route('wishlist.index', '/wishlist');
    }

    public static function cart(): string
    {
        return self::route('cart.index', '/keranjang');
    }

    public static function login(): string
    {
        return self::route('login', '/masuk');
    }

    public static function register(): string
    {
        return self::route('register', '/daftar');
    }

    public static function profile(): string
    {
        return self::route('profile.edit', '/profil');
    }

    public static function orders(): string
    {
        return self::route('orders.index', '/pesanan');
    }

    public static function logout(): string
    {
        return self::route('logout', '/logout');
    }

    private static function route(string $name, string $fallback, array|object $parameters = []): string
    {
        return Route::has($name)
            ? route($name, is_array($parameters) ? $parameters : [$parameters])
            : url($fallback);
    }
}
