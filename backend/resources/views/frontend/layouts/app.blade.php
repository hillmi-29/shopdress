<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7c3aed">

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

    <title>@yield('title', $title ?? 'ShopDress — Busana & Dress Wanita Modern')</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? 'ShopDress menghadirkan koleksi dress, gamis, abaya, dan busana wanita modern dengan bahan premium.')">

    {{-- Plus Jakarta Sans (body) + Fraunces (display) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <span class="page-loader__monogram">SD</span>
            </div>
            <p class="page-loader__wordmark">Shop<span>Dress</span></p>
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
