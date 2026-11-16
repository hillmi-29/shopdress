@php
    $triggerClass = 'group relative inline-flex items-center gap-1 py-2 text-[13px] font-semibold tracking-tight transition-colors duration-200';
@endphp

<div x-data="{ open: false }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     class="relative inline-flex items-center">
    {{-- Kategori text link --}}
    <a href="{{ $categoryIndexUrl }}"
       class="{{ $triggerClass }} {{ $isCategory ? 'text-[var(--brand)]' : 'text-[var(--text-muted)] hover:text-[var(--text)]' }}"
       @click.stop
       aria-label="Buka halaman semua kategori">
        Kategori
        <span class="absolute inset-x-0 -bottom-0.5 h-[2px] origin-left rounded-full bg-[var(--brand)] transition-transform duration-200 ease-out {{ $isCategory ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
    </a>

    {{-- Dropdown trigger (arrow only) --}}
    <button type="button"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-haspopup="true"
            aria-label="Buka daftar kategori"
            class="ml-0.5 inline-flex items-center justify-center p-1.5 rounded-full text-[var(--text-muted)] hover:text-[var(--text)] hover:bg-[var(--surface-2)] transition-colors duration-200 {{ $isCategory ? 'text-[var(--brand)]' : '' }}">
        <svg :class="{ 'rotate-180': open }"
             class="h-3.5 w-3.5 transition-transform duration-200"
             viewBox="0 0 20 20"
             fill="currentColor"
             aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 translate-y-1"
         x-cloak
         role="menu"
         aria-label="Daftar kategori"
         class="absolute left-0 top-full z-50 mt-2 w-[19.5rem] rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-3 shadow-[0_28px_60px_-32px_rgba(50,19,95,0.45)]">
        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--text-subtle)]">Kategori pilihan</p>

        @if ($navCategories->isEmpty())
            <p class="mt-2 text-[13px] text-[var(--text-muted)]">Belum ada kategori.</p>
        @else
            <div class="mt-2.5 flex flex-wrap gap-1.5">
                @foreach ($navCategories->take(8) as $category)
                    <a href="{{ \App\Support\FrontendUrls::category($category) }}"
                       role="menuitem"
                       @click="open = false"
                       class="rounded-full border border-[var(--border)] bg-[var(--surface-2)]/60 px-3 py-1.5 text-[11px] font-semibold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:bg-[var(--brand-soft)] hover:text-[var(--brand)]">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <a href="{{ $categoryIndexUrl }}"
           role="menuitem"
           @click="open = false"
           class="mt-3 flex items-center justify-between gap-2 rounded-xl bg-[var(--brand-soft)] px-3 py-2 text-[11px] font-bold uppercase tracking-[0.1em] text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
            Lihat semua kategori
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
            </svg>
        </a>
    </div>
</div>
