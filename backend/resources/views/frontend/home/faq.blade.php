{{-- FAQ accordion, animated with grid-template-rows --}}
<section class="mt-16 sm:mt-20">
    <div class="grid gap-8 lg:grid-cols-[0.78fr_1.22fr] lg:gap-14">
        <div class="reveal lg:sticky lg:top-28 lg:self-start">
            <p class="section-kicker">Bantuan cepat</p>
            <h2 class="mt-2 max-w-md font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                Pertanyaan yang sering ditanyakan
            </h2>
            <p class="mt-3 max-w-sm text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Ukuran, bahan, pengiriman, atau pembayaran — temukan jawabannya di sini.
            </p>

            <div class="mt-6 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e8f7ef] text-[#1f9d64]">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m4.52 13.53c-.25-.13-1.47-.72-1.7-.81-.23-.09-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.44.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.21-.18-.46-.31"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-[var(--text)]">Masih ada pertanyaan?</p>
                        <p class="mt-1 text-[11px] leading-5 text-[var(--text-muted)]">Tim kami siap membantu setiap hari, 09.00–21.00 WIB.</p>
                    </div>
                </div>
                <a href="https://wa.me/6281234567890?text=Halo%20ShopDress,%20saya%20ingin%20bertanya"
                   target="_blank" rel="noopener noreferrer"
                   class="mt-4 inline-flex items-center gap-1.5 text-[11px] font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">
                    Chat customer care
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>

        @php
            $faqs = [
                [
                    'q' => 'Bagaimana cara memilih ukuran yang tepat?',
                    'a' => 'Setiap produk dilengkapi panduan ukuran (lingkar dada, panjang baju, panjang lengan) di halaman detail. Jika ragu di antara dua ukuran, pilih yang lebih longgar atau hubungi customer care kami untuk konsultasi gratis.',
                ],
                [
                    'q' => 'Apakah bahannya menerawang?',
                    'a' => 'Tidak. Kain satin silk, linen, dan rayon premium yang kami gunakan memiliki kerapatan serat tinggi. Warna cerah seperti putih dan cream juga dilapisi furing lembut agar tetap nyaman dan aman.',
                ],
                [
                    'q' => 'Berapa lama estimasi pengiriman?',
                    'a' => 'Pesanan sebelum pukul 15.00 WIB diproses di hari yang sama. Jabodetabek 1–2 hari kerja, Pulau Jawa 2–3 hari kerja, dan luar Pulau Jawa 3–5 hari kerja.',
                ],
                [
                    'q' => 'Metode pembayaran apa saja yang tersedia?',
                    'a' => 'Pembayaran diproses aman melalui Midtrans: virtual account BCA, Mandiri, BNI, BRI, QRIS (GoPay, OVO, ShopeePay, Dana), serta kartu kredit/debit Visa dan Mastercard.',
                ],
                [
                    'q' => 'Bagaimana jika ukuran tidak pas atau produk cacat?',
                    'a' => 'Kami memberikan garansi tukar ukuran atau pengembalian produk cacat maksimal 7 hari sejak paket diterima. Sertakan foto unboxing dan hubungi WhatsApp customer care untuk diproses tanpa biaya admin.',
                ],
            ];
        @endphp

        <div class="reveal divide-y divide-[var(--border)] border-y border-[var(--border)]"
             x-data="{ activeFaq: 0 }"
             style="--reveal-delay: 80ms">
            @foreach ($faqs as $index => $faq)
                <div>
                    <h3>
                        <button type="button"
                                @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}"
                                class="flex w-full items-center justify-between gap-5 py-4.5 text-left focus:outline-none sm:py-5"
                                :aria-expanded="(activeFaq === {{ $index }}).toString()"
                                aria-controls="faq-panel-{{ $index }}">
                            <span class="text-[13px] font-semibold leading-5 text-[var(--text)] sm:text-sm">{{ $faq['q'] }}</span>
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-[var(--border)] text-[var(--text-muted)] transition-all duration-250 ease-out"
                                  :class="activeFaq === {{ $index }} ? 'rotate-45 border-[var(--brand)] bg-[var(--brand)] text-white' : 'group-hover:border-[var(--text-subtle)]'">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3.75a.75.75 0 0 1 .75.75v4.75h4.75a.75.75 0 0 1 0 1.5h-4.75v4.75a.75.75 0 0 1-1.5 0v-4.75H4.5a.75.75 0 0 1 0-1.5h4.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/></svg>
                            </span>
                        </button>
                    </h3>

                    <div id="faq-panel-{{ $index }}"
                         class="grid transition-[grid-template-rows] duration-300 ease-out"
                         :class="activeFaq === {{ $index }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                        <div class="overflow-hidden">
                            <p class="max-w-2xl pb-5 pr-10 text-[11px] leading-6 text-[var(--text-muted)] sm:text-xs">
                                {{ $faq['a'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
