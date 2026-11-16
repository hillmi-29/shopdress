{{-- Simple resource list header: breadcrumbs, title, and optional create action. --}}
<header class="sd-page-header">
    <div>
        <nav class="sd-breadcrumbs" aria-label="Jejak navigasi">
            <a href="{{ \App\Filament\Pages\Dashboard::getUrl() }}" aria-label="Beranda">
                <span class="material-symbols-outlined" style="font-size: 16px;" aria-hidden="true">home</span>
            </a>
            <span class="material-symbols-outlined" style="font-size: 14px;">chevron_right</span>
            <span class="sd-crumb-current">{{ $title }}</span>
        </nav>

        <div class="sd-title-row">
            <h1 class="sd-page-title">{{ $title }}</h1>
        </div>
    </div>

    @if (($createUrl ?? null) && ($createLabel ?? null))
        <div class="sd-quick-actions">
            <a href="{{ $createUrl }}" class="sd-btn sd-btn-primary">
                <span class="material-symbols-outlined">{{ $createIcon ?? 'add_circle' }}</span>
                <span>{{ $createLabel }}</span>
            </a>
        </div>
    @endif
</header>
