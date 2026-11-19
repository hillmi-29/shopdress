@extends('frontend.layouts.app')

@section('title', '429 — Terlalu Banyak Permintaan | '.\App\Models\SiteSetting::storeName())

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Queue of Dresses with Loading Bar --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px]">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Queue dress fabric gradients (different colors) --}}
                                <linearGradient id="queueFabric1" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2e253a" />
                                    <stop offset="50%" stop-color="#241d2e" />
                                    <stop offset="100%" stop-color="#181320" />
                                </linearGradient>

                                <linearGradient id="queueFabric2" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2e3a4a" />
                                    <stop offset="50%" stop-color="#242d38" />
                                    <stop offset="100%" stop-color="#181e28" />
                                </linearGradient>

                                <linearGradient id="queueFabric3" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#3a2e3a" />
                                    <stop offset="50%" stop-color="#2e242e" />
                                    <stop offset="100%" stop-color="#1e1820" />
                                </linearGradient>

                                <linearGradient id="queueFabric4" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2e3a3a" />
                                    <stop offset="50%" stop-color="#242d2d" />
                                    <stop offset="100%" stop-color="#181e1e" />
                                </linearGradient>

                                {{-- Silver hanger gradient --}}
                                <linearGradient id="silverHanger" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#c8c0d0" />
                                    <stop offset="50%" stop-color="#a8a0b8" />
                                    <stop offset="100%" stop-color="#888098" />
                                </linearGradient>

                                {{-- Loading bar gradient --}}
                                <linearGradient id="loadingBarGradient" x1="60" y1="200" x2="260" y2="200" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#7c3aed" />
                                    <stop offset="50%" stop-color="#a855f7" />
                                    <stop offset="100%" stop-color="#7c3aed" />
                                </linearGradient>

                                {{-- Loading bar background --}}
                                <linearGradient id="loadingBarBg" x1="60" y1="200" x2="260" y2="200" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2d2438" />
                                    <stop offset="100%" stop-color="#1e1828" />
                                </linearGradient>

                                {{-- Progress glow --}}
                                <radialGradient id="progressGlow" cx="160" cy="200" r="50" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#7c3aed" stop-opacity="0.25" />
                                    <stop offset="100%" stop-color="#7c3aed" stop-opacity="0" />
                                </radialGradient>

                                {{-- Number badge gradient --}}
                                <linearGradient id="numberBadge" x1="0" y1="0" x2="30" y2="30" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#7c3aed" />
                                    <stop offset="100%" stop-color="#a855f7" />
                                </linearGradient>
                            </defs>

                            <g class="sd-queue-assembly">
                                {{-- Progress glow background --}}
                                <ellipse cx="160" cy="200" rx="80" ry="15" fill="url(#progressGlow)" class="sd-progress-glow" />

                                {{-- Queue Dresses (4 dresses side by side, slightly overlapping) --}}
                                <g class="sd-queue-dresses">
                                    <!-- Dress 1 (back) -->
                                    <g class="sd-dress-queue sd-dress-1" transform="translate(20, 0)">
                                        <use href="#dressTemplate" fill="url(#queueFabric1)" />
                                        <text x="160" y="130" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="800" fill="#7c3aed" opacity="0.7">#1</text>
                                    </g>

                                    <!-- Dress 2 -->
                                    <g class="sd-dress-queue sd-dress-2" transform="translate(40, 0)">
                                        <use href="#dressTemplate" fill="url(#queueFabric2)" />
                                        <text x="160" y="130" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="800" fill="#7c3aed" opacity="0.8">#2</text>
                                    </g>

                                    <!-- Dress 3 -->
                                    <g class="sd-dress-queue sd-dress-3" transform="translate(60, 0)">
                                        <use href="#dressTemplate" fill="url(#queueFabric3)" />
                                        <text x="160" y="130" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="800" fill="#7c3aed" opacity="0.9">#3</text>
                                    </g>

                                    <!-- Dress 4 (front - current) -->
                                    <g class="sd-dress-queue sd-dress-4 sd-current-dress" transform="translate(80, 0)">
                                        <use href="#dressTemplate" fill="url(#queueFabric4)" />
                                        <text x="160" y="130" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="800" fill="#a855f7">#4</text>
                                    </g>
                                </g>

                                {{-- Dress Template Definition --}}
                                <defs>
                                    <g id="dressTemplate">
                                        <!-- Mini hanger -->
                                        <g stroke="#9a90a8" stroke-width="1.2" stroke-linecap="round" fill="none">
                                            <path d="M 160 40 L 135 58 L 160 52 L 185 58" fill="url(#silverHanger)" stroke="#7a7088" stroke-width="0.8" stroke-linejoin="round" />
                                        </g>

                                        <!-- Mini bodice -->
                                        <path d="
                                            M 150 62
                                            C 155 60 165 60 170 62
                                            Q 172 72 168 80
                                            L 152 80
                                            Q 148 72 150 62
                                            Z"
                                            stroke="#121018" stroke-width="0.8" stroke-linejoin="round" />

                                        <!-- Mini skirt -->
                                        <path d="
                                            M 152 80
                                            L 168 80
                                            Q 180 110 188 145
                                            Q 160 152 72 145
                                            Q 80 110 152 80
                                            Z"
                                            stroke="#121018" stroke-width="0.8" stroke-linejoin="round" />
                                        
                                        <!-- Mini drape lines -->
                                        <path d="M 155 82 Q 150 100 145 140" stroke="#383048" stroke-width="0.5" stroke-linecap="round" opacity="0.5" />
                                        <path d="M 165 82 Q 170 100 175 140" stroke="#383048" stroke-width="0.5" stroke-linecap="round" opacity="0.5" />
                                    </g>
                                </defs>

                                {{-- Loading Bar at bottom --}}
                                <g class="sd-loading-bar-container">
                                    <!-- Bar background -->
                                    <rect x="60" y="195" width="200" height="10" rx="5" fill="url(#loadingBarBg)" stroke="#383048" stroke-width="0.8" />
                                    
                                    <!-- Bar progress -->
                                    <rect x="60" y="195" width="0" height="10" rx="5" fill="url(#loadingBarGradient)" class="sd-loading-progress" />
                                    
                                    <!-- Progress percentage text -->
                                    <text x="160" y="190"
                                          text-anchor="middle"
                                          font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                          font-size="9"
                                          font-weight="700"
                                          fill="#7c3aed"
                                          class="sd-progress-text">
                                        0%
                                    </text>
                                </g>

                                {{-- "429" on current dress --}}
                                <text x="240" y="100"
                                      text-anchor="middle"
                                      font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                      font-size="28"
                                      font-weight="800"
                                      letter-spacing="-0.5"
                                      fill="#7c3aed"
                                      style="filter: drop-shadow(0 2px 8px rgba(124,58,237,0.4));"
                                      class="sd-queue-code">
                                    429
                                </text>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 429 + Too Many Requests + Actions --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 429 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        429
                    </h1>

                    {{-- TOO MANY REQUESTS --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        TERLALU BANYAK PERMINTAAN
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>Anda mengirim terlalu banyak permintaan sekaligus.</p>
                        <p>Tunggu sebentar, antrian sedang diproses.</p>
                    </div>

                    {{-- Visual Progress Bar (mirrors SVG) --}}
                    <div class="mt-5 w-full max-w-md" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" aria-label="Antrian permintaan">
                        <div class="h-3 rounded-full bg-[var(--surface-2)] border border-[var(--border)] overflow-hidden relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-[var(--brand)] via-[var(--accent)] to-[var(--brand)] transition-all duration-500 ease-out" 
                                 style="width: 0%" id="progress-bar"></div>
                        </div>
                        <p class="mt-2 text-center text-[10px] font-bold text-[var(--brand)]" id="progress-text">Menunggu antrian... 0%</p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Wait & Retry --}}
                        <button onclick="window.location.reload()"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2"
                           id="retry-btn" disabled>
                            <svg class="h-3.5 w-3.5 mr-1.5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            TUNGGU & COBA LAGI
                        </button>

                        {{-- 2. Home --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BERANDA
                        </a>

                        {{-- 3. Contact --}}
                        <a href="{{ route('help') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BANTUAN
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-429" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-429"
                                       name="search"
                                       placeholder="Jelajahi sambil menunggu..."
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

@push('scripts')
<script>
    // Progress bar animation for 429 page
    document.addEventListener('DOMContentLoaded', () => {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const retryBtn = document.getElementById('retry-btn');
        
        if (!progressBar || !progressText) return;

        let progress = 0;
        const duration = 30000; // 30 seconds
        const interval = 300; // update every 300ms
        const increment = (interval / duration) * 100;

        const animate = () => {
            progress += increment;
            if (progress > 100) progress = 100;
            
            progressBar.style.width = progress + '%';
            progressText.textContent = `Menunggu antrian... ${Math.round(progress)}%`;

            if (progress < 100) {
                requestAnimationFrame(() => setTimeout(animate, interval));
            } else {
                progressText.textContent = 'Siap dicoba kembali';
                progressText.style.color = 'var(--success)';
                if (retryBtn) {
                    retryBtn.disabled = false;
                    retryBtn.innerHTML = 'COBA LAGI SEKARANG';
                }
            }
        };

        // Start animation
        animate();
    });
</script>
@endpush

@push('styles')
<style>
    /* Queue dresses bounce into position */
    @keyframes sdDressQueueIn {
        0% { transform: translateX(0) translateY(20px); opacity: 0; }
        60% { transform: translateX(0) translateY(-3px); opacity: 1; }
        100% { transform: translateX(0) translateY(0); opacity: 1; }
    }

    /* Current dress pulse */
    @keyframes sdCurrentDressPulse {
        0% { transform: translateX(80px) scale(1); filter: drop-shadow(0 4px 12px rgba(124,58,237,0.2)); }
        50% { transform: translateX(80px) scale(1.02); filter: drop-shadow(0 6px 16px rgba(168,85,247,0.4)); }
        100% { transform: translateX(80px) scale(1); filter: drop-shadow(0 4px 12px rgba(124,58,237,0.2)); }
    }

    /* Queue dresses subtle sway */
    @keyframes sdQueueSway {
        0% { transform: translateX(var(--tx, 0)) rotate(-0.5deg); }
        50% { transform: translateX(var(--tx, 0)) rotate(0.8deg); }
        100% { transform: translateX(var(--tx, 0)) rotate(-0.5deg); }
    }

    /* Progress glow pulse */
    @keyframes sdProgressGlowPulse {
        0% { transform: scaleX(1); opacity: 0.25; }
        50% { transform: scaleX(1.1); opacity: 0.15; }
        100% { transform: scaleX(1); opacity: 0.25; }
    }

    /* 429 code float */
    @keyframes sdQueueCodeFloat {
        0% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
        100% { transform: translateY(0); }
    }

    .sd-dress-queue {
        transform-origin: 160px 0px;
        animation: sdDressQueueIn 0.8s ease-out forwards, sdQueueSway 4s ease-in-out infinite;
        will-change: transform;
        opacity: 0;
    }

    .sd-dress-1 { animation-delay: 0.1s, 0.1s; --tx: 20px; }
    .sd-dress-2 { animation-delay: 0.2s, 0.2s; --tx: 40px; }
    .sd-dress-3 { animation-delay: 0.3s, 0.3s; --tx: 60px; }
    .sd-dress-4 { animation-delay: 0.4s, 0.4s; --tx: 80px; }

    .sd-current-dress {
        animation: sdDressQueueIn 0.8s ease-out forwards, sdCurrentDressPulse 2.5s ease-in-out infinite;
        will-change: transform, filter;
        z-index: 10;
    }

    .sd-progress-glow {
        transform-origin: 160px 200px;
        animation: sdProgressGlowPulse 2s ease-in-out infinite;
        will-change: transform, opacity;
    }

    .sd-queue-code {
        animation: sdQueueCodeFloat 3s ease-in-out infinite;
        will-change: transform;
    }

    .sd-loading-progress {
        animation: sdLoadingProgress 30s linear forwards;
        will-change: width;
    }

    @keyframes sdLoadingProgress {
        0% { width: 0%; }
        100% { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .sd-dress-queue,
        .sd-current-dress,
        .sd-progress-glow,
        .sd-queue-code,
        .sd-loading-progress {
            animation: none !important;
        }
        .sd-loading-progress { width: 100%; }
    }
</style>
@endpush