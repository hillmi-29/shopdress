{{-- Store value propositions --}}
<section class="reveal mt-5 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] px-4 py-4 sm:px-6 sm:py-5">
    <div class="grid grid-cols-2 gap-x-4 gap-y-5 lg:grid-cols-4 lg:gap-0">
        @php
            $props = [
                ['title' => 'Bahan pilihan', 'copy' => 'Satin, linen, dan katun premium yang sejuk.', 'icon' => 'sparkles'],
                ['title' => 'Detail yang rapi', 'copy' => 'Jahitan kuat dengan finishing butik.', 'icon' => 'scissors'],
                ['title' => 'Kirim cepat', 'copy' => 'Diproses dan dikemas di hari yang sama.', 'icon' => 'truck'],
                ['title' => 'Retur 7 hari', 'copy' => 'Tukar ukuran tanpa biaya admin.', 'icon' => 'shield'],
            ];
        @endphp

        @foreach ($props as $prop)
            <div class="flex items-start gap-3 px-1 lg:px-6 {{ ! $loop->first ? 'lg:border-l lg:border-[var(--border-light)]' : '' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    @if ($prop['icon'] === 'sparkles')
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m18.5 15 .9 2.6 2.6.9-2.6.9-.9 2.6-.9-2.6-2.6-.9 2.6-.9.9-2.6Z"/></svg>
                    @elseif ($prop['icon'] === 'scissors')
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 8 11 11M8 16 19 5"/></svg>
                    @elseif ($prop['icon'] === 'truck')
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h11v11H3zM14 9h4l3 3v5h-7"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg>
                    @else
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                    @endif
                </span>
                <div class="min-w-0">
                    <h2 class="text-[11px] font-bold text-[var(--text)] sm:text-xs">{{ $prop['title'] }}</h2>
                    <p class="mt-0.5 text-[10px] leading-4 text-[var(--text-subtle)] sm:text-[11px]">{{ $prop['copy'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
