@extends('frontend.layouts.app')

@section('title', '403 — Akses Ditolak | '.\App\Models\SiteSetting::storeName())

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Locked Dress on Hanger --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px] transition-transform duration-300">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Premium dress fabric gradient --}}
                                <linearGradient id="lockedFabric" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2e253a" />
                                    <stop offset="50%" stop-color="#241d2e" />
                                    <stop offset="100%" stop-color="#181320" />
                                </linearGradient>

                                {{-- Gold hanger gradient --}}
                                <linearGradient id="goldHanger" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#f5d78a" />
                                    <stop offset="50%" stop-color="#e8c46a" />
                                    <stop offset="100%" stop-color="#c9a44a" />
                                </linearGradient>

                                {{-- Gold lock gradient --}}
                                <linearGradient id="goldLock" x1="140" y1="250" x2="180" y2="290" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ffe066" />
                                    <stop offset="50%" stop-color="#f5c842" />
                                    <stop offset="100%" stop-color="#d4a82a" />
                                </linearGradient>

                                {{-- Lock shackle gradient --}}
                                <linearGradient id="shackleGradient" x1="150" y1="250" x2="170" y2="270" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#e8d080" />
                                    <stop offset="50%" stop-color="#c9a850" />
                                    <stop offset="100%" stop-color="#9a7a30" />
                                </linearGradient>

                                {{-- Sparkle gradient --}}
                                <radialGradient id="sparkleGradient" cx="0" cy="0" r="4" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#fff" stop-opacity="1" />
                                    <stop offset="70%" stop-color="#ffe066" stop-opacity="0.8" />
                                    <stop offset="100%" stop-color="#f5c842" stop-opacity="0" />
                                </radialGradient>
                            </defs>

                            <g class="sd-locked-assembly">
                                {{-- Sparkles around lock --}}
                                <g class="sd-sparkles">
                                    <circle cx="135" cy="255" r="2.5" fill="url(#sparkleGradient)" class="sd-sparkle-1" />
                                    <circle cx="185" cy="250" r="2" fill="url(#sparkleGradient)" class="sd-sparkle-2" />
                                    <circle cx="140" cy="285" r="1.5" fill="url(#sparkleGradient)" class="sd-sparkle-3" />
                                    <circle cx="180" cy="290" r="2" fill="url(#sparkleGradient)" class="sd-sparkle-4" />
                                    <circle cx="125" cy="270" r="1.5" fill="url(#sparkleGradient)" class="sd-sparkle-5" />
                                    <circle cx="195" cy="265" r="2" fill="url(#sparkleGradient)" class="sd-sparkle-6" />
                                </g>

                                {{-- Suspension Wire from Ceiling --}}
                                <line x1="160" y1="0" x2="160" y2="46" stroke="#7a6e88" stroke-width="1.5" stroke-dasharray="3 2" />

                                {{-- Gold Hook --}}
                                <g stroke="#c9a850" stroke-width="2.2" stroke-linecap="round" fill="none">
                                    <path d="M 160 46 C 160 38 167 34 170 39 C 173 44 168 49 160 52 L 160 68" />
                                    <ellipse cx="160" cy="68" rx="3.5" ry="1.8" fill="#b89640" stroke="#9a7a30" stroke-width="1" />
                                </g>

                                {{-- Gold Coat Hanger --}}
                                <g filter="drop-shadow(0 3px 8px rgba(201,168,74,0.3))">
                                    <path d="M 160 69 L 88 101 C 84 103 86 107 90 106 L 160 90 L 230 106 C 234 107 236 103 232 101 Z"
                                          fill="url(#goldHanger)"
                                          stroke="#9a7a30"
                                          stroke-width="1.4"
                                          stroke-linejoin="round" />
                                    <!-- Gold Highlight -->
                                    <path d="M 100 100 L 155 88" stroke="#fff8e0" stroke-width="0.9" stroke-linecap="round" opacity="0.7" />
                                </g>

                                {{-- Premium Bodice & Straps --}}
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
                                        fill="url(#lockedFabric)"
                                        stroke="#16111d"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Sweetheart Neckline Trim -->
                                    <path d="M 124 114 C 134 109 148 119 160 117 C 172 119 186 109 196 114" stroke="#3f344e" stroke-width="1.2" fill="none" />

                                    <!-- Gold Waistband Ribbon -->
                                    <path d="M 134 162 Q 160 166 186 162" stroke="url(#goldLock)" stroke-width="2.8" stroke-linecap="round" opacity="0.9" />
                                </g>

                                {{-- Flared Skirt with Gold Lock --}}
                                <g class="sd-locked-skirt" filter="drop-shadow(0 12px 18px rgba(36,16,35,0.15))">
                                    <!-- Skirt Fabric -->
                                    <path d="
                                        M 134 162
                                        L 186 162
                                        Q 214 220 236 288
                                        Q 160 298 84 288
                                        Q 106 220 134 162
                                        Z"
                                        fill="url(#lockedFabric)"
                                        stroke="#16111d"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Soft Skirt Drape Lines -->
                                    <path d="M 142 165 Q 132 225 116 289" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 160 166 Q 160 228 160 295" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />
                                    <path d="M 178 165 Q 188 225 204 289" stroke="#3b3248" stroke-width="1.1" stroke-linecap="round" opacity="0.6" />

                                    <!-- Bottom Hem -->
                                    <path d="M 84 288 Q 160 298 236 288" stroke="#16111d" stroke-width="1.2" stroke-linecap="round" />

                                    <!-- GOLD PADLOCK on skirt center -->
                                    <g class="sd-padlock" filter="drop-shadow(0 4px 12px rgba(201,168,74,0.4))">
                                        <!-- Lock body -->
                                        <rect x="140" y="250" width="40" height="30" rx="5" fill="url(#goldLock)" stroke="#9a7a30" stroke-width="1.5" />
                                        
                                        <!-- Lock shackle -->
                                        <path d="M 152 250 
                                                   C 152 235 168 235 168 250
                                                   M 168 250 L 168 240
                                                   M 152 250 L 152 240"
                                              stroke="url(#shackleGradient)" 
                                              stroke-width="3.5" 
                                              stroke-linecap="round" 
                                              fill="none" />
                                        
                                        <!-- Keyhole -->
                                        <circle cx="160" cy="268" r="4" fill="#1a1420" stroke="#3d2a10" stroke-width="0.8" />
                                        <rect x="158" y="268" width="4" height="8" fill="#1a1420" />
                                        
                                        <!-- "403" on lock body -->
                                        <text x="160" y="267"
                                              text-anchor="middle"
                                              font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                              font-size="16"
                                              font-weight="800"
                                              letter-spacing="-0.5"
                                              fill="#1a1420"
                                              style="filter: drop-shadow(0 1px 2px rgba(255,224,102,0.3));">
                                            403
                                        </text>
                                    </g>

                                    <!-- Chain from lock to waistband -->
                                    <g stroke="#c9a44a" stroke-width="1.8" stroke-linecap="round" fill="none" opacity="0.7">
                                        <path d="M 160 162 Q 160 190 160 225" class="sd-lock-chain" />
                                        <path d="M 155 165 Q 155 195 152 225" stroke-width="1.2" opacity="0.5" />
                                        <path d="M 165 165 Q 165 195 168 225" stroke-width="1.2" opacity="0.5" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 403 + Forbidden + Actions --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 403 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        403
                    </h1>

                    {{-- FORBIDDEN --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        AKSES DITOLAK
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>Area ini dikunci untuk keamanan.</p>
                        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Home Page --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BERANDA
                        </a>

                        {{-- 2. Login --}}
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            MASUK
                        </a>

                        {{-- 3. Register --}}
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            DAFTAR
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-403" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-403"
                                       name="search"
                                       placeholder="Jelajahi katalog yang tersedia..."
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
    /* Locked assembly subtle sway */
    @keyframes sdLockedSway {
        0% { transform: rotate(-1deg) translateX(-0.5px); }
        50% { transform: rotate(1.5deg) translateX(0.5px); }
        100% { transform: rotate(-1deg) translateX(-0.5px); }
    }

    /* Padlock pulse/blink */
    @keyframes sdPadlockPulse {
        0% { transform: scale(1) rotate(0deg); filter: drop-shadow(0 4px 12px rgba(201,168,74,0.4)); }
        50% { transform: scale(1.03) rotate(1deg); filter: drop-shadow(0 6px 16px rgba(245,200,66,0.6)); }
        100% { transform: scale(1) rotate(0deg); filter: drop-shadow(0 4px 12px rgba(201,168,74,0.4)); }
    }

    /* Chain subtle sway */
    @keyframes sdChainSway {
        0% { transform: translateX(-0.5px); }
        50% { transform: translateX(0.5px); }
        100% { transform: translateX(-0.5px); }
    }

    /* Sparkle twinkle */
    @keyframes sdSparkleTwinkle {
        0% { transform: scale(1) rotate(0deg); opacity: 1; }
        50% { transform: scale(1.5) rotate(180deg); opacity: 0.4; }
        100% { transform: scale(1) rotate(360deg); opacity: 1; }
    }

    .sd-locked-assembly {
        transform-origin: 160px 0px;
        animation: sdLockedSway 5s ease-in-out infinite;
        will-change: transform;
    }

    .sd-padlock {
        transform-origin: 160px 265px;
        animation: sdPadlockPulse 2.5s ease-in-out infinite;
        will-change: transform, filter;
    }

    .sd-lock-chain {
        transform-origin: 160px 162px;
        animation: sdChainSway 3s ease-in-out infinite;
        will-change: transform;
    }

    .sd-sparkles circle {
        transform-origin: center center;
        animation: sdSparkleTwinkle 1.5s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-sparkle-1 { animation-delay: 0s; }
    .sd-sparkle-2 { animation-delay: 0.2s; }
    .sd-sparkle-3 { animation-delay: 0.4s; }
    .sd-sparkle-4 { animation-delay: 0.6s; }
    .sd-sparkle-5 { animation-delay: 0.8s; }
    .sd-sparkle-6 { animation-delay: 1s; }

    @media (prefers-reduced-motion: reduce) {
        .sd-locked-assembly,
        .sd-padlock,
        .sd-lock-chain,
        .sd-sparkles circle {
            animation: none !important;
        }
    }
</style>
@endpush