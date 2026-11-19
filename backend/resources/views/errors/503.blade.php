@extends('frontend.layouts.app')

@section('title', '503 — Sedang Diperbaiki | '.\App\Models\SiteSetting::storeName())

@section('content')
    <div class="flex flex-1 items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">
                
                {{-- Left Column: Dress Being Sewn/Repaired --}}
                <div class="flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="relative w-full max-w-[260px] sm:max-w-[300px] lg:max-w-[320px]">
                        <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full select-none overflow-visible" aria-hidden="true">
                            <defs>
                                {{-- Unfinished dress fabric (lighter, raw) --}}
                                <linearGradient id="rawFabric" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#f5f0f8" />
                                    <stop offset="50%" stop-color="#e8e0f0" />
                                    <stop offset="100%" stop-color="#d8d0e8" />
                                </linearGradient>

                                {{-- Wooden mannequin/hanger gradient --}}
                                <linearGradient id="mannequinWood" x1="85" y1="75" x2="235" y2="105" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#d4b896" />
                                    <stop offset="50%" stop-color="#c4a07a" />
                                    <stop offset="100%" stop-color="#b08c60" />
                                </linearGradient>

                                {{-- Sewing thread gradient --}}
                <linearGradient id="threadGradient" x1="160" y1="100" x2="160" y2="300" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#7c3aed" />
                                    <stop offset="50%" stop-color="#a855f7" />
                                    <stop offset="100%" stop-color="#7c3aed" />
                                </linearGradient>

                                {{-- Needle metal gradient --}}
                                <linearGradient id="needleMetal" x1="155" y1="180" x2="165" y2="280" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#e8e0e8" />
                                    <stop offset="50%" stop-color="#c8c0c8" />
                                    <stop offset="100%" stop-color="#a8a0a8" />
                                </linearGradient>

                                {{-- Measuring tape gradient --}}
                                <linearGradient id="tapeGradient" x1="100" y1="100" x2="220" y2="100" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#f0e8f8" />
                                    <stop offset="50%" stop-color("#fff") stop-opacity="0.8" />
                                    <stop offset="100%" stop-color="#f0e8f8" />
                                </linearGradient>

                                {{-- Pin heads gradient --}}
                                <radialGradient id="pinHead" cx="0" cy="0" r="3" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#fff" />
                                    <stop offset="50%" stop-color="#e8e0e8" />
                                    <stop offset="100%" stop-color="#c8c0c8" />
                                </radialGradient>

                                {{-- Chalk mark gradient --}}
                                <linearGradient id="chalkGradient" x1="0" y1="0" x2="20" y2="0" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#a8d0ff" stop-opacity="0.8" />
                                    <stop offset="100%" stop-color="#7ca8e8" stop-opacity="0.6" />
                                </linearGradient>
                            </defs>

                            <g class="sd-repair-assembly">
                                {{-- Suspension Wire --}}
                                <line x1="160" y1="0" x2="160" y2="46" stroke="#a8a0b8" stroke-width="1.5" stroke-dasharray="3 2" />

                                {{-- Wooden Hook --}}
                                <g stroke="#b08c60" stroke-width="2.2" stroke-linecap="round" fill="none">
                                    <path d="M 160 46 C 160 38 167 34 170 39 C 173 44 168 49 160 52 L 160 68" />
                                    <ellipse cx="160" cy="68" rx="3.5" ry="1.8" fill="#a08050" stroke="#8a6a40" stroke-width="1" />
                                </g>

                                {{-- Wooden Mannequin/Hanger --}}
                                <g filter="drop-shadow(0 3px 8px rgba(176,140,96,0.3))">
                                    <path d="M 160 69 L 88 101 C 84 103 86 107 90 106 L 160 90 L 230 106 C 234 107 236 103 232 101 Z"
                                          fill="url(#mannequinWood)"
                                          stroke="#8a6a40"
                                          stroke-width="1.4"
                                          stroke-linejoin="round" />
                                    <!-- Wood Highlight -->
                                    <path d="M 100 100 L 155 88" stroke="#f5e8d8" stroke-width="0.9" stroke-linecap="round" opacity="0.6" />
                                </g>

                                {{-- Unfinished Dress on Mannequin --}}
                                <g filter="drop-shadow(0 8px 14px rgba(216,208,232,0.2))">
                                    <!-- Shoulder Straps (raw edges) -->
                                    <line x1="120" y1="94" x2="124" y2="114" stroke="#c8c0d8" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="3 2" />
                                    <line x1="200" y1="94" x2="196" y2="114" stroke="#c8c0d8" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="3 2" />

                                    <!-- Bodice (unfinished) -->
                                    <path d="
                                        M 124 114
                                        C 134 109 148 119 160 117
                                        C 172 119 186 109 196 114
                                        Q 198 138 186 162
                                        L 134 162
                                        Q 122 138 124 114
                                        Z"
                                        fill="url(#rawFabric)"
                                        stroke="#d8d0e8"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" 
                                        stroke-dasharray="8 4" />

                                    <!-- Sweetheart Neckline (pinned) -->
                                    <path d="M 124 114 C 134 109 148 119 160 117 C 172 119 186 109 196 114" 
                                          stroke="url(#chalkGradient)" stroke-width="1.5" fill="none" stroke-dasharray="4 3" />
                                    
                                    <!-- Pins along neckline -->
                                    <g class="sd-pins-neckline">
                                        <circle cx="130" cy="112" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-1" />
                                        <circle cx="145" cy="110" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-2" />
                                        <circle cx="160" cy="111" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-3" />
                                        <circle cx="175" cy="110" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-4" />
                                        <circle cx="190" cy="112" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-5" />
                                    </g>

                                    <!-- Waist pins -->
                                    <g class="sd-pins-waist">
                                        <circle cx="138" cy="162" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-6" />
                                        <circle cx="160" cy="164" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-7" />
                                        <circle cx="182" cy="162" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-8" />
                                    </g>

                                    <!-- Measuring tape across waist -->
                                    <g class="sd-measuring-tape" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.1))">
                                        <path d="M 134 162 Q 160 166 186 162" 
                                              stroke="url(#tapeGradient)" stroke-width="3.5" fill="none" stroke-linecap="round" />
                                        <!-- Tape markings -->
                                        <g stroke="#7c3aed" stroke-width="0.8" stroke-linecap="round">
                                            <line x1="138" y1="160" x2="138" y2="164" />
                                            <line x1="144" y1="160" x2="144" y2="164" />
                                            <line x1="150" y1="160" x2="150" y2="164" stroke-width="1.2" />
                                            <line x1="156" y1="160" x2="156" y2="164" />
                                            <line x1="162" y1="160" x2="162" y2="164" />
                                            <line x1="168" y1="160" x2="168" y2="164" stroke-width="1.2" />
                                            <line x1="174" y1="160" x2="174" y2="164" />
                                            <line x1="180" y1="160" x2="180" y2="164" />
                                        </g>
                                    </g>
                                </g>

                                {{-- Skirt Being Sewn with Needle --}}
                                <g class="sd-repair-skirt" filter="drop-shadow(0 12px 18px rgba(216,208,232,0.15))">
                                    <!-- Skirt Fabric (raw hem) -->
                                    <path d="
                                        M 134 162
                                        L 186 162
                                        Q 214 220 236 288
                                        Q 160 298 84 288
                                        Q 106 220 134 162
                                        Z"
                                        fill="url(#rawFabric)"
                                        stroke="#d8d0e8"
                                        stroke-width="1.6"
                                        stroke-linejoin="round" 
                                        stroke-dasharray="10 5" />

                                    <!-- Drape Lines (chalk) -->
                                    <path d="M 142 165 Q 132 225 116 289" stroke="url(#chalkGradient)" stroke-width="1.1" stroke-linecap="round" opacity="0.7" stroke-dasharray="5 3" />
                                    <path d="M 160 166 Q 160 228 160 295" stroke="url(#chalkGradient)" stroke-width="1.1" stroke-linecap="round" opacity="0.7" stroke-dasharray="5 3" />
                                    <path d="M 178 165 Q 188 225 204 289" stroke="url(#chalkGradient)" stroke-width="1.1" stroke-linecap="round" opacity="0.7" stroke-dasharray="5 3" />

                                    <!-- Raw Bottom Hem -->
                                    <path d="M 84 288 Q 160 298 236 288" stroke="#d8d0e8" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="6 4" />

                                    <!-- SEWING NEEDLE animating -->
                                    <g class="sd-needle" filter="drop-shadow(0 2px 8px rgba(124,58,237,0.3))">
                                        <!-- Needle shaft -->
                                        <path d="M 160 180 Q 160 210 160 250" 
                                              stroke="url(#needleMetal)" stroke-width="2" fill="none" stroke-linecap="round" />
                                        <!-- Needle eye -->
                                        <ellipse cx="160" cy="185" rx="1.5" ry="3" fill="#2e253a" stroke="#a8a0a8" stroke-width="0.5" />
                                        <!-- Thread from needle eye -->
                                        <path d="M 160 188 Q 160 220 160 260" 
                                              stroke="url(#threadGradient)" stroke-width="1.5" fill="none" 
                                              stroke-linecap="round" stroke-dasharray="4 2" 
                                              class="sd-thread" />
                                        <!-- Needle point -->
                                        <polygon points="158,250 162,250 160,258" fill="url(#needleMetal)" stroke="#a8a0a8" stroke-width="0.5" />
                                    </g>

                                    <!-- Stitches appearing behind needle -->
                                    <g class="sd-stitches">
                                        <path d="M 155 190 L 165 190" stroke="url(#threadGradient)" stroke-width="1.2" stroke-linecap="round" opacity="0" class="sd-stitch-1" />
                                        <path d="M 155 205 L 165 205" stroke="url(#threadGradient)" stroke-width="1.2" stroke-linecap="round" opacity="0" class="sd-stitch-2" />
                                        <path d="M 155 220 L 165 220" stroke="url(#threadGradient)" stroke-width="1.2" stroke-linecap="round" opacity="0" class="sd-stitch-3" />
                                        <path d="M 155 235 L 165 235" stroke="url(#threadGradient)" stroke-width="1.2" stroke-linecap="round" opacity="0" class="sd-stitch-4" />
                                        <path d="M 155 250 L 165 250" stroke="url(#threadGradient)" stroke-width="1.2" stroke-linecap="round" opacity="0" class="sd-stitch-5" />
                                    </g>

                                    <!-- Pins along skirt sides -->
                                    <g class="sd-pins-skirt">
                                        <circle cx="140" cy="200" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-9" />
                                        <circle cx="180" cy="200" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-10" />
                                        <circle cx="135" cy="240" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-11" />
                                        <circle cx="185" cy="240" r="2.5" fill="url(#pinHead)" stroke="#a8a0a8" stroke-width="0.5" class="sd-pin-12" />
                                    </g>

                                    <!-- Chalk marks on skirt -->
                                    <text x="140" y="190" text-anchor="end" font-family="system-ui" font-size="8" fill="url(#chalkGradient)" class="sd-chalk-mark-1">✂</text>
                                    <text x="180" y="190" text-anchor="start" font-family="system-ui" font-size="8" fill="url(#chalkGradient)" class="sd-chalk-mark-2">✂</text>
                                    <text x="135" y="230" text-anchor="end" font-family="system-ui" font-size="8" fill="url(#chalkGradient)" class="sd-chalk-mark-3">✂</text>
                                    <text x="185" y="230" text-anchor="start" font-family="system-ui" font-size="8" fill="url(#chalkGradient)" class="sd-chalk-mark-4">✂</text>

                                    <!-- "503" on fabric like a label -->
                                    <g class="sd-repair-label" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.1))">
                                        <rect x="145" y="120" width="30" height="18" rx="3" fill="#f5f0f8" stroke="#7c3aed" stroke-width="1.5" />
                                        <text x="160" y="133"
                                              text-anchor="middle"
                                              font-family="'Plus Jakarta Sans', system-ui, sans-serif"
                                              font-size="12"
                                              font-weight="800"
                                              fill="#7c3aed">
                                            503
                                        </text>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                {{-- Right Column: 503 + Maintenance + Actions --}}
                <div class="flex flex-col items-center text-center lg:col-span-7 lg:items-start lg:text-left">
                    
                    {{-- Giant 503 Number --}}
                    <h1 class="font-sans text-6xl font-extrabold tracking-tight text-[var(--text)] sm:text-7xl lg:text-8xl leading-none">
                        503
                    </h1>

                    {{-- SERVICE UNAVAILABLE --}}
                    <h2 class="mt-2 text-lg font-bold tracking-widest text-[var(--text)] uppercase sm:text-xl">
                        SEDANG DIPERBAIKI
                    </h2>

                    {{-- Explanation --}}
                    <div class="mt-3 space-y-0.5 text-xs text-[var(--text-muted)] leading-relaxed sm:text-sm max-w-md">
                        <p>Kami sedang mempercantik toko untuk Anda.</p>
                        <p>Layanan akan kembali normal dalam waktu singkat.</p>
                    </div>

                    {{-- Visual Sewing Progress --}}
                    <div class="mt-5 w-full max-w-md">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="h-5 w-5 text-[var(--brand)] animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-[var(--brand)]">MENJAHIT ULANG SISTEM...</span>
                        </div>
                        <div class="h-2 rounded-full bg-[var(--surface-2)] border border-[var(--border)] overflow-hidden relative">
                            <div class="h-full rounded-full bg-gradient-to-r from-[var(--brand)] via-[var(--accent)] to-[var(--brand)] animate-pulse" style="width: 100%"></div>
                        </div>
                        <p class="mt-2 text-center text-[10px] text-[var(--text-muted)]">Estimasi selesai: <span id="eta-text" class="font-bold text-[var(--brand)]">beberapa menit</span></p>
                    </div>

                    {{-- 3 Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 lg:justify-start">
                        {{-- 1. Refresh --}}
                        <button onclick="window.location.reload()"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--brand)] px-5 py-2.5 text-xs font-bold tracking-wider text-white uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-dark)] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2"
                           disabled>
                            <svg class="h-3.5 w-3.5 mr-1.5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            MUAT ULANG NANTI
                        </button>

                        {{-- 2. Home --}}
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            BERANDA
                        </a>

                        {{-- 3. Contact --}}
                        <a href="{{ route('help') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-[var(--surface)] border border-[var(--brand)]/25 px-5 py-2.5 text-xs font-bold tracking-wider text-[var(--brand)] uppercase shadow-xs transition-all duration-200 hover:bg-[var(--brand-soft)] hover:border-[var(--brand)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)] focus:ring-offset-2">
                            HUBUNGI KAMI
                        </a>
                    </div>

                    {{-- Sleek Search Pill --}}
                    <div class="mt-6 w-full max-w-sm">
                        <form action="{{ route('products.index') }}" method="GET" class="relative" role="search">
                            <label for="search-503" class="sr-only">Cari dress atau busana</label>
                            <div class="group relative flex items-center">
                                <input type="text"
                                       id="search-503"
                                       name="search"
                                       placeholder="Jelajahi katalog saat menunggu..."
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
    // ETA countdown for 503 page
    document.addEventListener('DOMContentLoaded', () => {
        const etaText = document.getElementById('eta-text');
        if (!etaText) return;

        const etas = [
            'beberapa menit',
            'sekitar 2 menit',
            'sekitar 1 menit',
            'hampir selesai...',
            'segera kembali!'
        ];
        let index = 0;

        const rotateEta = () => {
            etaText.textContent = etas[index];
            index = (index + 1) % etas.length;
        };

        // Initial
        rotateEta();
        // Rotate every 6 seconds
        setInterval(rotateEta, 6000);
    });
</script>
@endpush

@push('styles')
<style>
    /* Repair assembly subtle sway */
    @keyframes sdRepairSway {
        0% { transform: rotate(-0.8deg) translateX(-0.5px); }
        50% { transform: rotate(1.2deg) translateX(0.5px); }
        100% { transform: rotate(-0.8deg) translateX(-0.5px); }
    }

    /* Needle sewing up and down */
    @keyframes sdNeedleSew {
        0% { transform: translateY(0); }
        30% { transform: translateY(35px); }
        60% { transform: translateY(70px); }
        100% { transform: translateY(0); }
    }

    /* Thread following needle */
    @keyframes sdThreadPull {
        0% { stroke-dashoffset: 0; opacity: 0.8; }
        100% { stroke-dashoffset: 40; opacity: 0.4; }
    }

    /* Stitches appearing */
    @keyframes sdStitchAppear {
        0% { opacity: 0; stroke-dashoffset: 10; }
        100% { opacity: 1; stroke-dashoffset: 0; }
    }

    /* Pins subtle bounce */
    @keyframes sdPinBounce {
        0% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.2) rotate(5deg); }
        100% { transform: scale(1) rotate(0deg); }
    }

    /* Chalk marks fade in */
    @keyframes sdChalkFade {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Repair label pulse */
    @keyframes sdLabelPulse {
        0% { transform: scale(1); box-shadow: 0 0 0 rgba(124,58,237,0); }
        50% { transform: scale(1.05); box-shadow: 0 0 12px rgba(124,58,237,0.4); }
        100% { transform: scale(1); box-shadow: 0 0 0 rgba(124,58,237,0); }
    }

    .sd-repair-assembly {
        transform-origin: 160px 0px;
        animation: sdRepairSway 6s ease-in-out infinite;
        will-change: transform;
    }

    .sd-needle {
        transform-origin: 160px 180px;
        animation: sdNeedleSew 3s ease-in-out infinite;
        will-change: transform;
    }

    .sd-thread {
        animation: sdThreadPull 3s linear infinite;
        will-change: stroke-dashoffset, opacity;
    }

    .sd-stitches path {
        animation: sdStitchAppear 0.5s ease-out forwards;
        will-change: opacity, stroke-dashoffset;
    }

    .sd-stitch-1 { animation-delay: 0.6s; }
    .sd-stitch-2 { animation-delay: 1.2s; }
    .sd-stitch-3 { animation-delay: 1.8s; }
    .sd-stitch-4 { animation-delay: 2.4s; }
    .sd-stitch-5 { animation-delay: 3s; }

    .sd-pins-neckline circle,
    .sd-pins-waist circle,
    .sd-pins-skirt circle {
        transform-origin: center center;
        animation: sdPinBounce 2s ease-in-out infinite;
        will-change: transform;
    }

    .sd-pin-1 { animation-delay: 0s; }
    .sd-pin-2 { animation-delay: 0.15s; }
    .sd-pin-3 { animation-delay: 0.3s; }
    .sd-pin-4 { animation-delay: 0.45s; }
    .sd-pin-5 { animation-delay: 0.6s; }
    .sd-pin-6 { animation-delay: 0.8s; }
    .sd-pin-7 { animation-delay: 0.95s; }
    .sd-pin-8 { animation-delay: 1.1s; }
    .sd-pin-9 { animation-delay: 1.3s; }
    .sd-pin-10 { animation-delay: 1.45s; }
    .sd-pin-11 { animation-delay: 1.6s; }
    .sd-pin-12 { animation-delay: 1.75s; }

    .sd-chalk-mark-1,
    .sd-chalk-mark-2,
    .sd-chalk-mark-3,
    .sd-chalk-mark-4 {
        animation: sdChalkFade 1s ease-out forwards;
        will-change: opacity, transform;
        opacity: 0;
    }

    .sd-chalk-mark-1 { animation-delay: 0.5s; }
    .sd-chalk-mark-2 { animation-delay: 0.7s; }
    .sd-chalk-mark-3 { animation-delay: 0.9s; }
    .sd-chalk-mark-4 { animation-delay: 1.1s; }

    .sd-repair-label {
        animation: sdLabelPulse 2.5s ease-in-out infinite;
        will-change: transform, box-shadow;
    }

    @media (prefers-reduced-motion: reduce) {
        .sd-repair-assembly,
        .sd-needle,
        .sd-thread,
        .sd-stitches path,
        .sd-pins-neckline circle,
        .sd-pins-waist circle,
        .sd-pins-skirt circle,
        .sd-chalk-mark-1,
        .sd-chalk-mark-2,
        .sd-chalk-mark-3,
        .sd-chalk-mark-4,
        .sd-repair-label {
            animation: none !important;
        }
        .sd-stitches path { opacity: 1; }
        .sd-chalk-mark-1,
        .sd-chalk-mark-2,
        .sd-chalk-mark-3,
        .sd-chalk-mark-4 { opacity: 1; }
    }
</style>
@endpush