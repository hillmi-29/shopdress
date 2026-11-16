@if ($testimonials->isNotEmpty())
    <section class="mt-16 sm:mt-20">
        <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Ulasan pelanggan</p>
                <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                    Cerita dari pelanggan kami
                </h2>
                <p class="mt-2 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Pengalaman mereka memakai koleksi ShopDress untuk melengkapi gaya sehari-hari.
                </p>
            </div>

            <div class="flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] px-3.5 py-2">
                <div class="flex items-center gap-0.5 text-[var(--accent)]" aria-hidden="true">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z"/></svg>
                    @endfor
                </div>
                <span class="text-[10px] font-bold text-[var(--text)]">4,9 dari 5</span>
                <span class="text-[10px] text-[var(--text-subtle)]">· 1.200+ ulasan</span>
            </div>
        </div>

        <div class="mt-7 grid gap-4 md:grid-cols-2">
            @foreach ($testimonials as $review)
                <figure class="reveal relative flex flex-col overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-6 sm:p-7"
                        style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <div class="pointer-events-none absolute -right-4 -top-6 font-display text-[7rem] leading-none text-[var(--brand-soft)]" aria-hidden="true">
                        &rdquo;
                    </div>

                    <div class="relative flex items-center gap-0.5" role="img" aria-label="{{ $review->rating }} dari 5 bintang">
                        @for ($star = 1; $star <= 5; $star++)
                            <svg class="h-3.5 w-3.5 {{ $star <= $review->rating ? 'fill-[var(--accent)]' : 'fill-[var(--border)]' }}" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                            </svg>
                        @endfor
                    </div>

                    @if ($review->comment)
                        <blockquote class="relative mt-4 flex-1 text-[13px] leading-6 text-[var(--text-muted)] sm:text-sm">
                            &ldquo;{{ \Illuminate\Support\Str::limit($review->comment, 220) }}&rdquo;
                        </blockquote>
                    @endif

                    <figcaption class="relative mt-6 flex items-center gap-3 border-t border-[var(--border-light)] pt-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[11px] font-bold text-[var(--brand)]">
                            {{ mb_strtoupper(mb_substr($review->user->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-bold text-[var(--text)]">{{ $review->user->name }}</p>
                            @if ($review->product)
                                <a href="{{ $productUrl($review->product) }}" class="mt-0.5 flex items-center gap-1 truncate text-[10px] text-[var(--text-subtle)] transition hover:text-[var(--brand)]">
                                    <svg class="h-2.5 w-2.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                                    {{ $review->product->name }}
                                </a>
                            @endif
                        </div>
                        <span class="shrink-0 text-[9px] font-medium text-[var(--text-subtle)]">Pembeli terverifikasi</span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endif
