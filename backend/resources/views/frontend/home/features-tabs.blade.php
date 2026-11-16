{{-- Why ShopDress — tabs with a sliding highlight --}}
<section class="reveal mt-16 overflow-hidden rounded-[2rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:mt-20 sm:p-9 lg:p-11"
         x-data="{
            activeTab: 0,
            highlightLeft: 0,
            highlightWidth: 0,
            init() {
                this.$nextTick(() => this.updateHighlight());
            },
            updateHighlight() {
                const element = this.$refs.tabs?.querySelectorAll('[role=tab]')[this.activeTab];
                if (! element) return;
                this.highlightLeft = element.offsetLeft;
                this.highlightWidth = element.offsetWidth;
            },
            select(index) {
                this.activeTab = index;
                this.$nextTick(() => {
                    this.updateHighlight();
                    this.$refs.tabs.querySelectorAll('[role=tab]')[index]?.scrollIntoView({
                        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                });
            }
         }"
         @resize.window="updateHighlight()">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-xl">
            <p class="section-kicker">Keunggulan kami</p>
            <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                Kenapa harus belanja di ShopDress?
            </h2>
            <p class="mt-2 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Komitmen kami menghadirkan pengalaman belanja busana terbaik — dari bahan hingga layanan purnajual.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]">
            <span class="flex h-1.5 w-1.5 rounded-full bg-[var(--success)]"></span>
            Standar butik, harga bersahabat
        </div>
    </div>

    {{-- Tabs --}}
    <div class="no-scrollbar mt-7 overflow-x-auto pb-1">
        <div x-ref="tabs" class="relative flex w-max min-w-full items-center gap-1 rounded-full bg-[var(--surface-2)] p-1.5">
            <span x-show="highlightWidth > 0"
                  class="absolute inset-y-1.5 rounded-full bg-[var(--surface)] shadow-[0_4px_16px_-8px_rgba(36,29,46,0.35)] ring-1 ring-black/[0.03] transition-all duration-300 ease-out"
                  :style="'left: ' + highlightLeft + 'px; width: ' + highlightWidth + 'px'"
                  aria-hidden="true"></span>

            @php
                $tabs = ['Bahan pilihan', 'Detail yang rapi', 'Kirim cepat & rapi', 'Garansi retur 7 hari'];
            @endphp

            @foreach ($tabs as $index => $label)
                <button type="button"
                        @click="select({{ $index }})"
                        class="relative z-10 shrink-0 rounded-full px-3.5 py-2.5 text-[11px] font-bold transition-colors duration-200 focus:outline-none sm:px-5 sm:text-xs"
                        :class="activeTab === {{ $index }} ? 'text-[var(--brand)]' : 'text-[var(--text-muted)] hover:text-[var(--text)]'"
                        :aria-selected="(activeTab === {{ $index }}).toString()"
                        role="tab">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Panels --}}
    <div class="mt-6">
        {{-- Panel 1 --}}
        <div x-show="activeTab === 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="grid gap-6 lg:min-h-[21rem] lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div class="flex flex-col rounded-[1.5rem] bg-[var(--brand-soft)]/60 p-5 sm:p-7">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--brand)] shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m18.5 15 .9 2.6 2.6.9-2.6.9-.9 2.6-.9-2.6-2.6-.9 2.6-.9.9-2.6Z"/></svg>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold leading-snug tracking-[-0.02em] text-[var(--text)] sm:text-2xl">
                    Bahan pilihan yang sejuk dan jatuh sempurna
                </h3>
                <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Satin Silk Armani, Luna Linen, dan katun rayon premium dipilih untuk iklim tropis. Teksturnya halus, tidak menerawang, dan tetap nyaman dipakai seharian.
                </p>

                <ul class="mt-5 space-y-2.5">
                    @foreach (['Tidak mudah kusut dan mudah disetrika', 'Berpori sehingga nyaman untuk cuaca panas', 'Warna tahan luntur meski sering dicuci'] as $point)
                        <li class="flex items-start gap-2.5 text-[11px] font-medium text-[var(--text-muted)] sm:text-xs">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--success)]">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative min-h-[15rem] overflow-hidden rounded-[1.5rem] bg-[var(--surface-2)]">
                <img src="{{ asset('images/editorial/hero-1.jpg') }}" alt="Detail bahan dress satin ShopDress" loading="lazy" class="absolute inset-0 h-full w-full object-cover object-[50%_10%]">
                <div class="absolute inset-0 bg-gradient-to-t from-[#241d2e]/45 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/65">Material story</p>
                        <p class="mt-1 font-display text-lg font-semibold text-white">Satin yang mengalir</p>
                    </div>
                    <span class="rounded-full bg-white/15 px-2.5 py-1 text-[9px] font-bold text-white backdrop-blur">01 / 04</span>
                </div>
            </div>
        </div>

        {{-- Panel 2 --}}
        <div x-show="activeTab === 1" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="grid gap-6 lg:min-h-[21rem] lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div class="flex flex-col rounded-[1.5rem] bg-[var(--surface-2)]/70 p-5 sm:p-7">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--brand)] shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 8 11 11M8 16 19 5"/></svg>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold leading-snug tracking-[-0.02em] text-[var(--text)] sm:text-2xl">
                    Detail yang rapi, dari pola sampai jahitan akhir
                </h3>
                <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Dikerjakan penjahit berpengalaman dengan jahitan ganda dan QC berlapis. Setiap dress dilengkapi resleting Jepang berkualitas serta kancing cadangan.
                </p>

                <ul class="mt-5 space-y-2.5">
                    @foreach (['Pola fitting proporsional untuk tubuh wanita Indonesia', 'Jahitan rapi tanpa benang lepas', 'Resleting YKK lembut dan tidak mudah macet'] as $point)
                        <li class="flex items-start gap-2.5 text-[11px] font-medium text-[var(--text-muted)] sm:text-xs">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--success)]">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative min-h-[15rem] overflow-hidden rounded-[1.5rem] bg-[var(--surface-2)]">
                <img src="{{ asset('images/editorial/about.jpg') }}" alt="Proses kurasi busana ShopDress" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#241d2e]/45 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/65">Atelier note</p>
                        <p class="mt-1 font-display text-lg font-semibold text-white">Rapi di setiap detail</p>
                    </div>
                    <span class="rounded-full bg-white/15 px-2.5 py-1 text-[9px] font-bold text-white backdrop-blur">02 / 04</span>
                </div>
            </div>
        </div>

        {{-- Panel 3 --}}
        <div x-show="activeTab === 2" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="grid gap-6 lg:min-h-[21rem] lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div class="flex flex-col rounded-[1.5rem] bg-[var(--accent-soft)]/70 p-5 sm:p-7">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--surface)] text-[#8a6420] shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h11v11H3zM14 9h4l3 3v5h-7"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold leading-snug tracking-[-0.02em] text-[var(--text)] sm:text-2xl">
                    Dikemas rapi dan dikirim di hari yang sama
                </h3>
                <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Pesanan sebelum pukul 15.00 WIB langsung kami proses. Setiap order datang dalam box eksklusif dengan dust cover pelindung.
                </p>

                <ul class="mt-5 space-y-2.5">
                    @foreach (['Packing box eksklusif tanpa biaya tambahan', 'Kurir resmi dan nomor lacak aktif', 'Notifikasi status pengiriman via email'] as $point)
                        <li class="flex items-start gap-2.5 text-[11px] font-medium text-[var(--text-muted)] sm:text-xs">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--success)]">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative min-h-[15rem] overflow-hidden rounded-[1.5rem] bg-[var(--surface-2)]">
                <img src="{{ asset('images/editorial/promo.jpg') }}" alt="Pengemasan pesanan ShopDress" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#241d2e]/45 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/65">Delivery note</p>
                        <p class="mt-1 font-display text-lg font-semibold text-white">Sampai dengan aman</p>
                    </div>
                    <span class="rounded-full bg-white/15 px-2.5 py-1 text-[9px] font-bold text-white backdrop-blur">03 / 04</span>
                </div>
            </div>
        </div>

        {{-- Panel 4 --}}
        <div x-show="activeTab === 3" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="grid gap-6 lg:min-h-[21rem] lg:grid-cols-[1.08fr_0.92fr] lg:items-stretch">
            <div class="flex flex-col rounded-[1.5rem] bg-[var(--brand-soft)]/60 p-5 sm:p-7">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--brand)] shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold leading-snug tracking-[-0.02em] text-[var(--text)] sm:text-2xl">
                    Belanja tenang dengan garansi tukar ukuran
                </h3>
                <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Ukuran terasa belum pas? Ajukan tukar ukuran hingga 7 hari setelah paket diterima. Tim customer care kami akan membantu prosesnya.
                </p>

                <ul class="mt-5 space-y-2.5">
                    @foreach (['Proses tukar ukuran cepat dan responsif', 'Opsi tukar warna bila stok tersedia', 'Tanpa biaya admin tambahan'] as $point)
                        <li class="flex items-start gap-2.5 text-[11px] font-medium text-[var(--text-muted)] sm:text-xs">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--success)]">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative min-h-[15rem] overflow-hidden rounded-[1.5rem] bg-[var(--surface-2)]">
                <img src="{{ asset('images/editorial/hero-3.jpg') }}" alt="Koleksi dress floral ShopDress" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#241d2e]/45 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/65">Service note</p>
                        <p class="mt-1 font-display text-lg font-semibold text-white">7 hari untuk memastikan</p>
                    </div>
                    <span class="rounded-full bg-white/15 px-2.5 py-1 text-[9px] font-bold text-white backdrop-blur">04 / 04</span>
                </div>
            </div>
        </div>
    </div>
</section>
