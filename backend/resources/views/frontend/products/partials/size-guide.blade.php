@php
    use App\Support\FrontendUrls;
@endphp

<div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-2 rounded-2xl border border-[var(--border)] bg-[var(--surface)] px-3.5 py-2.5 sm:px-4">
    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
        </svg>
    </span>

    <p class="min-w-0 flex-1 text-[10px] font-semibold text-[var(--text-muted)] sm:text-[11px]">
        Butuh bantuan memilih ukuran?
    </p>

    <a href="{{ FrontendUrls::help() }}"
       class="inline-flex items-center gap-1.5 text-[10px] font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)] sm:text-[11px]">
        Baca panduan
        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
        </svg>
    </a>
</div>
