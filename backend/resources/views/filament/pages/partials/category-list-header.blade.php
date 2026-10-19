{{-- Category list header: breadcrumbs, title + count, actions and taxonomy stat strip --}}
<div class="sd-stack">
    <header class="sd-page-header">
        <div>
            <nav class="sd-breadcrumbs" aria-label="Jejak navigasi">
                <a href="{{ \App\Filament\Pages\Dashboard::getUrl() }}" aria-label="Beranda">
                    <span class="material-symbols-outlined" style="font-size: 16px;" aria-hidden="true">home</span>
                </a>
                <span class="material-symbols-outlined" style="font-size: 14px;">chevron_right</span>
                <span class="sd-crumb-current">Kategori</span>
            </nav>

            <div class="sd-title-row">
                <h1 class="sd-page-title">Kategori</h1>
                <span class="sd-badge sd-badge-primary">{{ $stats['total'] }} kategori</span>
            </div>
        </div>

        <div class="sd-quick-actions">
            <a href="{{ \App\Filament\Resources\ProductResource::getUrl('index') }}" class="sd-btn sd-btn-outline">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>Lihat Produk</span>
            </a>
            <a href="{{ \App\Filament\Resources\CategoryResource::getUrl('create') }}" class="sd-btn sd-btn-primary">
                <span class="material-symbols-outlined">add_circle</span>
                <span>Buat Kategori</span>
            </a>
        </div>
    </header>

    <div class="sd-grid-3">
        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Total Kategori</span>
                <div class="sd-stat-icon sd-tone-primary">
                    <span class="material-symbols-outlined">category</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['total'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">Struktur taksonomi toko siap pakai</span>
            </div>
        </div>

        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Total Produk Terkait</span>
                <div class="sd-stat-icon sd-tone-info">
                    <span class="material-symbols-outlined">checkroom</span>
                </div>
            </div>
            <div class="sd-stat-value">{{ $stats['products'] }}</div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption">Terdistribusi di {{ $stats['total'] }} kategori</span>
            </div>
        </div>

        <div class="sd-stat">
            <div class="sd-stat-top">
                <span class="sd-stat-label">Kategori Terbanyak</span>
                <div class="sd-stat-icon sd-tone-warning">
                    <span class="material-symbols-outlined">stars</span>
                </div>
            </div>
            <div class="sd-stat-value sd-stat-value-text" title="{{ $stats['top_name'] ?? 'Belum ada kategori' }}">
                {{ $stats['top_name'] ?? 'Belum ada kategori' }}
            </div>
            <div class="sd-stat-meta">
                <span class="sd-stat-caption"><strong>{{ $stats['top_count'] }} produk</strong> ({{ $stats['top_share'] }}% dari katalog)</span>
            </div>
        </div>
    </div>
</div>
