@if (session('success') || session('error'))
    @php
        $flashType = session('success') ? 'success' : 'error';
        $flashMessage = session('success') ?: session('error');
    @endphp

    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5500)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="pointer-events-none fixed inset-x-4 top-24 z-[60] flex justify-end sm:inset-x-auto sm:right-6 sm:w-full sm:max-w-sm"
         role="status">
        <div class="pointer-events-auto flex w-full items-start gap-3 rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-4 shadow-[0_24px_60px_-30px_rgba(36,29,46,0.45)]">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $flashType === 'success' ? 'bg-[var(--success-soft)] text-[var(--success)]' : 'bg-[var(--error-soft)] text-[var(--error)]' }}">
                @if ($flashType === 'success')
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                @else
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                @endif
            </span>

            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-[var(--text)]">{{ $flashType === 'success' ? 'Berhasil' : 'Terjadi kesalahan' }}</p>
                <p class="mt-0.5 text-xs leading-5 text-[var(--text-muted)]">{{ $flashMessage }}</p>
            </div>

            <button type="button"
                    @click="show = false"
                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)]"
                    aria-label="Tutup notifikasi">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
@endif
