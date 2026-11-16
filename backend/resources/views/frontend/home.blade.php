@extends('frontend.layouts.app')

@section('title', 'ShopDress — Busana & Dress Wanita Modern')
@section('meta_description', 'Temukan koleksi dress, gamis, abaya, dan busana wanita modern dengan bahan premium. Jelajahi koleksi ShopDress untuk gaya sehari-hari hingga acara spesial.')

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
    ])

    {{-- 2. Service highlights --}}
    @include('frontend.home.value-props')

    {{-- 3. Categories --}}
    @include('frontend.home.categories', [
        'categories' => $categories,
        'categoryIndexUrl' => $categoryIndexUrl,
        'categoryUrl' => $categoryUrl,
    ])

    {{-- 4. Recommended products --}}
    @include('frontend.home.featured-products', [
        'featuredProducts' => $featuredProducts,
        'wishlistProductIds' => $wishlistProductIds,
        'catalogUrl' => $catalogUrl,
    ])

    {{-- 5. Feature tabs --}}
    @include('frontend.home.features-tabs')

    {{-- 6. Promo + coupon --}}
    @include('frontend.home.promo-banner', [
        'catalogUrl' => $catalogUrl,
    ])

    {{-- 7. FAQ --}}
    @include('frontend.home.faq')

    {{-- 8. Reviews --}}
    @if ($testimonials->isNotEmpty())
        @include('frontend.home.testimonials', [
            'testimonials' => $testimonials,
            'productUrl' => $productUrl,
        ])
    @endif
@endsection
