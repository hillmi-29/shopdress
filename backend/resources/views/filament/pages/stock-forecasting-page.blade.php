<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Banner Intro ML -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 p-6 text-white shadow-lg shadow-orange-500/10">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Machine Learning Engine Active
                    </div>
                    <h2 class="text-2xl font-extrabold tracking-tight">AI Inventory Demand Forecasting</h2>
                    <p class="text-orange-100 text-sm max-w-2xl">
                        Model memprediksi kecepatan penjualan harian (<em>Sales Velocity Rate</em>), menghitung sisa hari ketahanan stok (<em>Days to Stockout</em>), dan merekomendasikan jumlah produksi ke konveksi secara akurat.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-orange-600 font-bold text-sm shadow-md">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        Auto-Forecast Updated
                    </span>
                </div>
            </div>
        </div>

        <!-- 4 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Critical -->
            <div class="p-5 rounded-xl border border-red-200 dark:border-red-900/50 bg-red-50/50 dark:bg-red-950/20 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">Perlu Restock Kritis</span>
                    <span class="p-2 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-black text-red-700 dark:text-red-300">{{ $this->metrics['critical_count'] ?? 0 }} <span class="text-sm font-normal text-red-600">Varian</span></div>
                    <p class="text-xs text-red-600/80 mt-1">Stok habis atau tersisa &le; 3 hari</p>
                </div>
            </div>

            <!-- Card 2: Warning -->
            <div class="p-5 rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Peringatan Waspada</span>
                    <span class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-black text-amber-700 dark:text-amber-300">{{ $this->metrics['warning_count'] ?? 0 }} <span class="text-sm font-normal text-amber-600">Varian</span></div>
                    <p class="text-xs text-amber-600/80 mt-1">Stok diperkirakan habis dlm 4-7 hari</p>
                </div>
            </div>

            <!-- Card 3: Slow Moving -->
            <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Slow Moving Stock</span>
                    <span class="p-2 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-black text-slate-800 dark:text-slate-200">{{ $this->metrics['slow_moving_count'] ?? 0 }} <span class="text-sm font-normal text-slate-500">Varian</span></div>
                    <p class="text-xs text-slate-500 mt-1">Stok mengendap (disarankan promo/diskon)</p>
                </div>
            </div>

            <!-- Card 4: Recommended Restock Quantity -->
            <div class="p-5 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-950/20 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Rekomendasi Restock</span>
                    <span class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ number_format($this->metrics['total_restock_units'] ?? 0, 0, ',', '.') }} <span class="text-sm font-normal text-emerald-600">Pcs Baju</span></div>
                    <p class="text-xs text-emerald-600/80 mt-1">Total order produksi yang disarankan</p>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Filter Prediksi:</span>
                
                <!-- Filter Kategori -->
                <select wire:model.live="selectedCategory" class="text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white py-1.5 px-3 focus:ring-orange-500 focus:border-orange-500">
                    <option value="ALL">Semua Kategori</option>
                    @foreach($this->categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Filter Status Risiko -->
                <select wire:model.live="selectedRisk" class="text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white py-1.5 px-3 focus:ring-orange-500 focus:border-orange-500">
                    <option value="ALL">Semua Tingkat Risiko</option>
                    <option value="CRITICAL">🚨 Kritis (Habis &le; 3 Hari)</option>
                    <option value="WARNING">⚠️ Waspada (Habis 4-7 Hari)</option>
                    <option value="SAFE">🟢 Aman (> 7 Hari)</option>
                    <option value="SLOW_MOVING">💤 Slow Moving (Stok Mengendap)</option>
                </select>
            </div>

            <div class="text-xs text-gray-500 dark:text-gray-400">
                Menampilkan <strong>{{ count($this->forecastData) }}</strong> varian baju dianalisis
            </div>
        </div>

        <!-- Table Prediksi Stok & Restock -->
        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-800/60 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="py-3 px-4">Produk & Varian</th>
                            <th class="py-3 px-4">SKU / Ukuran / Warna</th>
                            <th class="py-3 px-4 text-center">Stok Saat Ini</th>
                            <th class="py-3 px-4 text-center">Kecepatan Demand (ML)</th>
                            <th class="py-3 px-4 text-center">Estimasi Habis</th>
                            <th class="py-3 px-4 text-center">Tingkat Risiko</th>
                            <th class="py-3 px-4 text-center">Saran Restock</th>
                            <th class="py-3 px-4 text-right">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($this->forecastData as $item)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40 transition">
                                <!-- Kolom Produk -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 text-xs">No Img</div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white leading-snug">{{ $item['product_name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $item['category_name'] }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Varian -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-block px-2 py-0.5 rounded font-mono text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">{{ $item['size'] }}</span>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $item['color'] }}</span>
                                    </div>
                                    <div class="text-[11px] font-mono text-gray-400 mt-0.5">{{ $item['sku'] }}</div>
                                </td>

                                <!-- Kolom Stok Saat Ini -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-base {{ $item['current_stock'] <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }}">
                                        {{ $item['current_stock'] }}
                                    </span>
                                    <span class="text-xs text-gray-400">pcs</span>
                                </td>

                                <!-- Kolom Daily Demand -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="font-semibold text-gray-900 dark:text-white">~{{ $item['daily_demand'] }} <span class="text-xs text-gray-400">pcs/hari</span></div>
                                    <div class="text-[11px] text-gray-400">Terjual 30h: {{ $item['sales_30d'] }} pcs</div>
                                </td>

                                <!-- Kolom Estimasi Habis -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['current_stock'] <= 0)
                                        <span class="font-bold text-xs text-red-600 bg-red-100 dark:bg-red-900/40 px-2 py-1 rounded-md">Habis Total</span>
                                    @elseif($item['days_until_stockout'] > 90)
                                        <span class="text-xs text-gray-500">&gt; 3 bulan</span>
                                    @else
                                        <span class="font-bold text-sm {{ $item['days_until_stockout'] <= 3 ? 'text-red-600' : ($item['days_until_stockout'] <= 7 ? 'text-amber-600' : 'text-green-600') }}">
                                            {{ $item['days_until_stockout'] }} Hari
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Tingkat Risiko -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['risk'] === 'CRITICAL')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                                            🚨 Kritis
                                        </span>
                                    @elseif($item['risk'] === 'WARNING')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            ⚠️ Waspada
                                        </span>
                                    @elseif($item['risk'] === 'SLOW_MOVING')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            💤 Slow Moving
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            🟢 Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Saran Restock -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['recommended_restock'] > 0)
                                        <span class="font-extrabold text-sm text-[#FF5D00] bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-800/50 px-2.5 py-1 rounded-lg">
                                            + {{ $item['recommended_restock'] }} pcs
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Cukup</span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="py-3.5 px-4 text-right">
                                    @if($item['recommended_restock'] > 0)
                                        <a href="{{ $this->generatePoTemplate($item) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                            PO Konveksi
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-400">Tidak ada varian baju yang sesuai dengan filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
