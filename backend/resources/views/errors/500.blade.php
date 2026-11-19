@extends('frontend.layouts.app')

@section('title', '500 — Kesalahan Server | '.\App\Models\SiteSetting::storeName())

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Burnt/Torn Dress with Fire Animation --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px]">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Burnt dress fabric gradient --}}
                                <linearGradient id="burntFabric" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#1a1420" />
                                    <stop offset="40%" stop-color="#2d1b1b" />
                                    <stop offset="70%" stop-color="#3d1a1a" />
                                    <stop offset="100%" stop-color="#181010" />
                                </linearGradient>

                                {{-- Charred hanger gradient --}}
                                <linearGradient id="charredHanger" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2d2520" />
                                    <stop offset="50%" stop-color "#1a1410" />
                                    <stop offset="100%" stop-color="#0d0806" />
                                </linearGradient>

                                {{-- Fire gradient --}}
                                <linearGradient id="fireGradient" x1="160" y1="180" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ff6b00" stop-opacity="0.9" />
                                    <stop offset="40%" stop-color="#ff3d00" stop-opacity="0.7" />
                                    <stop offset="70%" stop-color="#ff1a00" stop-opacity="0.5" />
                                    <stop offset="100%" stop-color="#8b0000" stop-opacity="0.2" />
                                </linearGradient>

                                {{-- Smoke gradient --}}
                <radialGradient id="smokeGradient" cx="160" cy="160" r="80" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#3a3038" stop-opacity="0.4" />
                                    <stop offset="50%" stop-color="#241d2e" stop-opacity="0.2" />
                                    <stop offset="100%" stop-color="#181320" stop-opacity="0" />
                                </radialGradient>

                                {{-- Ember particles --}}
                                <radialGradient id="emberGradient" cx="0" cy="0" r="4" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ffcc00" />
                                    <stop offset="50%" stop-color="#ff6600" />
                                    <stop offset="100%" stop-color="#cc3300" stop-opacity="0" />
                                </radialGradient>
                            </defs>

                            <g class="sd-burnt-assembly">
                                {{-- Smoke rising --}}
                                <g class="sd-smoke-rise" filter="drop-shadow(0 2px 8px rgba(0,0,0,0.3))">
                                    <ellipse cx="160" cy="170" rx="40" ry="25" fill="url(#smokeGradient)" />
                                    <ellipse cx="140" cy="150" rx="30" ry="20" fill="url(#smokeGradient)" opacity="0.6" />
                                    <ellipse cx="180" cy="155" rx="25" ry="18" fill="url(#smokeGradient)" opacity="0.5" />
                                    <ellipse cx="160" cy="130" rx="35" ry="22" fill="url(#smokeGradient)" opacity="0.4" />
                                </g>

                                {{-- Floating embers --}}
                                <g class="sd-embers">
                                    <circle cx="130" cy="190" r="3" fill="url(#emberGradient)" class="sd-ember-1" />
                                    <circle cx="190" cy="200" r="2.5" fill="url(#emberGradient)" class="sd-ember-2" />
                                    <circle cx="145" cy="220" r="2" fill="url(#emberGradient)" class="sd-ember-3" />
                                    <circle cx="175" cy="230" r="3" fill="url(#emberGradient)" class="sd-ember-4" />
                                    <circle cx="120" cy="210" r="2" fill="url(#emberGradient)" class="sd-ember-5" />
                                    <circle cx="200" cy="185" r="2.5" fill="url(#emberGradient)" class="sd-ember-6" />
                                </g>

                                {{-- Suspension Wire --}}
                                <line x1="160" y1="0" x2="160" y2="46" stroke="#5a4e58" stroke-width="1.5" stroke-dasharray="3 2" />

                                {{-- Charred Hook --}}
                                <g stroke="#4a424e" stroke-width="2.2" stroke-linecap="round" fill="none">
                                    <path d="M 160 46 C 160 38 167 34 170 39 C 173 44 168 49 160 52 L 160 68" />
                                    <ellipse cx="160" cy="68" rx="3.5" ry="1.8" fill="#3a323e" stroke="#2a242e" stroke-width="1" />
                                </g>

                                {{-- Charred Coat Hanger --}}
                                <g filter="drop-shadow(0 3px 5px rgba(0,0,0,0.4))">
                                    <path d="M 160 69 L 88 101 C 84 103 86 107 90 106 L 160 90 L 230 106 C 234 107 236 103 232 101 Z"
                                          fill="url(#charredHanger)"
                                          stroke="#1a1410"
                                          stroke-width="1.4"
                                          stroke-linejoin="round" />
                                </g>

                                {{-- Burnt Bodice --}}
                                <g filter="drop-shadow(0 8px 14px rgba(0,0,0,0.3))">
                                    {{-- Shoulder Straps (burnt) --}}
                                    <line x1="120" y1="94" x2="124" y2="114" stroke="#1a1420" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="4 2" />
                                    <line x1="200" y1="94" x2="196" y2="114" stroke="#1a1420" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="4 2" />

                                    {{-- Burnt Bodice --}}
                                    <path d="
                                        M 124 114
                                        C 134 109 148 119 160 117
                                        C 172 119 186 109 196 114
                                        Q 198 138 186 162
                                        L 134 162
                                        Q 122 138 124 114
                                        Z"
                                        fill="url(#burntFabric)"
                                        stroke="#0d0808"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    {{-- Burnt edges --}}
                                    <path d="M 124 114 C 134 109 148 119 160 117 C 172 119 186 109 196 114" 
                                          stroke="#4a2a2a" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-dasharray="3 3" />

                                    {{-- Burnt waistband --}}
                                    <path d="M 134 162 Q 160 166 186 162" stroke="#662222" stroke-width="2.4" stroke-linecap="round" opacity="0.6" />
                                </g>

                                {{-- Burnt & Torn Skirt with Fire --}}
                                <g class="sd-burnt-skirt" filter="drop-shadow(0 12px 24px rgba(61,26,26,0.4))">
                                    {{-- Fire at bottom hem --}}
                                    <g class="sd-fire-flames">
                                        <path d="M 95 288 Q 110 310 125 295 Q 140 320 155 300 Q 170 325 185 302 Q 200 315 215 298 Q 230 305 225 288"
                                              fill="url(#fireGradient)" class="sd-flame-main" />
                                        <path d="M 105 290 Q 120 315 135 298 Q 150 325 165 305 Q 180 328 195 308 Q 210 318 215 292"
                                              fill="#ff3d00" opacity="0.5" class="sd-flame-inner" />
                                    </g>

                                    {{-- Burnt skirt fabric --}}
                                    <path d="
                                        M 134 162
                                        L 186 162
                                        Q 214 220 236 288
                                        Q 160 298 84 288
                                        Q 106 220 134 162
                                        Z"
                                        fill="url(#burntFabric)"
                                        stroke="#0d0808"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    {{-- Torn holes --}}
                                    <ellipse cx="150" cy="200" rx="12" ry="8" fill="#0d0808" stroke="#1a1010" stroke-width="1" />
                                    <ellipse cx="170" cy="230" rx="8" ry="14" fill="#0d0808" stroke="#1a1010" stroke-width="1" />
                                    <ellipse cx="135" cy="240" rx="10" ry="6" fill="#0d0808" stroke="#1a1010" stroke-width="1" />
                                    <ellipse cx="185" cy="190" rx="6" ry="10" fill="#0d0808" stroke="#1a1010" stroke-width="1" />

                                    {{-- Burnt drape lines --}}
                                    <path d="M 142 165 Q 132 225 116 289" stroke="#3d1a1a" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 160 166 Q 160 228 160 295" stroke="#3d1a1a" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 178 165 Q 188 225 204 289" stroke="#3d1a1a" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />

                                    {{-- "500" Burnt into fabric --}}
                                    <text x="160" y="225"
                                          text-anchor="middle"
                                          font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                          font-size="42"
                                          font-weight="800"
                                          letter-spacing="-1"
                                          fill="#8b0000"
                                          style="filter: drop-shadow(0 2px 4px rgba(139,0,0,0.5));">
                                        500
                                    </text>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 500 + Server Error + Actions --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 500 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        500
                    </h1>

                    {{-- SERVER ERROR --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        KESALAHAN SERVER
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>Maaf, server kami sedang tidak fit.</p>
                        <p>Tim teknis sudah otomatis diberitahu. Coba lagi sebentar.</p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Home Page --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BERANDA
                        </a>

                        {{-- 2. Refresh Page --}}
                        <button onclick="window.location.reload()"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            MUAT ULANG
                        </button>

                        {{-- 3. Contact Support --}}
                        <a href="{{ route('help') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            HUBUNGI KAMI
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-500" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-500"
                                       name="search"
                                       placeholder="Cari dress, gamis favoritmu..."
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
    /* Burnt assembly subtle sway */
    @keyframes sdBurntSway {
        0% { transform: rotate(-0.8deg) translateX(-1px); }
        50% { transform: rotate(1.2deg) translateX(1px); }
        100% { transform: rotate(-0.8deg) translateX(-1px); }
    }

    /* Smoke rising */
    @keyframes sdSmokeRise {
        0% { transform: translateY(0) scale(1); opacity: 0.4; }
        50% { transform: translateY(-15px) scale(1.15); opacity: 0.2; }
        100% { transform: translateY(-30px) scale(1.3); opacity: 0; }
    }

    /* Fire flicker */
    @keyframes sdFireFlicker {
        0% { transform: scaleY(1) skewX(0deg); opacity: 0.9; }
        15% { transform: scaleY(1.08) skewX(2deg); opacity: 0.7; }
        35% { transform: scaleY(0.92) skewX(-1.5deg); opacity: 0.85; }
        55% { transform: scaleY(1.05) skewX(1deg); opacity: 0.6; }
        75% { transform: scaleY(0.95) skewX(-2deg); opacity: 0.75; }
        100% { transform: scaleY(1) skewX(0deg); opacity: 0.9; }
    }

    /* Inner flame faster */
    @keyframes sdFlameInner {
        0% { transform: scaleY(1) translateY(0); opacity: 0.5; }
        50% { transform: scaleY(1.15) translateY(-5px); opacity: 0.3; }
        100% { transform: scaleY(1) translateY(0); opacity: 0.5; }
    }

    /* Ember float */
    @keyframes sdEmberFloat {
        0% { transform: translateY(0) translateX(0); opacity: 1; }
        50% { transform: translateY(-25px) translateX(8px); opacity: 0.7; }
        100% { transform: translateY(-50px) translateX(-5px); opacity: 0; }
    }

    .sd-burnt-assembly {
        transform-origin: 160px 0px;
        animation: sdBurntSway 6s ease-in-out infinite;
        will-change: transform;
    }

    .sd-smoke-rise {
        transform-origin: 160px 170px;
        animation: sdSmokeRise 4s ease-out infinite;
        will-change: transform, opacity;
    }

    .sd-smoke-rise ellipse:nth-child(2) { animation-delay: 0.5s; }
    .sd-smoke-rise ellipse:nth-child(3) { animation-delay: 1s; }
    .sd-smoke-rise ellipse:nth-child(4) { animation-delay: 1.5s; }

    .sd-flame-main {
        transform-origin: 160px 288px;
        animation: sdFireFlicker 0.4s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-flame-inner {
        transform-origin: 160px 290px;
        animation: sdFlameInner 0.3s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-embers circle {
        transform-origin: center center;
        animation: sdEmberFloat 3s ease-out infinite;
        will-change: transform, opacity;
    }

    .sd-ember-1 { animation-delay: 0s; }
    .sd-ember-2 { animation-delay: 0.5s; }
    .sd-ember-3 { animation-delay: 1s; }
    .sd-ember-4 { animation-delay: 1.5s; }
    .sd-ember-5 { animation-delay: 2s; }
    .sd-ember-6 { animation-delay: 2.5s; }

    @media (prefers-reduced-motion: reduce) {
        .sd-burnt-assembly,
        .sd-smoke-rise,
        .sd-flame-main,
        .sd-flame-inner,
        .sd-embers circle {
            animation: none !important;
        }
    }
</style>
@endpush