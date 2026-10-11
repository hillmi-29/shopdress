<x-filament-panels::page>
    <div class="space-y-5">
        <!-- 4 Metric KPI Cards Matching User Reference -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Restock Kritis -->
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm flex items-center gap-3.5 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950/60 flex items-center justify-center text-red-500 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Restock Kritis</div>
                    <div class="text-2xl font-bold text-gray-950 dark:text-white leading-tight mt-0.5">
                        {{ $this->metrics['critical_count'] ?? 0 }} <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Varian</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Peringatan Waspada -->
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm flex items-center gap-3.5 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-amber-500 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Peringatan Waspada</div>
                    <div class="text-2xl font-bold text-gray-950 dark:text-white leading-tight mt-0.5">
                        {{ $this->metrics['warning_count'] ?? 0 }} <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Varian</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Stok Mengendap -->
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm flex items-center gap-3.5 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Stok Mengendap</div>
                    <div class="text-2xl font-bold text-gray-950 dark:text-white leading-tight mt-0.5">
                        {{ $this->metrics['slow_moving_count'] ?? 0 }} <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Varian</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Saran Total Restock -->
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm flex items-center gap-3.5 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Saran Total Restock</div>
                    <div class="text-2xl font-bold text-gray-950 dark:text-white leading-tight mt-0.5">
                        {{ number_format($this->metrics['total_restock_units'] ?? 0, 0, ',', '.') }} <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Pcs</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Professional Table Matching User Reference -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-white dark:bg-gray-900 text-[11px] font-semibold text-gray-900 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="py-3 px-4">Produk</th>
                            <th class="py-3 px-4">Varian & SKU</th>
                            <th class="py-3 px-4">Stok Fisik</th>
                            <th class="py-3 px-4">Laju Penjualan</th>
                            <th class="py-3 px-4 text-center">Ketahanan Stok</th>
                            <th class="py-3 px-4 text-center">Tingkat Risiko</th>
                            <th class="py-3 px-4 text-center">Saran Restock</th>
                            <th class="py-3 px-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                        @forelse($this->forecastData as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                                <!-- Kolom Produk -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 dark:border-gray-800 shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-950 dark:text-white leading-tight text-xs">{{ $item['product_name'] }}</div>
                                            <div class="text-[11px] text-gray-500 mt-0.5">{{ $item['category_name'] }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Varian & SKU -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">{{ $item['size'] }}</span>
                                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $item['color'] }}</span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 font-normal">{{ $item['sku'] }}</div>
                                </td>

                                <!-- Kolom Stok Fisik -->
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-xs text-gray-950 dark:text-white">
                                        {{ $item['current_stock'] }}
                                    </span>
                                    <span class="text-xs text-gray-500">pcs</span>
                                </td>

                                <!-- Kolom Laju Penjualan -->
                                <td class="py-3.5 px-4">
                                    <div class="text-xs text-gray-800 dark:text-gray-200">
                                        ~{{ $item['daily_demand'] }} pcs/hari
                                    </div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">30 Hari: {{ $item['sales_30d'] }} pcs</div>
                                </td>

                                <!-- Kolom Ketahanan Stok -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['current_stock'] <= 0)
                                        <span class="font-bold text-xs text-red-600">Habis</span>
                                    @elseif($item['days_until_stockout'] > 90)
                                        <span class="text-gray-700 dark:text-gray-300 text-xs">&gt; 90 Hari</span>
                                    @else
                                        <span class="font-bold text-xs {{ $item['days_until_stockout'] <= 3 ? 'text-red-600' : ($item['days_until_stockout'] <= 7 ? 'text-amber-600' : 'text-emerald-600') }}">
                                            {{ $item['days_until_stockout'] }} Hari
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Tingkat Risiko -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['risk'] === 'CRITICAL')
                                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-700 dark:bg-red-950/60 dark:text-red-300">
                                            Kritis
                                        </span>
                                    @elseif($item['risk'] === 'WARNING')
                                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                                            Waspada
                                        </span>
                                    @elseif($item['risk'] === 'SLOW_MOVING')
                                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            Slow Moving
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Saran Restock -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['recommended_restock'] > 0)
                                        <span class="font-bold text-xs text-orange-600 dark:text-orange-400">
                                            +{{ $item['recommended_restock'] }} pcs
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-xs font-medium">-</span>
                                    @endif
                                </td>

                                <!-- Kolom Tindakan -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['recommended_restock'] > 0)
                                        <a href="{{ $this->generatePoTemplate($item) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-medium text-white bg-emerald-600 hover:bg-emerald-500 shadow-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            PO Konveksi
                                        </a>
                                    @else
                                        <span class="text-gray-700 dark:text-gray-300 text-xs font-normal">Tercukupi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-400">
                                    Tidak ada data varian yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
