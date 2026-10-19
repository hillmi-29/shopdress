{{-- Product list header: breadcrumbs, title + count, actions and inventory stat strip --}}
<div class="sd-stack">
    <header class="sd-page-header">
        <div>
            <nav class="sd-breadcrumbs" aria-label="Jejak navigasi">
                <a href="{{ \App\Filament\Pages\Dashboard::getUrl() }}" aria-label="Beranda">
                    <span class="material-symbols-outlined" style="font-size: 16px;" aria-hidden="true">home</span>
                </a>
                <span class="material-symbols-outlined" style="font-size: 14px;">chevron_right</span>
                <span class="sd-crumb-current">Produk</span>
            </nav>

            <div class="sd-title-row">
                <h1 class="sd-page-title">Produk</h1>
                <span class="sd-badge sd-badge-primary">{{ $stats['total'] }} produk</span>
            </div>
        </div>

        <div class="sd-quick-actions">
            <a href="{{ \App\Filament\Resources\CategoryResource::getUrl('index') }}" class="sd-btn sd-btn-outline">
                <span class="material-symbols-outlined">tune</span>
                <span>Atur Kategori</span>
            </a>
            <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}" class="sd-btn sd-btn-primary">
                <span class="material-symbols-outlined">add_circle</span>
                <span>Tambah Produk</span>
            </a>
        </div>
    </header>

    <div class="sd-grid-4">
        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Total Produk</span>
                <div class="sd-stat-icon sd-tone-primary">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['total'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">{{ $stats['active'] }} produk aktif di katalog</span>
            </div>
        </div>

        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Stok Aman</span>
                <div class="sd-stat-icon sd-tone-success">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['ready'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">Semua varian di atas titik restok</span>
            </div>
        </div>

        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Perlu Restok</span>
                <div class="sd-stat-icon sd-tone-warning">
                    <span class="material-symbols-outlined">notification_important</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['low'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">Ada varian yang mencapai titik restok</span>
            </div>
        </div>

        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Stok Habis</span>
                <div class="sd-stat-icon sd-tone-danger">
                    <span class="material-symbols-outlined">remove_shopping_cart</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['empty'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">Tidak ada stok tersisa</span>
            </div>
        </div>
    </div>
</div>
