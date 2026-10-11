<x-filament-panels::page>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <style>
        .font-headline-sm { font-family: 'Space Grotesk', sans-serif; font-size: 20px; line-height: 28px; font-weight: 600; }
        .font-headline-lg { font-family: 'Space Grotesk', sans-serif; font-size: 30px; line-height: 38px; font-weight: 700; }
        .font-title-md { font-family: 'Inter', sans-serif; font-size: 18px; line-height: 26px; font-weight: 600; }
        .font-title-sm { font-family: 'Inter', sans-serif; font-size: 15px; line-height: 22px; font-weight: 600; }
        .font-body-sm { font-family: 'Inter', sans-serif; font-size: 13px; line-height: 20px; }
        .font-label-md { font-family: 'Inter', sans-serif; font-size: 13px; line-height: 18px; font-weight: 500; }
        .font-caption { font-family: 'Inter', sans-serif; font-size: 11px; line-height: 16px; font-weight: 500; }

        .bg-surface-container-lowest { background-color: #ffffff; }
        .dark .bg-surface-container-lowest { background-color: #111827; }
        .bg-surface-soft { background-color: #F8FAFC; }
        .dark .bg-surface-soft { background-color: #1f2937; }
        .text-neutral-heading { color: #0F172A; }
        .dark .text-neutral-heading { color: #f9fafb; }
        .text-neutral-muted { color: #64748B; }
        .dark .text-neutral-muted { color: #9ca3af; }
        
        .bg-primary-container { background-color: #ff5d00; }
        .bg-primary-hover { background-color: #E05200; }
        .text-primary { color: #ff5d00; }
        .bg-primary-subtle { background-color: #FFF4ED; }
        .dark .bg-primary-subtle { background-color: rgba(255, 93, 0, 0.15); }
        .text-accent-emerald { color: #10B981; }
        .bg-accent-emerald { background-color: #10B981; }
        .bg-accent-emerald-subtle { background-color: #ECFDF5; }
        .dark .bg-accent-emerald-subtle { background-color: rgba(16, 185, 129, 0.15); }
        .text-status-warning { color: #F59E0B; }
        .bg-status-warning-subtle { background-color: #FFFBEB; }
        .dark .bg-status-warning-subtle { background-color: rgba(245, 158, 11, 0.15); }
        .text-status-critical { color: #EF4444; }
        .bg-status-critical-subtle { background-color: #FEF2F2; }
        .dark .bg-status-critical-subtle { background-color: rgba(239, 68, 68, 0.15); }
        .border-neutral-border-subtle { border-color: #F1F5F9; }
        .dark .border-neutral-border-subtle { border-color: #374151; }
    </style>

    <div class="space-y-6">
        <!-- Context Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-surface-container-lowest via-surface-soft to-surface-container-lowest p-6 border border-gray-100 dark:border-gray-800 shadow-xs">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-primary-subtle text-primary text-caption font-caption">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        <span>Algoritma Dual-Engine Aktif</span>
                    </div>
                    <p class="font-headline-sm text-neutral-heading leading-tight">
                        Analitik prediktif stok cerdas menggunakan Weighted Moving Average (WMA) & Economic Order Quantity (EOQ)
                    </p>
                    <p class="font-body-sm text-neutral-muted">
                        Memproyeksikan laju pengurangan unit per SKU berdasarkan bobot penjualan 14 hari terakhir, lead time konveksi 7 hari kerja, dan margin biaya penyimpanan optimal.
                    </p>
                </div>
                <div class="flex items-center gap-3 self-start md:self-auto shrink-0">
                    <div class="px-4 py-2.5 rounded-xl bg-surface-container-lowest border border-gray-100 dark:border-gray-800 shadow-xs flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl">warehouse</span>
                        <div>
                            <p class="text-caption font-caption text-neutral-muted">Total SKU Aktif</p>
                            <p class="text-title-sm font-title-sm text-neutral-heading">{{ count($this->forecastData) }} Varian Busana</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-orange-500/5 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- TOP ROW: Two Visual Analytics Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- 1. Donut Chart Widget: Distribusi Risiko Ketahanan Stok -->
            @php
                $dist = $this->riskDistribution;
                $safePct = $dist['safe_pct'] ?: 75;
                $slowPct = $dist['slow_moving_pct'] ?: 20;
                $warnPct = $dist['warning_pct'] ?: 5;
                $critPct = $dist['critical_pct'] ?: 0;

                $safeCirc = round((339.29 * $safePct) / 100, 2);
                $slowCirc = round((339.29 * $slowPct) / 100, 2);
                $warnCirc = round((339.29 * $warnPct) / 100, 2);
                $critCirc = round((339.29 * $critPct) / 100, 2);
            @endphp
            <div class="lg:col-span-5 bg-surface-container-lowest rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-caption uppercase tracking-wider text-neutral-muted">RISK STRATIFICATION</span>
                            <h2 class="font-title-md text-neutral-heading mt-0.5">Distribusi Risiko Ketahanan Stok</h2>
                        </div>
                        <span class="material-symbols-outlined text-neutral-muted text-lg">info</span>
                    </div>
                    <p class="font-body-sm text-neutral-muted mt-1 leading-snug">
                        Estimasi ketersediaan unit berdasarkan velocity real-time.
                    </p>
                </div>

                <div class="my-6 flex flex-col sm:flex-row items-center justify-center gap-6">
                    <!-- Inline Donut SVG Chart -->
                    <div class="relative w-44 h-44 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 140 140">
                            <!-- Background Track -->
                            <circle cx="70" cy="70" fill="transparent" r="54" stroke="#F1F5F9" stroke-width="16"></circle>
                            <!-- Safe Ring (Emerald 75%) -->
                            <circle cx="70" cy="70" fill="transparent" r="54" stroke="#10B981" stroke-dasharray="{{ $safeCirc }} 339.29" stroke-linecap="round" stroke-width="16"></circle>
                            <!-- Slow Moving Ring (Slate 20%) -->
                            <circle cx="70" cy="70" fill="transparent" r="54" stroke="#64748B" stroke-dasharray="{{ $slowCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            <!-- Warning Ring (Amber 5%) -->
                            <circle cx="70" cy="70" fill="transparent" r="54" stroke="#F59E0B" stroke-dasharray="{{ $warnCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc + $slowCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            <!-- Critical Ring (Red if any) -->
                            @if($critCirc > 0)
                                <circle cx="70" cy="70" fill="transparent" r="54" stroke="#EF4444" stroke-dasharray="{{ $critCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc + $slowCirc + $warnCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                            <span class="text-[10px] font-caption text-neutral-muted uppercase tracking-tight">KONDISI DOMINAN</span>
                            <span class="font-headline-sm text-neutral-heading text-2xl font-bold my-0.5 leading-none">
                                {{ $safePct }}%
                            </span>
                            <span class="text-xs font-semibold text-accent-emerald leading-tight">
                                Stok Aman
                            </span>
                        </div>
                    </div>

                    <!-- 4 Legend Cards Stacked -->
                    <div class="w-full sm:w-auto flex-1 space-y-2">
                        <div class="flex items-center justify-between p-2 px-3 rounded-xl bg-status-critical-subtle cursor-pointer transition hover:opacity-90" wire:click="setRiskFilter('CRITICAL')">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-status-critical shrink-0"></span>
                                <span class="text-xs font-medium text-neutral-heading">Kritis (&lt; 7 Hari)</span>
                            </div>
                            <span class="text-xs font-bold text-status-critical">{{ $critPct }}%</span>
                        </div>

                        <div class="flex items-center justify-between p-2 px-3 rounded-xl bg-status-warning-subtle cursor-pointer transition hover:opacity-90" wire:click="setRiskFilter('WARNING')">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-status-warning shrink-0"></span>
                                <span class="text-xs font-medium text-neutral-heading">Waspada (7–14 Hari)</span>
                            </div>
                            <span class="text-xs font-bold text-status-warning">{{ $warnPct }}%</span>
                        </div>

                        <div class="flex items-center justify-between p-2 px-3 rounded-xl bg-accent-emerald-subtle cursor-pointer transition hover:opacity-90" wire:click="setRiskFilter('SAFE')">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-accent-emerald shrink-0"></span>
                                <span class="text-xs font-medium text-neutral-heading">Aman (15–45 Hari)</span>
                            </div>
                            <span class="text-xs font-bold text-accent-emerald">{{ $safePct }}%</span>
                        </div>

                        <div class="flex items-center justify-between p-2 px-3 rounded-xl bg-surface-soft cursor-pointer transition hover:opacity-90" wire:click="setRiskFilter('SLOW_MOVING')">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-neutral-muted shrink-0"></span>
                                <span class="text-xs font-medium text-neutral-heading">Stok Mengendap (&gt;60)</span>
                            </div>
                            <span class="text-xs font-bold text-neutral-muted">{{ $slowPct }}%</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Batch kalkulasi: #WMA-2026-Q1</span>
                    <span class="text-neutral-heading font-medium">{{ $dist['slow_moving'] }} Varian butuh evaluasi</span>
                </div>
            </div>

            <!-- 2. Bar Chart Widget: Stok Fisik Tersedia vs Saran Order Restock per Kategori -->
            <div class="lg:col-span-7 bg-surface-container-lowest rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-xs flex flex-col justify-between">
                <div>
                    <div>
                        <span class="text-[11px] font-caption uppercase tracking-wider text-neutral-muted">CATEGORY INVENTORY VELOCITY</span>
                        <h2 class="font-title-md text-neutral-heading mt-0.5">Stok Fisik Tersedia vs Saran Order Restock per Kategori</h2>
                    </div>
                    <!-- Legend below header matching reference -->
                    <div class="flex items-center gap-4 text-xs font-medium mt-1.5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-xs bg-[#0d1c2f] dark:bg-white shrink-0"></span>
                            <span class="text-neutral-heading">Stok Fisik</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-xs bg-primary-container shrink-0"></span>
                            <span class="text-neutral-heading">Saran Restock</span>
                        </div>
                    </div>
                    <p class="font-body-sm text-neutral-muted mt-1.5">
                        Perbandingan inventaris saat ini terhadap proyeksi demand konveksi 30 hari ke depan.
                    </p>
                </div>

                <!-- Category Horizontal Bar Chart Visualization -->
                <div class="my-5 space-y-3.5">
                    @foreach($this->categoryStats as $cat)
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-neutral-heading">{{ $cat['name'] }}</span>
                                <span class="text-neutral-muted">
                                    Fisik: <strong class="text-neutral-heading font-bold">{{ $cat['stock'] }} pcs</strong>
                                    &bull; Saran: <strong class="text-primary font-bold">{{ $cat['restock'] }} pcs</strong>
                                </span>
                            </div>
                            <div class="h-4.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden flex items-center p-0.5">
                                <div class="h-full bg-[#0d1c2f] dark:bg-slate-200 rounded-full transition-all duration-300" style="width: {{ $cat['bar_width'] }}%;"></div>
                                @if($cat['restock_width'] > 0)
                                    <div class="h-full bg-primary-container rounded-full ml-1 transition-all duration-300" style="width: {{ $cat['restock_width'] }}%;"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Stok warehouse sentral: Sukoharjo & Jakarta</span>
                    <span class="text-neutral-heading font-medium">Level penyangga aman di seluruh lini</span>
                </div>
            </div>
        </div>

        <!-- MIDDLE ROW: 4 KPI Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <!-- 1. Restock Kritis -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-caption font-caption uppercase tracking-wider text-neutral-muted">Prioritas Kritis</span>
                    <div class="w-10 h-10 rounded-xl bg-status-critical-subtle text-status-critical flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">warning</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-headline-lg text-neutral-heading leading-tight">
                        {{ $this->metrics['critical_count'] ?? 0 }} <span class="font-headline-sm text-neutral-muted font-normal text-base">Varian</span>
                    </p>
                    <div class="mt-2 flex items-center gap-1.5 text-caption font-caption {{ ($this->metrics['critical_count'] ?? 0) === 0 ? 'text-accent-emerald font-semibold' : 'text-status-critical font-semibold' }}">
                        <span class="material-symbols-outlined text-sm">
                            {{ ($this->metrics['critical_count'] ?? 0) === 0 ? 'check_circle' : 'error' }}
                        </span>
                        <span>{{ ($this->metrics['critical_count'] ?? 0) === 0 ? 'Tidak ada SKU kekurangan stok' : 'Butuh restock darurat!' }}</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Label: Restock Kritis</span>
                    <span class="text-status-critical font-medium">Ambien: &le; 3 Hari</span>
                </div>
            </div>

            <!-- 2. Peringatan Waspada -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-caption font-caption uppercase tracking-wider text-neutral-muted">Stok Waspada</span>
                    <div class="w-10 h-10 rounded-xl bg-status-warning-subtle text-status-warning flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">schedule</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-headline-lg text-neutral-heading leading-tight">
                        {{ $this->metrics['warning_count'] ?? 0 }} <span class="font-headline-sm text-neutral-muted font-normal text-base">Varian</span>
                    </p>
                    <div class="mt-2 flex items-center gap-1.5 text-caption font-caption text-accent-emerald font-semibold">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        <span>Semua varian di atas batas aman</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Label: Peringatan Waspada</span>
                    <span class="text-status-warning font-medium">Ambien: 4&ndash;7 Hari</span>
                </div>
            </div>

            <!-- 3. Stok Mengendap -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-caption font-caption uppercase tracking-wider text-neutral-muted">Slow Moving</span>
                    <div class="w-10 h-10 rounded-xl bg-surface-soft text-neutral-muted flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">inventory</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-headline-lg text-neutral-heading leading-tight">
                        {{ $this->metrics['slow_moving_count'] ?? 0 }} <span class="font-headline-sm text-neutral-muted font-normal text-base">Varian</span>
                    </p>
                    <div class="mt-2 flex items-center gap-1.5 text-caption font-caption text-neutral-muted">
                        <span class="material-symbols-outlined text-sm text-status-warning">bolt</span>
                        <span>Rekomendasi promo bundling / diskon</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Label: Stok Mengendap</span>
                    <span class="text-neutral-heading font-medium">Ketahanan &gt; 60 Hari</span>
                </div>
            </div>

            <!-- 4. Saran Total Restock -->
            <div class="bg-surface-container-lowest rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-caption font-caption uppercase tracking-wider text-neutral-muted">Order Requisition</span>
                    <div class="w-10 h-10 rounded-xl bg-surface-soft text-neutral-muted flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">package_2</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-headline-lg text-neutral-heading leading-tight">
                        {{ number_format($this->metrics['total_restock_units'] ?? 0, 0, ',', '.') }} <span class="font-headline-sm text-neutral-muted font-normal text-base">Pcs</span>
                    </p>
                    <div class="mt-2 flex items-center gap-1.5 text-caption font-caption text-accent-emerald font-semibold">
                        <span class="material-symbols-outlined text-sm">verified</span>
                        <span>Kapasitas konveksi optimal</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-border-subtle flex items-center justify-between text-caption font-caption text-neutral-muted">
                    <span>Label: Saran Total Restock</span>
                    <span class="text-neutral-heading font-medium">Kalkulasi EOQ Batch Aktif</span>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW: Variant Analysis Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs overflow-hidden">
            <!-- Table Header Bar -->
            <div class="p-6 bg-surface-container-lowest border-b border-neutral-border-subtle">
                <!-- Top: Title & Description -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h3 class="font-title-md text-neutral-heading">Tabel Analisis Varian Inventaris</h3>
                            <span class="px-2.5 py-0.5 text-caption font-caption rounded-full bg-surface-soft text-neutral-heading font-semibold border border-gray-200/50 dark:border-gray-700/50">
                                {{ count($this->forecastData) }} SKU Terpilih
                            </span>
                        </div>
                        <p class="font-body-sm text-neutral-muted mt-1">
                            Data real-time stok fisik, kecepatan transaksi (Weighted Moving Average), estimasi ketahanan hari, dan kalkulasi order EOQ.
                        </p>
                    </div>
                </div>

                <!-- Bottom: Cohesive & Beautiful Filter Toolbar -->
                <div class="mt-4 pt-4 border-t border-neutral-border-subtle flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <!-- Left: Search Box & Category Dropdown in one line -->
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                        <!-- Search Box with pinned SVG icon & forced padding -->
                        <div class="relative w-64 shrink-0">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="search" 
                                placeholder="Cari varian, warna, SKU..." 
                                style="padding-left: 2.5rem !important; height: 38px !important;"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-surface-soft text-neutral-heading pr-3 focus:ring-2 focus:ring-primary-container focus:border-primary-container transition"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Selector Dropdown (Clean, single chevron, same height) -->
                        <div class="w-48 shrink-0">
                            <select 
                                wire:model.live="selectedCategory" 
                                style="height: 38px !important;"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-surface-soft text-neutral-heading px-3 focus:ring-2 focus:ring-primary-container focus:border-primary-container transition">
                                <option value="ALL">Semua Kategori</option>
                                @foreach($this->categories as $c)
                                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!empty($this->selectedRisk) || !empty($this->selectedCategory) || !empty($this->search))
                            <button 
                                type="button" 
                                wire:click="resetFilters" 
                                class="text-xs text-primary hover:underline font-medium px-2 py-1 transition cursor-pointer whitespace-nowrap">
                                Reset Filter
                            </button>
                        @endif
                    </div>

                    <!-- Right: Quick Risk Tabs Segmented Control -->
                    <div class="flex items-center rounded-xl bg-surface-soft p-1 border border-gray-200/50 dark:border-gray-700/50 shrink-0 self-start lg:self-auto">
                        <button 
                            type="button" 
                            wire:click="setRiskFilter(null)" 
                            class="px-3 py-1 rounded-lg text-xs transition cursor-pointer {{ empty($this->selectedRisk) ? 'bg-surface-container-lowest text-neutral-heading font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Semua
                        </button>
                        <button 
                            type="button" 
                            wire:click="setRiskFilter('SLOW_MOVING')" 
                            class="px-3 py-1 rounded-lg text-xs transition cursor-pointer {{ $this->selectedRisk === 'SLOW_MOVING' ? 'bg-surface-container-lowest text-neutral-heading font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Slow Moving ({{ $this->riskDistribution['slow_moving'] }})
                        </button>
                        <button 
                            type="button" 
                            wire:click="setRiskFilter('SAFE')" 
                            class="px-3 py-1 rounded-lg text-xs transition cursor-pointer {{ $this->selectedRisk === 'SAFE' ? 'bg-surface-container-lowest text-neutral-heading font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Aman ({{ $this->riskDistribution['safe'] }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- High Clarity Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-soft text-caption font-caption text-neutral-muted uppercase tracking-wider border-b border-neutral-border-subtle">
                            <th class="py-3.5 px-6 font-semibold" scope="col">Produk</th>
                            <th class="py-3.5 px-4 font-semibold" scope="col">Varian & SKU</th>
                            <th class="py-3.5 px-4 font-semibold text-right" scope="col">Stok Fisik</th>
                            <th class="py-3.5 px-4 font-semibold text-right" scope="col">Laju Penjualan (Velocity/WMA)</th>
                            <th class="py-3.5 px-4 font-semibold text-right" scope="col">Ketahanan Stok</th>
                            <th class="py-3.5 px-4 font-semibold text-center" scope="col">Tingkat Risiko</th>
                            <th class="py-3.5 px-4 font-semibold text-right" scope="col">Saran Restock (EOQ)</th>
                            <th class="py-3.5 px-6 font-semibold text-center" scope="col">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border-subtle text-body-sm font-body-sm">
                        @forelse($this->forecastData as $item)
                            <tr class="hover:bg-surface-soft/60 transition-colors">
                                <!-- Kolom Produk -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-11 h-14 object-cover rounded-lg shrink-0 shadow-xs border border-gray-100 dark:border-gray-800">
                                        @else
                                            <div class="w-11 h-14 bg-surface-soft rounded-lg shrink-0 flex items-center justify-center text-neutral-muted border border-gray-100 dark:border-gray-800">
                                                <span class="material-symbols-outlined text-xl">apparel</span>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-title-sm text-neutral-heading block leading-snug">{{ $item['product_name'] }}</span>
                                            <span class="text-caption font-caption text-neutral-muted">Kategori: {{ $item['category_name'] }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Varian & SKU -->
                                <td class="py-4 px-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-slate-800 inline-block"></span>
                                            <span class="font-semibold text-neutral-heading">{{ $item['color'] }} / Size {{ $item['size'] }}</span>
                                        </div>
                                        <span class="font-mono text-caption font-caption text-neutral-muted block">{{ $item['sku'] }}</span>
                                    </div>
                                </td>

                                <!-- Kolom Stok Fisik -->
                                <td class="py-4 px-4 text-right">
                                    <span class="font-headline-sm text-neutral-heading font-bold">{{ $item['current_stock'] }}</span>
                                    <span class="text-caption font-caption text-neutral-muted block">Pcs di Rak</span>
                                </td>

                                <!-- Kolom Laju Penjualan -->
                                <td class="py-4 px-4 text-right">
                                    <span class="font-semibold text-neutral-heading">~{{ $item['daily_demand'] }} Pcs/Hari</span>
                                    <span class="text-caption font-caption text-accent-emerald flex items-center justify-end gap-0.5">
                                        <span class="material-symbols-outlined text-xs">trending_up</span> 30 Hari: {{ $item['sales_30d'] }} pcs
                                    </span>
                                </td>

                                <!-- Kolom Ketahanan Stok -->
                                <td class="py-4 px-4 text-right">
                                    <span class="font-semibold text-neutral-heading">
                                        {{ $item['days_until_stockout'] > 90 ? '> 90 Hari' : $item['days_until_stockout'] . ' Hari' }}
                                    </span>
                                    <span class="text-caption font-caption text-neutral-muted block">
                                        {{ $item['current_stock'] <= 0 ? 'Habis' : 'Est. Terkendali' }}
                                    </span>
                                </td>

                                <!-- Kolom Tingkat Risiko -->
                                <td class="py-4 px-4 text-center">
                                    @if($item['risk'] === 'CRITICAL')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-status-critical-subtle text-status-critical text-caption font-caption font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-status-critical"></span>
                                            Kritis
                                        </span>
                                    @elseif($item['risk'] === 'WARNING')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-status-warning-subtle text-status-warning text-caption font-caption font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-status-warning"></span>
                                            Waspada
                                        </span>
                                    @elseif($item['risk'] === 'SLOW_MOVING')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-soft text-neutral-muted text-caption font-caption font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-muted"></span>
                                            Stok Mengendap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-accent-emerald-subtle text-accent-emerald text-caption font-caption font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-accent-emerald"></span>
                                            Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Saran Restock -->
                                <td class="py-4 px-4 text-right">
                                    <span class="font-headline-sm text-neutral-heading font-bold">
                                        {{ $item['recommended_restock'] }}
                                    </span>
                                    <span class="text-caption font-caption text-neutral-muted block">
                                        {{ $item['recommended_restock'] > 0 ? 'Pcs disarankan' : 'Tercukupi' }}
                                    </span>
                                </td>

                                <!-- Kolom Tindakan -->
                                <td class="py-4 px-6 text-center">
                                    <a 
                                        href="{{ $this->generatePoTemplate($item) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-accent-emerald hover:bg-emerald-600 text-white text-caption font-caption font-semibold shadow-xs transition active:scale-95 cursor-pointer">
                                        <span class="material-symbols-outlined text-sm">chat</span>
                                        <span>Buat PO WhatsApp Konveksi</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-neutral-muted">
                                    Tidak ada varian yang sesuai dengan filter atau kata kunci pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Ledger Summary Footer -->
            <div class="p-4 px-6 bg-surface-soft flex flex-col sm:flex-row items-center justify-between gap-3 text-body-sm font-body-sm text-neutral-muted border-t border-neutral-border-subtle">
                <div class="flex items-center gap-2">
                    <span>Menampilkan <strong>{{ count($this->forecastData) }}</strong> SKU aktif</span>
                    <span class="text-neutral-muted">&bull;</span>
                    <span class="text-caption font-caption">Formula: EOQ = &radic;((2 &times; D &times; S) / H)</span>
                </div>
                <div class="text-caption font-caption text-neutral-muted">
                    Lead time default: <strong>7 hari kerja</strong>
                </div>
            </div>
        </div>

        <!-- Quick Insights Footer Callouts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2 pb-6">
            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-gray-100 dark:border-gray-800 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-accent-emerald-subtle text-accent-emerald flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">health_and_safety</span>
                </div>
                <div>
                    <h4 class="font-title-sm text-neutral-heading">Zero Stockout Protection</h4>
                    <p class="font-body-sm text-neutral-muted mt-1">
                        Monitoring ketahanan real-time memastikan varian favorit tidak kehabisan stok saat transaksi melonjak.
                    </p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-gray-100 dark:border-gray-800 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary-subtle text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">campaign</span>
                </div>
                <div>
                    <h4 class="font-title-sm text-neutral-heading">Optimasi Varian Slow Moving</h4>
                    <p class="font-body-sm text-neutral-muted mt-1">
                        Varian dengan ketahanan tinggi disarankan masuk ke dalam promo flash sale atau program bundling mingguan.
                    </p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-gray-100 dark:border-gray-800 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-surface-soft text-neutral-heading flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">precision_manufacturing</span>
                </div>
                <div>
                    <h4 class="font-title-sm text-neutral-heading">Automasi WhatsApp Konveksi</h4>
                    <p class="font-body-sm text-neutral-muted mt-1">
                        Format PO terstandarisasi dengan data SKU, spek warna, ukuran, dan batch order siap dikirim sekali klik.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
