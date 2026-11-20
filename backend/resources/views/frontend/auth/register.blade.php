@extends('frontend.layouts.app')

@section('title', 'Daftar — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Daftar akun '.\App\Models\SiteSetting::storeName().' baru untuk mengakses wishlist, riwayat pesanan, dan kecepatan checkout.')

@section('content')
    @php
        $redirectTarget = request()->query('redirect');
        $loginUrl = \App\Support\FrontendUrls::login()
            . (is_string($redirectTarget) && $redirectTarget !== ''
                ? '?'.http_build_query(['redirect' => $redirectTarget])
                : '');
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15';
    @endphp

    <div class="mx-auto max-w-lg">
        <div class="reveal overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] shadow-[0_26px_70px_-56px_rgba(50,19,95,0.5)]">
            <div class="relative overflow-hidden border-b border-[var(--border-light)] bg-[var(--brand-soft)]/50 px-6 py-8 text-center sm:px-8">
                <div class="pointer-events-none absolute -right-10 -top-14 h-40 w-40 rounded-full bg-[var(--brand)]/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-16 -left-10 h-36 w-36 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

                <p class="relative text-[9px] font-black uppercase tracking-[0.2em] text-[var(--brand)]">Bergabung dengan {{ \App\Models\SiteSetting::storeName() }}</p>
                <h1 class="relative mt-2 font-display text-[2.5rem] font-semibold leading-[1.02] tracking-[-0.04em] text-[var(--text)]">
                    Buat akun <span class="italic text-[var(--brand)]">barumu</span>
                </h1>
                <p class="relative mx-auto mt-2 max-w-xs text-xs leading-6 text-[var(--text-muted)]">
                    Simpan wishlist, lacak pesanan, dan checkout lebih cepat dalam satu akun.
                </p>
            </div>

            <div class="p-6 sm:p-8">
                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-[var(--error)]/20 bg-[var(--error-soft)] p-4 text-xs leading-5 text-[var(--error)]" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nama Lengkap <span class="text-[var(--error)]">*</span></label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               autocomplete="name"
                               placeholder="Nama lengkapmu"
                               class="{{ $inputClass }}"
                               aria-describedby="name-error">
                        @error('name')
                            <p id="name-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Email <span class="text-[var(--error)]">*</span></label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               placeholder="nama@email.com"
                               class="{{ $inputClass }}"
                               aria-describedby="email-error">
                        @error('email')
                            <p id="email-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nomor Telepon <span class="font-medium text-[var(--text-subtle)]">(opsional)</span></label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               autocomplete="tel"
                               placeholder="08xxxxxxxxxx"
                               class="{{ $inputClass }}"
                               aria-describedby="phone-error">
                        @error('phone')
                            <p id="phone-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Kata Sandi <span class="text-[var(--error)]">*</span></label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Min. 8 karakter"
                                   class="{{ $inputClass }}"
                                   aria-describedby="password-hint password-error">
                            @error('password')
                                <p id="password-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                            @else
                                <p id="password-hint" class="mt-1.5 text-[10px] text-[var(--text-subtle)]">Minimal 8 karakter</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Konfirmasi Sandi <span class="text-[var(--error)]">*</span></label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Ulangi kata sandi"
                                   class="{{ $inputClass }}"
                                   aria-describedby="password_confirmation-error">
                            @error('password_confirmation')
                                <p id="password_confirmation-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-start gap-2.5 rounded-xl bg-[var(--bg)] p-3.5">
                            <input type="checkbox"
                                   id="terms"
                                   name="terms"
                                   required
                                   class="mt-0.5 h-4 w-4 shrink-0 rounded border-[var(--border)] text-[var(--brand)] focus:ring-[var(--brand)]/20">
                            <label for="terms" class="text-[10px] leading-5 text-[var(--text-muted)]">
                                Saya menyetujui <a href="{{ \App\Support\FrontendUrls::terms() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Syarat & Ketentuan</a> dan <a href="{{ \App\Support\FrontendUrls::privacy() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Kebijakan Privasi</a> {{ \App\Models\SiteSetting::storeName() }}.
                            </label>
                        </div>
                        @error('terms')
                            <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 active:scale-[0.99]">
                        Buat Akun
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
                    </button>
                </form>

                <p class="mt-6 border-t border-[var(--border-light)] pt-5 text-center text-[11px] text-[var(--text-muted)]">
                    Sudah punya akun?
                    <a href="{{ $loginUrl }}" class="font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
@endsection
