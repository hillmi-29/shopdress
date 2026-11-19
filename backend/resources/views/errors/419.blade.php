@extends('frontend.layouts.app')

@section('title', '419 — Sesi Berakhir | '.\App\Models\SiteSetting::storeName())

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Dress in Warehouse with Hourglass --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px]">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Warehouse dress fabric gradient (muted) --}}
                                <linearGradient id="warehouseFabric" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#3a3048" />
                                    <stop offset="50%" stop-color="#2d2438" />
                                    <stop offset="100%" stop-color="#1e1828" />
                                </linearGradient>

                                {{-- Steel hanger gradient --}}
                                <linearGradient id="steelHanger" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#a8a0b8" />
                                    <stop offset="50%" stop-color="#888098" />
                                    <stop offset="100%" stop-color="#6a607a" />
                                </linearGradient>

                                {{-- Glass hourglass gradient --}}
                                <linearGradient id="glassGradient" x1="150" y1="180" x2="170" y2="280" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#e8e0f0" stop-opacity="0.3" />
                                    <stop offset="50%" stop-color="#c8b8e0" stop-opacity="0.15" />
                                    <stop offset="100%" stop-color="#a898c8" stop-opacity="0.25" />
                                </linearGradient>

                                {{-- Sand gradient (top) --}}
                <linearGradient id="sandTop" x1="150" y1="185" x2="170" y2="225" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#f5d78a" stop-opacity="0.9" />
                                    <stop offset="50%" stop-color="#e8c46a" stop-opacity="0.7" />
                                    <stop offset="100%" stop-color="#d4a84a" stop-opacity="0.9" />
                                </linearGradient>

                                {{-- Sand gradient (bottom) --}}
                                <linearGradient id="sandBottom" x1="150" y1="225" x2="170" y2="265" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#a898c8" stop-opacity="0.6" />
                                    <stop offset="50%" stop-color="#c8b8e0" stop-opacity="0.4" />
                                    <stop offset="100%" stop-color="#e8e0f0" stop-opacity="0.7" />
                                </linearGradient>

                                {{-- Dust particles --}}
                                <radialGradient id="dustGradient" cx="0" cy="0" r="2" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#b8b0c8" stop-opacity="0.8" />
                                    <stop offset="100%" stop-color="#8880a0" stop-opacity="0" />
                                </radialGradient>

                                {{-- Glow for hourglass --}}
                                <radialGradient id="hourglassGlow" cx="160" cy="225" r="30" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#c8b8e0" stop-opacity="0.3" />
                                    <stop offset="100%" stop-color="#8880a0" stop-opacity="0" />
                                </radialGradient>
                            </defs>

                            <g class="sd-warehouse-assembly">
                                {{-- Dust particles floating --}}
                                <g class="sd-dust-particles">
                                    <circle cx="120" cy="150" r="1.5" fill="url(#dustGradient)" class="sd-dust-1" />
                                    <circle cx="200" cy="140" r="1" fill="url(#dustGradient)" class="sd-dust-2" />
                                    <circle cx="110" cy="200" r="1.5" fill="url(#dustGradient)" class="sd-dust-3" />
                                    <circle cx="210" cy="190" r="1" fill="url(#dustGradient)" class="sd-dust-4" />
                                    <circle cx="130" cy="280" r="1.5" fill="url(#dustGradient)" class="sd-dust-5" />
                                    <circle cx="190" cy="270" r="1" fill="url(#dustGradient)" class="sd-dust-6" />
                                    <circle cx="100" cy="180" r="1" fill="url(#dustGradient)" class="sd-dust-7" />
                                    <circle cx="220" cy="220" r="1.5" fill="url(#dustGradient)" class="sd-dust-8" />
                                </g>

                                {{-- Hourglass glow --}}
                                <ellipse cx="160" cy="225" rx="35" ry="45" fill="url(#hourglassGlow)" class="sd-hourglass-glow" />

                                {{-- Suspension Wire --}}
                                <line x1="160" y1="0" x2="160" y2="46" stroke="#6a5e7a" stroke-width="1.5" stroke-dasharray="3 2" />

                                {{-- Steel Hook --}}
                                <g stroke="#7a708a" stroke-width="2.2" stroke-linecap="round" fill="none">
                                    <path d="M 160 46 C 160 38 167 34 170 39 C 173 44 168 49 160 52 L 160 68" />
                                    <ellipse cx="160" cy="68" rx="3.5" ry="1.8" fill="#5a506a" stroke="#4a405a" stroke-width="1" />
                                </g>

                                {{-- Steel Coat Hanger --}}
                                <g filter="drop-shadow(0 3px 8px rgba(0,0,0,0.2))">
                                    <path d="M 160 69 L 88 101 C 84 103 86 107 90 106 L 160 90 L 230 106 C 234 107 236 103 232 101 Z"
                                          fill="url(#steelHanger)"
                                          stroke="#5a506a"
                                          stroke-width="1.4"
                                          stroke-linejoin="round" />
                                    <!-- Steel Highlight -->
                                    <path d="M 100 100 L 155 88" stroke="#d8d0e8" stroke-width="0.9" stroke-linecap="round" opacity="0.5" />
                                </g>

                                {{-- Warehouse Bodice --}}
                                <g filter="drop-shadow(0 8px 14px rgba(30,24,40,0.2))">
                                    <!-- Shoulder Straps -->
                                    <line x1="120" y1="94" x2="124" y2="114" stroke="#1e1828" stroke-width="2.5" stroke-linecap="round" opacity="0.7" />
                                    <line x1="200" y1="94" x2="196" y2="114" stroke="#1e1828" stroke-width="2.5" stroke-linecap="round" opacity="0.7" />

                                    <!-- Bodice -->
                                    <path d="
                                        M 124 114
                                        C 134 109 148 119 160 117
                                        C 172 119 186 109 196 114
                                        Q 198 138 186 162
                                        L 134 162
                                        Q 122 138 124 114
                                        Z"
                                        fill="url(#warehouseFabric)"
                                        stroke="#121018"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Neckline Trim -->
                                    <path d="M 124 114 C 134 109 148 119 160 117 C 172 119 186 109 196 114" stroke="#383048" stroke-width="1.2" fill="none" />

                                    <!-- Gray Waistband -->
                                    <path d="M 134 162 Q 160 166 186 162" stroke="#7a6e8a" stroke-width="2.4" stroke-linecap="round" opacity="0.6" />
                                </g>

                                {{-- Warehouse Skirt with Hourglass --}}
                                <g class="sd-warehouse-skirt" filter="drop-shadow(0 12px 18px rgba(30,24,40,0.15))">
                                    <!-- Skirt Fabric -->
                                    <path d="
                                        M 134 162
                                        L 186 162
                                        Q 214 220 236 288
                                        Q 160 298 84 288
                                        Q 106 220 134 162
                                        Z"
                                        fill="url(#warehouseFabric)"
                                        stroke="#121018"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" />

                                    <!-- Drape Lines -->
                                    <path d="M 142 165 Q 132 225 116 289" stroke="#383048" stroke-width="1.1" stroke-linecap="round" opacity="0.5" />
                                    <path d="M 160 166 Q 160 228 160 295" stroke="#383048" stroke-width="1.1" stroke-linecap="round" opacity="0.5" />
                                    <path d="M 178 165 Q 188 225 204 289" stroke="#383048" stroke-width="1.1" stroke-linecap="round" opacity="0.5" />

                                    <!-- Bottom Hem -->
                                    <path d="M 84 288 Q 160 298 236 288" stroke="#121018" stroke-width="1.2" stroke-linecap="round" />

                                    <!-- HOURGLASS on skirt center -->
                                    <g class="sd-hourglass" filter="drop-shadow(0 4px 16px rgba(200,184,224,0.3))">
                                        <!-- Top bulb (sand flowing) -->
                                        <path d="
                                            M 150 185
                                            Q 142 195 145 210
                                            L 175 210
                                            Q 178 195 170 185
                                            Z"
                                            fill="url(#sandTop)" 
                                            stroke="#c8b8e0" 
                                            stroke-width="1.2"
                                            stroke-opacity="0.5" />
                                        
                                        <!-- Glass top frame -->
                                        <path d="M 150 185 Q 142 195 145 210" stroke="#d8c8f0" stroke-width="1.5" fill="none" stroke-linecap="round" opacity="0.6" />
                                        <path d="M 170 185 Q 178 195 175 210" stroke="#d8c8f0" stroke-width="1.5" fill="none" stroke-linecap="round" opacity="0.6" />

                                        <!-- Neck of hourglass -->
                                        <rect x="157" y="210" width="6" height="15" fill="url(#glassGradient)" stroke="#d8c8f0" stroke-width="1" rx="1" />
                                        
                                        <!-- Bottom bulb (sand collected) -->
                                        <path d="
                                            M 145 225
                                            Q 142 240 145 255
                                            L 175 255
                                            Q 178 240 175 225
                                            Z"
                                            fill="url(#sandBottom)" 
                                            stroke="#c8b8e0" 
                                            stroke-width="1.2"
                                            stroke-opacity="0.5" />
                                        
                                        <!-- Glass bottom frame -->
                                        <path d="M 145 225 Q 142 240 145 255" stroke="#d8c8f0" stroke-width="1.5" fill="none" stroke-linecap="round" opacity="0.6" />
                                        <path d="M 175 225 Q 178 240 175 255" stroke="#d8c8f0" stroke-width="1.5" fill="none" stroke-linecap="round" opacity="0.6" />

                                        <!-- Frame caps -->
                                        <rect x="145" y="180" width="30" height="5" rx="2" fill="#d8c8f0" opacity="0.7" />
                                        <rect x="145" y="260" width="30" height="5" rx="2" fill="#d8c8f0" opacity="0.7" />

                                        <!-- Flowing sand particles in neck -->
                                        <g class="sd-flowing-sand">
                                            <circle cx="160" cy="215" r="1.5" fill="#e8c46a" class="sd-sand-1" />
                                            <circle cx="158" cy="218" r="1" fill="#f5d78a" class="sd-sand-2" />
                                            <circle cx="162" cy="220" r="1" fill="#e8c46a" class="sd-sand-3" />
                                            <circle cx="159" cy="212" r="1.5" fill="#f5d78a" class="sd-sand-4" />
                                            <circle cx="161" cy="217" r="1" fill="#e8c46a" class="sd-sand-5" />
                                        </g>

                                        <!-- "419" on hourglass waist -->
                                        <text x="160" y="220"
                                              text-anchor="middle"
                                              font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                              font-size="14"
                                              font-weight="800"
                                              letter-spacing="-0.5"
                                              fill="#5a4a7a"
                                              style="filter: drop-shadow(0 1px 2px rgba(200,184,224,0.5));">
                                            419
                                        </text>
                                    </g>

                                    <!-- Time's up text below hourglass -->
                                    <text x="160" y="285"
                                          text-anchor="middle"
                                          font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                          font-size="10"
                                          font-weight="600"
                                          fill="#7a6e8a"
                                          opacity="0.7">
                                        SESI HABIS
                                    </text>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 419 + Session Expired + Actions --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 419 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        419
                    </h1>

                    {{-- SESSION EXPIRED --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        SESI BERAKHIR
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>Sesi Anda telah berakhir demi keamanan.</p>
                        <p>Silakan muat ulang halaman atau masuk kembali untuk melanjutkan.</p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Refresh Page --}}
                        <button onclick="window.location.reload()"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            MUAT ULANG HALAMAN
                        </button>

                        {{-- 2. Login --}}
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            MASUK KEMBALI
                        </a>

                        {{-- 3. Home --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BERANDA
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-419" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-419"
                                       name="search"
                                       placeholder="Jelajahi katalog setelah login..."
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
    /* Warehouse assembly very subtle sway */
    @keyframes sdWarehouseSway {
        0% { transform: rotate(-0.5deg) translateX(-0.3px); }
        50% { transform: rotate(0.8deg) translateX(0.3px); }
        100% { transform: rotate(-0.5deg) translateX(-0.3px); }
    }

    /* Dust floating */
    @keyframes sdDustFloat {
        0% { transform: translateY(0) translateX(0); opacity: 0.8; }
        50% { transform: translateY(-20px) translateX(5px); opacity: 0.4; }
        100% { transform: translateY(-40px) translateX(-3px); opacity: 0; }
    }

    /* Hourglass glow pulse */
    @keyframes sdHourglassGlow {
        0% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.15; }
        100% { transform: scale(1); opacity: 0.3; }
    }

    /* Sand flowing down */
    @keyframes sdSandFlow {
        0% { transform: translateY(-5px) scale(1); opacity: 1; }
        50% { transform: translateY(5px) scale(1.2); opacity: 0.7; }
        100% { transform: translateY(15px) scale(0.8); opacity: 0; }
    }

    .sd-warehouse-assembly {
        transform-origin: 160px 0px;
        animation: sdWarehouseSway 7s ease-in-out infinite;
        will-change: transform;
    }

    .sd-dust-particles circle {
        transform-origin: center center;
        animation: sdDustFloat 6s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-dust-1 { animation-delay: 0s; }
    .sd-dust-2 { animation-delay: 0.7s; }
    .sd-dust-3 { animation-delay: 1.4s; }
    .sd-dust-4 { animation-delay: 2.1s; }
    .sd-dust-5 { animation-delay: 2.8s; }
    .sd-dust-6 { animation-delay: 3.5s; }
    .sd-dust-7 { animation-delay: 4.2s; }
    .sd-dust-8 { animation-delay: 4.9s; }

    .sd-hourglass-glow {
        transform-origin: 160px 225px;
        animation: sdHourglassGlow 3s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-flowing-sand circle {
        transform-origin: center center;
        animation: sdSandFlow 2s ease-in infinite;
        will-change: transform, opacity;
    }

    .sd-sand-1 { animation-delay: 0s; }
    .sd-sand-2 { animation-delay: 0.3s; }
    .sd-sand-3 { animation-delay: 0.6s; }
    .sd-sand-4 { animation-delay: 0.9s; }
    .sd-sand-5 { animation-delay: 1.2s; }

    @media (prefers-reduced-motion: reduce) {
        .sd-warehouse-assembly,
        .sd-dust-particles circle,
        .sd-hourglass-glow,
        .sd-flowing-sand circle {
            animation: none !important;
        }
    }
</style>
@endpush