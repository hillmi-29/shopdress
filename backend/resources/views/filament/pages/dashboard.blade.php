<x-filament-panels::page>
    <div class="sd-stack">
        {{-- Low stock alert banner --}}
        @if ($this->lowStockCount > 0)
            <div class="sd-alert">
                <div class="sd-alert-icon">
                    <span class="material-symbols-outlined" aria-hidden="true">warning</span>
                </div>
                <div class="sd-alert-body">
                    <div class="sd-alert-head">
                        <p class="sd-alert-title">
                            Pemberitahuan Inventaris: {{ $this->lowStockCount }} Varian Perlu Restok
                        </p>
                        <a href="{{ $this->lowStockUrl }}" class="sd-alert-action">
                            <span>Lihat Daftar Stok</span>
                            <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                        </a>
                    </div>
                    <p class="sd-alert-text">
                        Perhatian: {{ $this->lowStockCount }} varian produk sudah mencapai titik restok.
                        Segera lakukan pemesanan restock konveksi untuk menjaga kelancaran pengiriman.
                    </p>
                </div>
            </div>
        @endif

        {{-- KPI cards --}}
        <div class="sd-grid-4">
            @foreach ($this->kpis as $kpi)
                @php
                    $trendTone = match ($kpi['trend']['type']) {
                        'up' => 'success',
                        'down' => 'danger',
                        default => 'neutral',
                    };
                @endphp
                <div class="sd-stat">
                    <div class="sd-stat-top">
                        <span class="sd-stat-label">{{ $kpi['label'] }}</span>
                        <div class="sd-stat-icon sd-tone-{{ $kpi['tone'] }}">
                            <span class="material-symbols-outlined" aria-hidden="true">{{ $kpi['icon'] }}</span>
                        </div>
                    </div>
                    <div class="sd-stat-value">{{ $kpi['value'] }}</div>
                    <div class="sd-stat-meta">
                        <span class="sd-badge sd-badge-{{ $trendTone }}">
                            <span class="material-symbols-outlined" aria-hidden="true">{{ $kpi['trend']['icon'] }}</span>
                            {{ $kpi['trend']['text'] }}
                        </span>
                        <span class="sd-stat-caption">{{ $kpi['caption'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts row: 30-day sales line chart + category donut --}}
        <div class="sd-split-main">
            @php $trend = $this->salesTrend; @endphp

            <div class="sd-card sd-col-8">
                <div class="sd-card-head">
                    <div class="sd-card-head-text">
                        <h2 class="sd-section-title">Penjualan 30 Hari Terakhir</h2>
                        <p class="sd-caption">Penjualan harian, dibanding 30 hari sebelumnya</p>
                    </div>
                </div>
                <div class="sd-card-body">
                    <div class="sd-chart-meta">
                        <div class="sd-chart-legend">
                            <span><span class="sd-legend-line"></span>30 Hari Terakhir</span>
                            <span><span class="sd-legend-dash"></span>30 Hari Sebelumnya</span>
                        </div>
                        @if ($trend['peak'])
                            <span class="sd-callout">
                                <span class="material-symbols-outlined" aria-hidden="true">stars</span>
                                <span>Puncak Penjualan: <strong>{{ $trend['peak']['label'] }}</strong></span>
                            </span>
                        @endif
                    </div>

                    <div class="sd-chart-wrap">
                        <svg class="sd-chart-svg sd-line-chart" viewBox="0 0 760 230" role="img" aria-label="Grafik penjualan 30 hari terakhir">
                            <defs>
                                <linearGradient id="sdRevenueArea" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" style="stop-color: var(--sd-chart-1);" stop-opacity="0.22"></stop>
                                    <stop offset="100%" style="stop-color: var(--sd-chart-1);" stop-opacity="0"></stop>
                                </linearGradient>
                            </defs>

                            @foreach ($trend['grid'] as $y)
                                <line class="sd-grid-line" x1="40" x2="740" y1="{{ $y }}" y2="{{ $y }}" stroke-width="1"></line>
                            @endforeach

                            @foreach ($trend['y_labels'] as $label)
                                <text class="sd-axis-label" x="34" y="{{ $label['y'] + 4 }}" text-anchor="end" font-size="10">{{ $label['value'] }}</text>
                            @endforeach

                            <path class="sd-prev-line" d="{{ $trend['previous_path'] }}" fill="none" stroke-dasharray="4 4" stroke-width="2"></path>
                            <path d="{{ $trend['area_path'] }}" fill="url(#sdRevenueArea)"></path>
                            <path class="sd-current-line" d="{{ $trend['current_path'] }}" fill="none" stroke-width="2.5"></path>

                            @if ($trend['peak'])
                                <line class="sd-peak-line" x1="{{ $trend['peak']['x'] }}" x2="{{ $trend['peak']['x'] }}" y1="{{ $trend['peak']['y'] }}" y2="180" stroke-dasharray="2 2" stroke-width="1.5"></line>
                                <circle class="sd-peak-dot" cx="{{ $trend['peak']['x'] }}" cy="{{ $trend['peak']['y'] }}" r="5" stroke-width="2.5"></circle>
                                <g transform="translate({{ max(0, min(590, $trend['peak']['x'] - 85)) }}, {{ max(0, $trend['peak']['y'] - 34) }})">
                                    <rect width="170" height="26" rx="4" style="fill: var(--sd-on-surface);"></rect>
                                    <text x="85" y="17" text-anchor="middle" font-size="10" font-weight="600" style="fill: var(--sd-surface);">{{ $trend['peak']['label'] }}</text>
                                </g>
                            @endif
                        </svg>

                        <div class="sd-chart-xaxis">
                            @foreach ($trend['x_labels'] as $label)
                                <span class="{{ $label['peak'] ? 'sd-xaxis-peak' : '' }}">{{ $label['label'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @php $share = $this->categoryShare; @endphp

            <div class="sd-card sd-col-4">
                <div class="sd-card-head">
                    <div class="sd-card-head-text">
                        <h2 class="sd-section-title">Penjualan per Kategori</h2>
                        <p class="sd-caption">Berdasarkan jumlah unit terjual</p>
                    </div>
                </div>
                <div class="sd-card-body">
                    <div class="sd-donut-wrap">
                        <svg width="190" height="190" viewBox="0 0 190 190" style="transform: rotate(-90deg);" role="img" aria-label="Diagram penjualan per kategori">
                            <circle cx="95" cy="95" r="70" fill="transparent" style="stroke: var(--sd-surface-muted);" stroke-width="20"></circle>
                            @foreach ($share['segments'] as $segment)
                                <circle
                                    cx="95"
                                    cy="95"
                                    r="70"
                                    fill="transparent"
                                    style="stroke: {{ $segment['color'] }};"
                                    stroke-width="20"
                                    stroke-dasharray="{{ $segment['dash'] }} {{ $share['circumference'] }}"
                                    stroke-dashoffset="{{ $segment['offset'] }}"
                                ></circle>
                            @endforeach
                        </svg>
                        <div class="sd-donut-center">
                            <span class="sd-eyebrow">Total Terjual</span>
                            <span class="sd-donut-total">{{ number_format($share['total'], 0, ',', '.') }}</span>
                            <span class="sd-donut-hint">pcs busana</span>
                        </div>
                    </div>

                    <div class="sd-donut-legend">
                        @forelse ($share['segments'] as $segment)
                            <div class="sd-donut-row">
                                <div class="sd-donut-name">
                                    <span class="sd-donut-dot" style="background-color: {{ $segment['color'] }};"></span>
                                    <span class="sd-donut-label">{{ $segment['name'] }}</span>
                                </div>
                                <div class="sd-donut-figures">
                                    <span>{{ number_format($segment['units'], 0, ',', '.') }} pcs</span>
                                    <span class="sd-donut-pct">{{ $segment['pct'] }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="sd-empty" style="padding: 1rem;">
                                <span>Belum ada penjualan tercatat.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Lower row: latest orders + top products --}}
        <div class="sd-split-main">
            <div class="sd-card sd-col-8">
                <div class="sd-card-head">
                    <div class="sd-card-head-text">
                        <h2 class="sd-section-title">Pesanan Terbaru</h2>
                        <p class="sd-caption">5 transaksi terakhir</p>
                    </div>
                    <a class="sd-card-link" href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined" style="font-size: 16px;" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
                <div class="sd-table-scroll">
                    <table class="sd-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Produk &amp; Jumlah</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th class="sd-td-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->latestOrders as $order)
                                <tr>
                                    <td>
                                        <a class="sd-link" href="{{ $order['url'] }}">{{ $order['number'] }}</a>
                                    </td>
                                    <td>
                                        <div class="sd-user-cell">
                                            <div class="sd-avatar sd-avatar-{{ $order['avatar_tone'] }}">{{ $order['initials'] }}</div>
                                            <div style="min-width: 0;">
                                                <div class="sd-user-name">{{ $order['customer_name'] }}</div>
                                                <div class="sd-user-sub">{{ $order['customer_sub'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="sd-text-strong">{{ $order['item_summary'] }}</span>
                                        @if ($order['extra_items'] > 0)
                                            <span class="sd-user-sub" style="display:inline;">+{{ $order['extra_items'] }} produk lain</span>
                                        @endif
                                    </td>
                                    <td class="sd-num">{{ $order['total'] }}</td>
                                    <td>
                                        <span class="sd-badge sd-badge-{{ $order['status_tone'] }}">
                                            <span class="sd-badge-dot"></span>
                                            {{ $order['status_label'] }}
                                        </span>
                                    </td>
                                    <td class="sd-user-sub">{{ $order['time'] }}</td>
                                    <td class="sd-td-right">
                                        <a class="sd-icon-btn" href="{{ $order['url'] }}" title="Detail Pesanan" aria-label="Detail pesanan {{ $order['number'] }}">
                                            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="sd-empty">
                                            <span class="material-symbols-outlined" aria-hidden="true">receipt_long</span>
                                            <span>Belum ada pesanan masuk.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="sd-card sd-col-4">
                <div class="sd-card-head">
                    <div class="sd-card-head-text">
                        <h2 class="sd-section-title">Produk Terlaris</h2>
                        <p class="sd-caption">Diurutkan dari unit terjual terbanyak</p>
                    </div>
                    <a class="sd-card-link" href="{{ \App\Filament\Resources\ProductResource::getUrl('index') }}">Katalog &rarr;</a>
                </div>
                <div class="sd-card-body">
                    <div class="sd-top-list">
                        @forelse ($this->topProducts as $product)
                            <div class="sd-top-item">
                                <div class="sd-top-thumb">
                                    @if ($product['image_url'])
                                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" />
                                    @else
                                        <span class="material-symbols-outlined" style="font-size: 20px;" aria-hidden="true">checkroom</span>
                                    @endif
                                </div>
                                <div class="sd-top-info">
                                    <div class="sd-top-row">
                                        <h3 class="sd-top-name" title="{{ $product['name'] }}">{{ $product['name'] }}</h3>
                                        <span class="sd-top-units">{{ number_format($product['units'], 0, ',', '.') }} pcs</span>
                                    </div>
                                    <div class="sd-top-meta">
                                        <span class="sd-chip sd-chip-{{ $product['chip_tone'] }}">{{ $product['category'] }}</span>
                                        <span class="sd-top-price">{{ $product['price'] }}</span>
                                    </div>
                                    <div class="sd-progress" aria-hidden="true">
                                        <div class="sd-progress-fill" style="width: {{ $product['bar_pct'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="sd-empty">
                                <span class="material-symbols-outlined" aria-hidden="true">insights</span>
                                <span>Belum ada data penjualan produk.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="sd-card-foot" style="justify-content: center;">
                    <a href="{{ $this->restockUrl }}" class="sd-btn sd-btn-subtle sd-btn-full">
                        <span class="material-symbols-outlined" aria-hidden="true">inventory</span>
                        <span>Kelola Restock Konveksi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
