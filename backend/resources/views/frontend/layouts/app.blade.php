@php
    $siteSettings = $siteSettings ?? \App\Models\SiteSetting::values();
    $storeName = $siteSettings['store_name'] ?? \App\Models\SiteSetting::storeName();
    $brandColor = $siteSettings['brand_color'] ?? \App\Models\SiteSetting::brandColor();
    $nameParts = preg_split('/(?<=[a-z])(?=[A-Z])/', $storeName, 2) ?: [$storeName];
    $brandLead = $nameParts[0];
    $brandTail = $nameParts[1] ?? '';
    $monogram = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(preg_replace('/\s+/', '', $storeName), 0, 2));
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $brandColor }}">

    {{-- Show the branded loader before first paint: on direct entry and on internal navigation. --}}
    <script>
        (function () {
            var root = document.documentElement;

            try {
                if (window.sessionStorage.getItem('shopdress:navigating') === '1') {
                    root.classList.add('sd-navigating');
                } else {
                    // Direct load, refresh, or a link opened in a new tab.
                    root.classList.add('sd-navigating', 'sd-entry');
                }
            } catch (error) {
                // Storage can be unavailable (private mode). Treat it as a fresh entry.
                root.classList.add('sd-navigating', 'sd-entry');
            }
        })();
    </script>

    <title>@yield('title', $title ?? $storeName.' — Busana & Dress Wanita Modern')</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? $storeName.' menghadirkan koleksi dress, gamis, abaya, dan busana wanita modern dengan bahan premium.')">

    {{-- Plus Jakarta Sans (body) + Fraunces (display) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand: {{ $brandColor }};
            --brand-dark: color-mix(in srgb, {{ $brandColor }} 78%, #000);
            --brand-deep: color-mix(in srgb, {{ $brandColor }} 42%, #000);
            --brand-soft: color-mix(in srgb, {{ $brandColor }} 9%, #fff);
            --brand-light: color-mix(in srgb, {{ $brandColor }} 18%, #fff);
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-[var(--bg)] font-sans text-[var(--text)] antialiased">
    {{-- Branded page transition loader --}}
    <div id="page-loader"
         class="page-loader"
         role="status"
         aria-live="polite"
         aria-label="Memuat halaman"
         aria-hidden="true">
        <div class="page-loader__inner" aria-hidden="true">
            <div class="page-loader__mark">
                <span class="page-loader__ring"></span>
                <span class="page-loader__monogram">{{ $monogram }}</span>
            </div>
            <p class="page-loader__wordmark">{{ $brandLead }}<span>{{ $brandTail }}</span></p>
            <p class="page-loader__caption">Menyiapkan halaman</p>
            <span class="page-loader__line"><span></span></span>
        </div>
    </div>

    <script>
        if (document.documentElement.classList.contains('sd-navigating')) {
            document.getElementById('page-loader').setAttribute('aria-hidden', 'false');
        }
    </script>

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[80] focus:rounded-xl focus:bg-[var(--brand)] focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white">
        Lewati ke konten utama
    </a>

    {{-- Main Sticky Navbar --}}
    @include('frontend.layouts.partials.navbar')

    {{-- Flash Toast & Actions --}}
    @include('frontend.layouts.partials.flash-toasts')
    @include('frontend.layouts.partials.customer-actions')

    {{-- Mini Cart Drawer --}}
    @include('frontend.layouts.partials.mini-cart')

    {{-- Variant picker (dipakai card produk di beranda) --}}
    @include('frontend.layouts.partials.variant-picker')

    {{-- Dialog masuk untuk aksi yang membutuhkan akun --}}
    @include('frontend.layouts.partials.auth-required-modal')

    {{-- Main Content --}}
    <main id="main-content" tabindex="-1" class="flex-1">
        <div class="mx-auto w-full max-w-7xl px-4 pb-16 pt-5 sm:px-6 sm:pt-7 lg:px-8">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>

    {{-- Floating WhatsApp / Help Button --}}
    @include('frontend.layouts.partials.floating-help')

    {{-- Footer --}}
    @include('frontend.layouts.partials.footer')

    @stack('scripts')
</body>
</html>
