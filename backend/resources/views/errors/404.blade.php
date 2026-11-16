@extends('frontend.layouts.app')

@section('title', '404 — Halaman Tidak Ditemukan | ShopDress')

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Women's Dress on Hanger with Wind Animation --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px] transition-transform duration-300">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Dress fabric gradient --}}
                                <linearGradient id="dressFabric" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2e253a" />
                                    <stop offset="50%" stop-color="#241d2e" />
                                    <stop offset="100%" stop-color="#181320" />
                                </linearGradient>

                                {{-- Wooden hanger gradient --}}
                                <linearGradient id="hangerWood" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#caa072" />
                                    <stop offset="50%" stop-color="#dfb78c" />
                                    <stop offset="100%" stop-color="#ba8e60" />
                                </linearGradient>
                            </defs>

                            {{-- Entire assembly sways softly like a pendulum from the top string --}}
                            <g class="sd-hanger-swing">
                                <!-- Suspension Wire from Ceiling -->
                                <line x1="160" y1="0" x2="160" y2="46" stroke="#9a92a2" stroke-width="1.5" stroke-dasharray="3 2" />

                                <!-- Silver Hook -->
                                <g stroke="#9f99a8" stroke-width="2.2" stroke-linecap="round" fill="none">
                                    <path d="M 160 46 C 160 38 167 34 170 39 C 173 44 168 49 160 52 L 160 68" />
                                    <ellipse cx="160" cy="68" rx="3.5" ry="1.8" fill="#756f7e" stroke="#5a5462" stroke-width="1" />
                                </g>

                                <!-- Wooden Coat Hanger -->
                                <g filter="drop-shadow(0 3px 5px rgba(0,0,0,0.15))">
                                    <path d="M 160 69 L 88 101 C 84 103 86 107 90 106 L 160 90 L 230 106 C 234 107 236 103 232 101 Z"
                                          fill="url(#hangerWood)"
                                          stroke="#8e6035"
                                          stroke-width="1.4"
                                          stroke-linejoin="round" />
                                    <!-- Wood Highlight -->
                                    <path d="M 100 100 L 155 88" stroke="#f1dbbf" stroke-width="0.9" stroke-linecap="round" opacity="0.6" />
                                </g>

                                <!-- Upper Bodice & Straps (Connected to Hanger) -->
                                <g filter="drop-shadow(0 8px 14px rgba(36,29,46,0.12))">
                                    <!-- Shoulder Straps -->
                                    <line x1="120" y1="94" x2="124" y2="114" stroke="#241d2e" stroke-width="2.5" stroke-linecap="round" />
                                    <line x1="200" y1="94" x2="196" y2="114" stroke="#241d2e" stroke-width="2.5" stroke-linecap="round" />

                                    <!-- Bodice -->
                                    <path d="
                                        M 124 114
                                        C 134 109 148 119 160 117
                                        C 172 119 186 109 196 114
                                        Q 198 138 186 162
                                        L 134 162
                                        Q 122 138 124 114
                                        Z"
                                        fill="url(#dressFabric)"
                                        stroke="#16111d"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Sweetheart Neckline Trim -->
                                    <path d="M 124 114 C 134 109 148 119 160 117 C 172 119 186 109 196 114" stroke="#3f344e" stroke-width="1.2" fill="none" />

                                    <!-- Waistband Ribbon -->
                                    <path d="M 134 162 Q 160 166 186 162" stroke="#7c3aed" stroke-width="2.4" stroke-linecap="round" opacity="0.85" />
                                </g>

                                <!-- Flared Skirt with Organic Wind Flutter / Cloth Ripple Effect -->
                                <g class="sd-skirt-flutter" filter="drop-shadow(0 12px 18px rgba(36,29,46,0.18))">
                                    <!-- Skirt Fabric -->
                                    <path d="
                                        M 134 162
                                        L 186 162
                                        Q 214 220 236 288
                                        Q 160 298 84 288
                                        Q 106 220 134 162
                                        Z"
                                        fill="url(#dressFabric)"
                                        stroke="#16111d"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Soft Skirt Drape Lines -->
                                    <path d="M 142 165 Q 132 225 116 289" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 160 166 Q 160 228 160 295" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 178 165 Q 188 225 204 289" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />

                                    <!-- Bottom Hem Scallop Wave -->
                                    <path d="M 84 288 Q 160 298 236 288" stroke="#16111d" stroke-width="1.2" stroke-linecap="round" />

                                    <!-- "404" Printed on the Flowy Skirt -->
                                    <text x="160" y="234"
                                          text-anchor="middle"
                                          font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                          font-size="44"
                                          font-weight="800"
                                          letter-spacing="-1"
                                          fill="#c4b5fd"
                                          style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.35));">
                                        404
                                    </text>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 404 + PAGE NOT FOUND + 3 Buttons (No Scroll, Compact) --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 404 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        404
                    </h1>

                    {{-- PAGE NOT FOUND --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        PAGE NOT FOUND
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>We are sorry. But the page you are looking for is not available.</p>
                        <p>Here are some helpful links instead:</p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Home Page --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            HOME PAGE
                        </a>

                        {{-- 2. Products / Style Catalog --}}
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            KATALOG PRODUK
                        </a>

                        {{-- 3. Promo & Diskon --}}
                        <a href="{{ route('promo') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            PROMO SPESIAL
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-404" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-404"
                                       name="search"
                                       placeholder="Atau cari dress, gamis favoritmu..."
                                       autocomplete="off"
                                       class="h-10 w-full rounded-lg border border-[var(--border)] bg-white pl-3.5 pr-20 text-xs font-medium text-[var(--text)] placeholder:text-[var(--text-subtle)] shadow-xs transition-all duration-200 hover:border-[#d9cec1] focus:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand-soft)]" />
                                <button type="submit"
                                        class="absolute right-1.5 h-7.5 rounded-md bg-[var(--brand)] px-3 text-xs font-bold text-white transition-all duration-200 hover:bg-[var(--brand-dark)] focus:outline-none">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Ayunan gantungan (pendulum) halus dari titik gantung tali atas */
    @keyframes sdHangerSwing {
        0% {
            transform: rotate(-2.2deg);
        }
        50% {
            transform: rotate(2.8deg);
        }
        100% {
            transform: rotate(-2.2deg);
        }
    }

    /* Kibasan kain rok mekar terkena hembusan angin organik */
    @keyframes sdSkirtFlutter {
        0% {
            transform: rotate(-1.5deg) skewX(-2deg);
        }
        25% {
            transform: rotate(3.5deg) skewX(3deg) scaleY(1.01);
        }
        60% {
            transform: rotate(-3deg) skewX(-2.5deg);
        }
        85% {
            transform: rotate(1.8deg) skewX(1.5deg);
        }
        100% {
            transform: rotate(-1.5deg) skewX(-2deg);
        }
    }

    .sd-hanger-swing {
        transform-origin: 160px 0px;
        animation: sdHangerSwing 4.8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        will-change: transform;
    }

    .sd-skirt-flutter {
        transform-origin: 160px 162px;
        animation: sdSkirtFlutter 3.4s ease-in-out infinite;
        will-change: transform;
    }

    @media (prefers-reduced-motion: reduce) {
        .sd-hanger-swing,
        .sd-skirt-flutter {
            animation: none !important;
        }
    }
</style>
@endpush
