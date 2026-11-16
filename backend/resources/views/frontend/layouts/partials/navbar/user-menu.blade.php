<div x-data="{ open: false }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     class="relative ml-1 hidden lg:block">
    <button type="button"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-haspopup="true"
            class="flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] py-1.5 pl-1.5 pr-3 text-[11px] font-bold text-[var(--text)] transition-all duration-200 ease-out hover:border-[var(--brand)] hover:shadow-sm focus:outline-none">
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[11px] font-bold text-[var(--brand)]">
            {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </span>
        <span class="max-w-24 truncate">{{ auth()->user()->name }}</span>
        <svg :class="{ 'rotate-180': open }" class="h-3.5 w-3.5 text-[var(--text-subtle)] transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
         x-cloak
         class="absolute right-0 mt-2.5 w-60 overflow-hidden rounded-2xl border border-[var(--border)] bg-[var(--surface)] shadow-[0_24px_60px_-30px_rgba(36,29,46,0.4)]">
        <div class="border-b border-[var(--border-light)] bg-[var(--surface-2)]/50 px-4 py-3">
            <p class="truncate text-xs font-bold text-[var(--text)]">{{ auth()->user()->name }}</p>
            <p class="mt-0.5 truncate text-[11px] text-[var(--text-muted)]">{{ auth()->user()->email }}</p>
        </div>
        <div class="p-1">
            <a href="{{ $profileUrl }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-[var(--text-muted)] transition hover:bg-[var(--brand-soft)] hover:text-[var(--brand)]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0" />
                </svg>
                Profil Akun
            </a>
            <a href="{{ $ordersUrl }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-[var(--text-muted)] transition hover:bg-[var(--brand-soft)] hover:text-[var(--brand)]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10v18l-2.5-1.5L12 21l-2.5-1.5L7 21V3Z" />
                </svg>
                Pesanan Saya
            </a>
            <a href="{{ $wishlistUrl }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-[var(--text-muted)] transition hover:bg-[var(--brand-soft)] hover:text-[var(--brand)]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
                </svg>
                Wishlist
            </a>
        </div>
        <form method="POST" action="{{ $logoutUrl }}" class="border-t border-[var(--border-light)] p-1">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-xs font-semibold text-[var(--error)] transition hover:bg-[var(--error-soft)]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H4m0 0 3.5-3.5M4 12l3.5 3.5M10 5V3h10v18H10v-2" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
