@extends('frontend.layouts.app')

@section('title', \App\Models\SiteSetting::storeName().' — Busana & Dress Wanita Modern')
@section('meta_description', 'Temukan koleksi dress, gamis, abaya, dan busana wanita modern dengan bahan premium. Jelajahi koleksi '.\App\Models\SiteSetting::storeName().' untuk gaya sehari-hari hingga acara spesial.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $promoUrl = \App\Support\FrontendUrls::promo();
        $helpUrl = \App\Support\FrontendUrls::help();
        $categoryIndexUrl = \App\Support\FrontendUrls::categoryIndex();
        $productUrl = fn ($product) => \App\Support\FrontendUrls::product($product);
        $categoryUrl = fn ($category) => \App\Support\FrontendUrls::category($category);
    @endphp

    {{-- 1. Hero carousel --}}
    @include('frontend.home.hero', [
        'catalogUrl' => $catalogUrl,
        'heroBanners' => $heroBanners,
        'featuredCoupon' => $featuredCoupon,
    ])

    {{-- 2. Service highlights --}}
    @include('frontend.home.value-props')

    {{-- 3. Categories --}}
    @include('frontend.home.categories', [
        'categories' => $categories,
        'categoryIndexUrl' => $categoryIndexUrl,
        'categoryUrl' => $categoryUrl,
    ])

    {{-- 4. New arrivals / Rekomendasi untukmu --}}
    @include('frontend.home.new-arrivals', [
        'newArrivals' => $newArrivals,
        'wishlistProductIds' => $wishlistProductIds,
        'catalogUrl' => $catalogUrl,
    ])

    {{-- 5. Featured products / Koleksi Unggulan --}}
    @include('frontend.home.featured-products', [
        'featuredProducts' => $featuredProducts,
        'wishlistProductIds' => $wishlistProductIds,
        'catalogUrl' => $catalogUrl,
    ])

    {{-- 6. Customer favorites --}}
    @include('frontend.home.customer-favorites', [
        'customerFavorites' => $customerFavorites,
        'wishlistProductIds' => $wishlistProductIds,
        'catalogUrl' => $catalogUrl,
    ])

    {{-- 7. Feature tabs --}}
    @include('frontend.home.features-tabs')

    {{-- 8. Promo + coupon --}}
    @include('frontend.home.promo-banner', [
        'catalogUrl' => $catalogUrl,
        'promoBanner' => $promoBanner,
        'featuredCoupon' => $featuredCoupon,
    ])

    {{-- 9. FAQ --}}
    @include('frontend.home.faq')

    {{-- 10. Reviews --}}
    @if ($testimonials->isNotEmpty())
        @include('frontend.home.testimonials', [
            'testimonials' => $testimonials,
            'testimonialStats' => $testimonialStats,
            'productUrl' => $productUrl,
        ])
    @endif
@endsection
