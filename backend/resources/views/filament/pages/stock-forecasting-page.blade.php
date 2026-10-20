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
        .font-caption { font-family: 'Inter', sans-serif; font-size: 11px; line-height: 16px; font-weight: 500; }

        .bg-surface-container-lowest { background-color: #ffffff; }
        .dark .bg-surface-container-lowest { background-color: #111827; }
        .bg-surface-soft { background-color: #F8FAFC; }
        .dark .bg-surface-soft { background-color: #1f2937; }
        .text-neutral-heading { color: #0F172A; }
        .dark .text-neutral-heading { color: #f9fafb; }
        .text-neutral-muted { color: #64748B; }
        .dark .text-neutral-muted { color: #9ca3af; }

        .bg-primary-container { background-color: #6D28D9; }
        .bg-primary-hover { background-color: #5B21B6; }
        .text-primary { color: #6D28D9; }
        .bg-primary-subtle { background-color: #F5F3FF; }
        .dark .bg-primary-subtle { background-color: rgba(124, 58, 237, 0.15); }
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

        /* Utility yang tidak ikut ter-bundle CSS Filament. */
        .rounded-2xl { border-radius: 1rem; }
        .shadow-xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
        .rounded-xs { border-radius: 2px; }
        .dark .dark\:border-gray-800 { border-color: #1F2937; }
        .dark .dark\:border-gray-700\/50 { border-color: rgba(55, 65, 81, 0.5); }
        .bg-status-slow-subtle { background-color: #E2E8F0; }
        .dark .bg-status-slow-subtle { background-color: rgba(100, 116, 139, 0.22); }
        .dark .text-primary { color: #A78BFA; }
        .uppercase { text-transform: uppercase; }
        .tracking-wider { letter-spacing: 0.05em; }
        .sd-donut { width: 128px; height: 128px; flex-shrink: 0; }
        .sd-donut svg { width: 100%; height: 100%; }
        .sd-bar-track { height: 10px; border-radius: 9999px; background-color: #F1F5F9; overflow: hidden; }
        .dark .sd-bar-track { background-color: #374151; }
        .sd-bar-fill { height: 100%; border-radius: 9999px; background-color: #10B981; }
        @media (min-width: 768px) {
            .sd-product-table { min-width: 760px; }
            .sd-variant-table { min-width: 560px; }
        }
        .sd-table-card { min-width: 0; width: 100%; }
        .mt-1\.5 { margin-top: 0.375rem; }
        .mt-4 { margin-top: 1rem; }
        .gap-2\.5 { gap: 0.625rem; }
        .hover\:bg-surface-container-lowest:hover { background-color: #ffffff; }
        .dark .hover\:bg-surface-container-lowest:hover { background-color: #111827; }
        .btn-po-emerald:hover { background-color: #059669; }
        .ring-track { stroke: #F1F5F9; }
        .dark .ring-track { stroke: #374151; }
        .focus\:ring-primary-container:focus { --tw-ring-color: #6D28D9; }
        .focus\:border-primary-container:focus { border-color: #6D28D9; }
        .hover\:bg-surface-soft\/60:hover { background-color: rgba(248, 250, 252, 0.6); }
        .dark .hover\:bg-surface-soft\/60:hover { background-color: rgba(31, 41, 55, 0.6); }
        .divide-neutral-border-subtle > :not([hidden]) ~ :not([hidden]) { border-color: #F1F5F9; }
        .dark .divide-neutral-border-subtle > :not([hidden]) ~ :not([hidden]) { border-color: #374151; }
        .text-\[11px\] { font-size: 11px; line-height: 16px; }
        .sd-num { font-variant-numeric: tabular-nums; }
        .sd-chevron-open { transform: rotate(180deg); }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0; }
    </style>

    @php
        $dist = $this->riskDistribution;
        $categoryStats = $this->categoryStats;
        $groups = collect($this->forecastData)->groupBy('product_id');
        $filteredVariants = $groups->sum(fn ($items) => $items->count());
        $riskOrder = ['CRITICAL' => 3, 'WARNING' => 2, 'SLOW_MOVING' => 1, 'SAFE' => 0];
        $statusLabels = [
            'CRITICAL' => 'Kritis',
            'WARNING' => 'Menipis',
            'SLOW_MOVING' => 'Menumpuk',
            'SAFE' => 'Aman',
        ];
        $statusChip = [
            'CRITICAL' => 'bg-status-critical-subtle text-status-critical',
            'WARNING' => 'bg-status-warning-subtle text-status-warning',
            'SLOW_MOVING' => 'bg-status-slow-subtle text-neutral-muted',
            'SAFE' => 'bg-accent-emerald-subtle text-accent-emerald',
        ];
        $statusDot = [
            'CRITICAL' => 'bg-status-critical',
            'WARNING' => 'bg-status-warning',
            'SLOW_MOVING' => 'bg-neutral-muted',
            'SAFE' => 'bg-accent-emerald',
        ];
        $totalStock = (int) $categoryStats->sum('stock');
        $totalRestock = (int) $categoryStats->sum('restock');
        $formatPcs = fn ($value) => number_format((float) $value, 0, ',', '.');
    @endphp

    <div class="space-y-6">
        <!-- RINGKASAN VISUAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Distribusi status -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-xs flex flex-col">
                <h2 class="font-title-md text-neutral-heading">Status Stok</h2>

                @php
                    $totalForRing = max(1, (int) $dist['total']);
                    $safeCirc = round((339.29 * $dist['safe']) / $totalForRing, 2);
                    $slowCirc = round((339.29 * $dist['slow_moving']) / $totalForRing, 2);
                    $warnCirc = round((339.29 * $dist['warning']) / $totalForRing, 2);
                    $critCirc = round((339.29 * $dist['critical']) / $totalForRing, 2);
                @endphp

                <div class="my-auto py-4 flex flex-col sm:flex-row sm:items-center gap-5">
                    <div class="relative sd-donut mx-auto sm:mx-0">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 140 140" role="img" aria-label="Distribusi status stok">
                            <circle cx="70" cy="70" fill="transparent" r="54" class="ring-track" stroke-width="16"></circle>
                            @if($safeCirc > 0)
                                <circle cx="70" cy="70" fill="transparent" r="54" stroke="#10B981" stroke-dasharray="{{ $safeCirc }} 339.29" stroke-linecap="round" stroke-width="16"></circle>
                            @endif
                            @if($slowCirc > 0)
                                <circle cx="70" cy="70" fill="transparent" r="54" stroke="#64748B" stroke-dasharray="{{ $slowCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            @endif
                            @if($warnCirc > 0)
                                <circle cx="70" cy="70" fill="transparent" r="54" stroke="#F59E0B" stroke-dasharray="{{ $warnCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc + $slowCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            @endif
                            @if($critCirc > 0)
                                <circle cx="70" cy="70" fill="transparent" r="54" stroke="#EF4444" stroke-dasharray="{{ $critCirc }} 339.29" stroke-dashoffset="-{{ $safeCirc + $slowCirc + $warnCirc }}" stroke-linecap="round" stroke-width="16"></circle>
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="font-headline-lg text-neutral-heading leading-none">{{ $dist['total'] }}</span>
                            <span class="text-xs text-neutral-muted mt-1">varian</span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 space-y-1">
                        @foreach([
                            ['key' => 'CRITICAL', 'label' => 'Kritis', 'count' => $dist['critical'], 'title' => 'Stok habis atau di bawah stok pengaman'],
                            ['key' => 'WARNING', 'label' => 'Menipis', 'count' => $dist['warning'], 'title' => 'Stok sudah mencapai titik restock'],
                            ['key' => 'SLOW_MOVING', 'label' => 'Menumpuk', 'count' => $dist['slow_moving'], 'title' => 'Stok cukup untuk lebih dari 60 hari'],
                            ['key' => 'SAFE', 'label' => 'Aman', 'count' => $dist['safe'], 'title' => 'Stok di atas titik restock'],
                        ] as $status)
                            <div class="flex items-center justify-between rounded-lg px-2.5 py-2" title="{{ $status['title'] }}">
                                <span class="flex items-center gap-2.5 text-sm text-neutral-heading">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $statusDot[$status['key']] }}"></span>
                                    {{ $status['label'] }}
                                </span>
                                <span class="text-sm font-semibold text-neutral-heading sd-num">{{ $status['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Stok per kategori -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-xs flex flex-col">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-title-md text-neutral-heading">Stok per Kategori</h2>
                    <span class="text-xs text-neutral-muted">{{ $formatPcs($totalStock) }} pcs</span>
                </div>

                <div class="mt-5 flex-1 space-y-4">
                    @foreach($categoryStats as $cat)
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="min-w-0 truncate font-medium text-neutral-heading" title="{{ $cat['name'] }}">{{ $cat['name'] }}</span>
                                <span class="shrink-0 text-neutral-muted">
                                    <strong class="font-semibold text-neutral-heading sd-num">{{ $formatPcs($cat['stock']) }}</strong> pcs
                                    @if($cat['restock'] > 0)
                                        <span class="ml-1.5 font-semibold text-primary">+{{ $formatPcs($cat['restock']) }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="mt-1.5 sd-bar-track">
                                <div class="sd-bar-fill" style="width: {{ $cat['bar_width'] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($totalRestock > 0)
                    <p class="mt-4 text-xs font-medium text-primary">Saran restock: {{ $formatPcs($totalRestock) }} pcs</p>
                @endif
            </div>
        </div>

        <!-- DAFTAR PRODUK -->
        <div class="bg-surface-container-lowest rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs overflow-hidden sd-table-card" x-data="{ cara: false, buka: null }">
            <div class="p-6 border-b border-neutral-border-subtle">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="font-title-md text-neutral-heading">Daftar Produk</h2>
                        <span class="px-2.5 py-0.5 text-caption font-caption rounded-full bg-surface-soft text-neutral-muted font-medium">
                            {{ $groups->count() }} produk &middot; {{ $filteredVariants }} varian
                        </span>
                    </div>

                    <button
                        type="button"
                        x-on:click="cara = !cara"
                        x-bind:aria-expanded="cara.toString()"
                        aria-controls="cara-hitung"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-surface-soft text-xs font-medium text-neutral-heading transition hover:bg-surface-container-lowest cursor-pointer self-start sm:self-auto shrink-0">
                        <span class="material-symbols-outlined text-base text-primary">calculate</span>
                        <span>Cara hitung</span>
                        <span class="material-symbols-outlined text-sm transition-transform" x-bind:class="cara ? 'sd-chevron-open' : ''">expand_more</span>
                    </button>
                </div>

                <!-- Filter -->
                <div class="mt-4 flex flex-col xl:flex-row xl:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="relative shrink-0" style="width: 280px;">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cari produk/SKU..."
                                style="padding-left: 2.5rem !important; height: 38px !important;"
                                class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-surface-soft text-neutral-heading pr-3 focus:ring-2 focus:ring-primary-container focus:border-primary-container transition"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="w-48 shrink-0">
                            <select
                                wire:model.live="selectedCategory"
                                style="height: 38px !important;"
                                class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-surface-soft text-neutral-heading px-3 focus:ring-2 focus:ring-primary-container focus:border-primary-container transition">
                                <option value="ALL">Semua kategori</option>
                                @foreach($this->categories as $c)
                                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($this->selectedRisk || ($this->selectedCategory && $this->selectedCategory !== 'ALL') || $this->search)
                            <button
                                type="button"
                                wire:click="resetFilters"
                                class="text-sm text-primary hover:underline font-medium px-1.5 py-1 transition cursor-pointer whitespace-nowrap">
                                Reset
                            </button>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center rounded-xl bg-surface-soft p-1 border border-gray-200/50 dark:border-gray-700/50 shrink-0 self-start xl:self-auto">
                        <button
                            type="button"
                            wire:click="setRiskFilter(null)"
                            class="px-3 py-1 rounded-lg text-sm transition cursor-pointer {{ empty($this->selectedRisk) ? 'bg-surface-container-lowest text-neutral-heading font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Semua
                        </button>
                        <button
                            type="button"
                            wire:click="setRiskFilter('CRITICAL')"
                            class="px-3 py-1 rounded-lg text-sm transition cursor-pointer {{ $this->selectedRisk === 'CRITICAL' ? 'bg-surface-container-lowest text-status-critical font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Kritis
                        </button>
                        <button
                            type="button"
                            wire:click="setRiskFilter('WARNING')"
                            class="px-3 py-1 rounded-lg text-sm transition cursor-pointer {{ $this->selectedRisk === 'WARNING' ? 'bg-surface-container-lowest text-status-warning font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Menipis
                        </button>
                        <button
                            type="button"
                            wire:click="setRiskFilter('SLOW_MOVING')"
                            class="px-3 py-1 rounded-lg text-sm transition cursor-pointer {{ $this->selectedRisk === 'SLOW_MOVING' ? 'bg-surface-container-lowest text-neutral-heading font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Menumpuk
                        </button>
                        <button
                            type="button"
                            wire:click="setRiskFilter('SAFE')"
                            class="px-3 py-1 rounded-lg text-sm transition cursor-pointer {{ $this->selectedRisk === 'SAFE' ? 'bg-surface-container-lowest text-accent-emerald font-semibold shadow-xs' : 'text-neutral-muted hover:text-neutral-heading' }}">
                            Aman
                        </button>
                    </div>
                </div>

                <!-- Cara hitung -->
                <div x-show="cara" x-cloak id="cara-hitung" class="mt-4 rounded-xl border border-neutral-border-subtle bg-surface-soft p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div class="flex gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-primary-subtle text-primary text-[11px] font-bold flex items-center justify-center shrink-0">1</span>
                            <div>
                                <p class="text-xs font-semibold text-neutral-heading">Rata-rata jual</p>
                                <p class="text-caption font-caption text-neutral-muted mt-1">70% penjualan 7 hari + 30% penjualan 30 hari terakhir.</p>
                            </div>
                        </div>
                        <div class="flex gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-primary-subtle text-primary text-[11px] font-bold flex items-center justify-center shrink-0">2</span>
                            <div>
                                <p class="text-xs font-semibold text-neutral-heading">Stok pengaman</p>
                                <p class="text-caption font-caption text-neutral-muted mt-1">Cadangan untuk lonjakan permintaan selama 7 hari.</p>
                            </div>
                        </div>
                        <div class="flex gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-primary-subtle text-primary text-[11px] font-bold flex items-center justify-center shrink-0">3</span>
                            <div>
                                <p class="text-xs font-semibold text-neutral-heading">Titik restock</p>
                                <p class="text-caption font-caption text-neutral-muted mt-1">Batas stok minimum sebelum perlu restock.</p>
                            </div>
                        </div>
                        <div class="flex gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-primary-subtle text-primary text-[11px] font-bold flex items-center justify-center shrink-0">4</span>
                            <div>
                                <p class="text-xs font-semibold text-neutral-heading">Saran restock</p>
                                <p class="text-caption font-caption text-neutral-muted mt-1">Selisih titik restock dan stok, minimum 12 pcs.</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 pt-3 border-t border-neutral-border-subtle text-caption font-caption text-neutral-muted leading-relaxed">
                        Kritis: stok di bawah stok pengaman &middot;
                        Menipis: stok mencapai titik restock &middot;
                        Menumpuk: stok cukup lebih dari 60 hari.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto" style="max-width: 100%;">
                <table class="w-full text-left sd-product-table">
                    <thead>
                        <tr class="bg-surface-soft text-caption font-caption text-neutral-muted uppercase tracking-wider border-b border-neutral-border-subtle">
                            <th class="py-3 px-6 font-semibold" scope="col">Produk</th>
                            <th class="py-3 px-4 font-semibold text-right" scope="col">Stok</th>
                            <th class="py-3 px-4 font-semibold text-center" scope="col">Status</th>
                            <th class="py-3 px-4 font-semibold text-right" scope="col">Saran Restock</th>
                            <th class="py-3 px-6 font-semibold text-right" scope="col"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border-subtle text-body-sm font-body-sm">
                        @forelse($groups as $productId => $variants)
                            @php
                                $first = $variants->first();
                                $worst = $variants->sortByDesc(fn ($v) => $riskOrder[$v['risk']] ?? 0)->first();
                                $groupStock = (int) $variants->sum('current_stock');
                                $groupRestock = (int) $variants->sum('recommended_restock');
                                $variantCount = $variants->count();
                            @endphp
                            <tr class="hover:bg-surface-soft/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @if($first['image_url'])
                                            <img src="{{ $first['image_url'] }}" alt="" class="w-11 h-14 rounded-lg object-cover border border-gray-100 dark:border-gray-800 shrink-0">
                                        @else
                                            <div class="w-11 h-14 rounded-lg bg-surface-soft border border-gray-100 dark:border-gray-800 flex items-center justify-center text-neutral-muted shrink-0">
                                                <span class="material-symbols-outlined text-xl">apparel</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-title-sm text-neutral-heading leading-snug">{{ $first['product_name'] }}</p>
                                            <p class="text-caption font-caption text-neutral-muted">
                                                {{ $first['category_name'] }} &middot; {{ $variantCount }} varian
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-right" title="Total stok varian yang cocok dengan filter">
                                    <span class="font-title-sm font-semibold text-neutral-heading sd-num">{{ $formatPcs($groupStock) }}</span>
                                    <span class="text-caption font-caption text-neutral-muted"> pcs</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full whitespace-nowrap {{ $statusChip[$worst['risk']] }} text-caption font-caption font-semibold" title="Status varian terburuk di produk ini">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$worst['risk']] }}"></span>
                                        {{ $statusLabels[$worst['risk']] }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-right">
                                    @if($groupRestock > 0)
                                        <span class="font-title-sm font-semibold text-primary sd-num">{{ $formatPcs($groupRestock) }}</span>
                                        <span class="text-caption font-caption text-neutral-muted"> pcs</span>
                                    @else
                                        <span class="text-neutral-muted" title="Tidak ada varian yang perlu restock.">&mdash;</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <button
                                        type="button"
                                        x-on:click="buka = buka === {{ $productId }} ? null : {{ $productId }}"
                                        x-bind:aria-expanded="(buka === {{ $productId }}).toString()"
                                        aria-controls="produk-{{ $productId }}"
                                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-primary hover:bg-surface-soft/60 transition cursor-pointer whitespace-nowrap">
                                        <span x-text="buka === {{ $productId }} ? 'Tutup' : 'Lihat varian'"></span>
                                        <span class="material-symbols-outlined text-base transition-transform" x-bind:class="buka === {{ $productId }} ? 'sd-chevron-open' : ''">expand_more</span>
                                    </button>
                                </td>
                            </tr>

                            <!-- Detail varian -->
                            <tr id="produk-{{ $productId }}" x-show="buka === {{ $productId }}" x-cloak>
                                <td colspan="5" class="px-6 pb-5 pt-1">
                                    <div class="rounded-xl border border-neutral-border-subtle bg-surface-soft p-4">
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left sd-variant-table">
                                                <thead>
                                                    <tr class="text-[11px] font-caption uppercase tracking-wider text-neutral-muted">
                                                        <th class="pb-2 pr-3 font-semibold" scope="col">Varian</th>
                                                        <th class="pb-2 px-3 font-semibold text-right" scope="col">Stok</th>
                                                        <th class="pb-2 px-3 font-semibold text-center" scope="col">Status</th>
                                                        <th class="pb-2 px-3 font-semibold text-right" scope="col">Restock</th>
                                                        <th class="pb-2 pl-3 font-semibold text-right" scope="col">PO</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-border-subtle">
                                                    @foreach($variants as $v)
                                                        <tr>
                                                            <td class="py-2.5 pr-3">
                                                                <p class="text-xs font-semibold text-neutral-heading">{{ $v['color'] }} &middot; {{ $v['size'] }}</p>
                                                                <p class="text-[11px] font-mono text-neutral-muted">{{ $v['sku'] }}</p>
                                                            </td>
                                                            <td class="py-2.5 px-3 text-right">
                                                                <span class="text-xs font-semibold text-neutral-heading sd-num">{{ $formatPcs($v['current_stock']) }} pcs</span>
                                                                <span class="block text-[11px] text-neutral-muted">
                                                                    @if($v['current_stock'] <= 0)
                                                                        Habis
                                                                    @elseif($v['sales_30d'] <= 0)
                                                                        Belum ada penjualan
                                                                    @elseif($v['days_until_stockout'] > 90)
                                                                        Cukup &gt; 90 hari
                                                                    @else
                                                                        Cukup {{ $v['days_until_stockout'] }} hari
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="py-2.5 px-3 text-center">
                                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full whitespace-nowrap {{ $statusChip[$v['risk']] }} text-[11px] font-medium">
                                                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$v['risk']] }}"></span>
                                                                    {{ $statusLabels[$v['risk']] }}
                                                                </span>
                                                            </td>
                                                            <td class="py-2.5 px-3 text-right">
                                                                @if($v['recommended_restock'] > 0)
                                                                    <span class="text-xs font-semibold text-neutral-heading sd-num">{{ $formatPcs($v['recommended_restock']) }} pcs</span>
                                                                @else
                                                                    <span class="text-neutral-muted" title="{{ $v['sales_30d'] <= 0 ? 'Belum ada penjualan 30 hari; restock otomatis belum disarankan.' : 'Stok masih di atas titik restock.' }}">&mdash;</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-2.5 pl-3 text-right">
                                                                @if($v['recommended_restock'] > 0)
                                                                    <a
                                                                        href="{{ $this->generatePoTemplate($v) }}"
                                                                        target="_blank"
                                                                        rel="noopener"
                                                                        title="Buat PO via WhatsApp"
                                                                        aria-label="Buat PO via WhatsApp untuk {{ $v['color'] }} ukuran {{ $v['size'] }}"
                                                                        class="inline-flex items-center gap-1 rounded-lg bg-accent-emerald btn-po-emerald px-2.5 py-1.5 text-[11px] font-semibold text-white transition active:scale-95">
                                                                        <span class="material-symbols-outlined text-sm">chat</span>
                                                                        PO
                                                                    </a>
                                                                @else
                                                                    <span class="text-neutral-muted" title="PO tersedia setelah ada saran restock">&mdash;</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-neutral-muted">
                                    Tidak ada produk yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
