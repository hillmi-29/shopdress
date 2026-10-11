<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Executive Header Banner (No Emoji, Clean Typography) -->
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 text-xs font-semibold border border-orange-200 dark:border-orange-800/60">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                            Sistem Analisis Prediktif
                        </span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Pembaruan Real-time</span>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Prediksi Permintaan & Analisis Ketahanan Stok
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-3xl leading-relaxed">
                        Modul ini memanfaatkan algoritma <em>Weighted Moving Average</em> untuk mengalkulasi laju penjualan harian, memproyeksikan hari ketahanan stok sebelum habis (<em>Days to Stockout</em>), dan menghitung kuantitas restock optimal untuk konveksi.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Status Model: Aktif & Sinkron
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Metric KPI Cards (Clean, Professional, No Emojis) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Critical -->
            <div class="rounded-xl border border-red-200 dark:border-red-900/60 bg-white dark:bg-gray-900 p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-red-600 dark:text-red-400">Restock Kritis</span>
                    <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-950/60 flex items-center justify-center text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-950 dark:text-white">
                        {{ $this->metrics['critical_count'] ?? 0 }}
                        <span class="text-sm font-normal text-gray-500">Varian</span>
                    </div>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
                        <span>Habis atau tersisa &le; 3 hari</span>
                    </p>
                </div>
            </div>

            <!-- Card 2: Warning -->
            <div class="rounded-xl border border-amber-200 dark:border-amber-900/60 bg-white dark:bg-gray-900 p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Peringatan Waspada</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-950 dark:text-white">
                        {{ $this->metrics['warning_count'] ?? 0 }}
                        <span class="text-sm font-normal text-gray-500">Varian</span>
                    </div>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1.5">
                        Habis dalam 4 sampai 7 hari
                    </p>
                </div>
            </div>

            <!-- Card 3: Slow Moving -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-gray-900 p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Stok Mengendap</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-950 dark:text-white">
                        {{ $this->metrics['slow_moving_count'] ?? 0 }}
                        <span class="text-sm font-normal text-gray-500">Varian</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                        Rekomendasi diskon atau bundling
                    </p>
                </div>
            </div>

            <!-- Card 4: Recommended Restock Quantity -->
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-900/60 bg-white dark:bg-gray-900 p-5 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Saran Total Restock</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-950 dark:text-white">
                        {{ number_format($this->metrics['total_restock_units'] ?? 0, 0, ',', '.') }}
                        <span class="text-sm font-normal text-gray-500">Pcs</span>
                    </div>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1.5">
                        Target kebutuhan produksi 10 hari
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari produk, warna, SKU..." 
                        class="w-full text-xs rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white pl-8 pr-3 py-2 focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <select wire:model.live="selectedCategory" class="text-xs rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white py-2 px-3 focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                    <option value="ALL">Semua Kategori</option>
                    @foreach($this->categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Risk Filter -->
                <select wire:model.live="selectedRisk" class="text-xs rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white py-2 px-3 focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                    <option value="ALL">Semua Tingkat Risiko</option>
                    <option value="CRITICAL">Tingkat Kritis (&le; 3 Hari)</option>
                    <option value="WARNING">Tingkat Waspada (4 - 7 Hari)</option>
                    <option value="SAFE">Tingkat Aman (&gt; 7 Hari)</option>
                    <option value="SLOW_MOVING">Stok Mengendap (Slow Moving)</option>
                </select>

                @if(!empty($selectedCategory) || !empty($selectedRisk) || !empty($search))
                    <button wire:click="resetFilters" class="text-xs text-orange-600 hover:text-orange-700 dark:text-orange-400 font-medium underline cursor-pointer">
                        Reset Filter
                    </button>
                @endif
            </div>

            <div class="text-xs text-gray-400">
                Total Varian: <span class="font-bold text-gray-700 dark:text-gray-300">{{ count($this->forecastData) }}</span>
            </div>
        </div>

        <!-- Clean Professional Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/80 dark:bg-gray-800/50 text-[11px] uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800 tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Produk</th>
                            <th class="py-3 px-4">Varian & SKU</th>
                            <th class="py-3 px-4 text-center">Stok Fisik</th>
                            <th class="py-3 px-4 text-center">Laju Penjualan</th>
                            <th class="py-3 px-4 text-center">Ketahanan Stok</th>
                            <th class="py-3 px-4 text-center">Tingkat Risiko</th>
                            <th class="py-3 px-4 text-center">Saran Restock</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @forelse($this->forecastData as $item)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition">
                                <!-- Kolom Produk -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-9 h-9 rounded-lg object-cover border border-gray-200 dark:border-gray-700 shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $item['product_name'] }}</div>
                                            <span class="inline-block text-[10px] text-gray-400 uppercase tracking-wider">{{ $item['category_name'] }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Varian -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.5 rounded font-mono text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700">{{ $item['size'] }}</span>
                                        <span class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ $item['color'] }}</span>
                                    </div>
                                    <div class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $item['sku'] }}</div>
                                </td>

                                <!-- Kolom Stok Fisik -->
                                <td class="py-3 px-4 text-center">
                                    <span class="font-bold text-sm {{ $item['current_stock'] <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $item['current_stock'] }}
                                    </span>
                                    <span class="text-[11px] text-gray-400">pcs</span>
                                </td>

                                <!-- Kolom Laju Penjualan -->
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1 font-semibold text-gray-900 dark:text-white">
                                        <span>~{{ $item['daily_demand'] }}</span>
                                        <span class="text-[10px] font-normal text-gray-400">pcs/hari</span>

                                        @if($item['trend'] === 'rising')
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        @elseif($item['trend'] === 'falling')
                                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-gray-400">30 Hari: {{ $item['sales_30d'] }} pcs</div>
                                </td>

                                <!-- Kolom Ketahanan Stok -->
                                <td class="py-3 px-4 text-center">
                                    @if($item['current_stock'] <= 0)
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/60 border border-red-200 dark:border-red-900">Habis</span>
                                    @elseif($item['days_until_stockout'] > 90)
                                        <span class="text-gray-400 text-xs">&gt; 90 Hari</span>
                                    @else
                                        <span class="font-bold text-xs {{ $item['days_until_stockout'] <= 3 ? 'text-red-600 dark:text-red-400' : ($item['days_until_stockout'] <= 7 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                            {{ $item['days_until_stockout'] }} Hari
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Tingkat Risiko -->
                                <td class="py-3 px-4 text-center">
                                    @if($item['risk'] === 'CRITICAL')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-900/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                            Kritis
                                        </span>
                                    @elseif($item['risk'] === 'WARNING')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Waspada
                                        </span>
                                    @elseif($item['risk'] === 'SLOW_MOVING')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Slow Moving
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Saran Restock -->
                                <td class="py-3 px-4 text-center">
                                    @if($item['recommended_restock'] > 0)
                                        <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-800/60">
                                            +{{ $item['recommended_restock'] }} pcs
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-[11px]">-</span>
                                    @endif
                                </td>

                                <!-- Kolom Tindakan -->
                                <td class="py-3 px-4 text-right">
                                    @if($item['recommended_restock'] > 0)
                                        <a href="{{ $this->generatePoTemplate($item) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold text-white bg-emerald-600 hover:bg-emerald-500 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            PO Konveksi
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Tercukupi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p class="text-xs">Tidak ditemukan varian yang sesuai dengan filter pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
