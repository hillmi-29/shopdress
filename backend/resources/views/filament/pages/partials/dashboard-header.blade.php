{{-- Custom dashboard header: breadcrumbs, welcome title, date & quick actions --}}
<header class="sd-page-header">
    <div>
        <nav class="sd-breadcrumbs" aria-label="Jejak navigasi">
            <span class="material-symbols-outlined" style="font-size: 16px;" aria-hidden="true">home</span>
            <span class="sd-crumb-current">Dashboard</span>
        </nav>

        <h1 class="sd-page-title"><span class="sd-text-muted">Selamat datang,</span> {{ filament()->auth()->user()?->name ?? 'Admin' }}</h1>
        <p class="sd-title-date">{{ $today }}</p>
    </div>

    <div class="sd-quick-actions">
        @foreach ($quickActions as $action)
            <a
                href="{{ $action['url'] }}"
                class="sd-btn {{ $action['variant'] === 'primary' ? 'sd-btn-primary' : 'sd-btn-outline' }}"
            >
                <span class="material-symbols-outlined" aria-hidden="true">{{ $action['icon'] }}</span>
                <span>{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>
</header>
