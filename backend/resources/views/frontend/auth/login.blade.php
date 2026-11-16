@extends('frontend.layouts.app')

@section('title', 'Masuk — ShopDress')
@section('meta_description', 'Masuk ke akun ShopDress Anda untuk mengakses wishlist, riwayat pesanan, dan kecepatan checkout.')

@section('content')
    @php
        $registerUrl = \App\Support\FrontendUrls::register();
    @endphp

    <div class="mx-auto max-w-md">
        <div class="reveal overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] shadow-[0_26px_70px_-56px_rgba(50,19,95,0.5)]">
            <div class="relative overflow-hidden border-b border-[var(--border-light)] bg-[var(--brand-soft)]/50 px-6 py-8 text-center sm:px-8">
                <div class="pointer-events-none absolute -right-10 -top-14 h-40 w-40 rounded-full bg-[var(--brand)]/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-16 -left-10 h-36 w-36 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

                <p class="relative text-[9px] font-black uppercase tracking-[0.2em] text-[var(--brand)]">Akun ShopDress</p>
                <h1 class="relative mt-2 font-display text-[2.5rem] font-semibold leading-[1.02] tracking-[-0.04em] text-[var(--text)]">
                    Selamat datang <span class="italic text-[var(--brand)]">kembali</span>
                </h1>
                <p class="relative mx-auto mt-2 max-w-xs text-xs leading-6 text-[var(--text-muted)]">
                    Masuk untuk melanjutkan belanja, melihat wishlist, dan memantau pesananmu.
                </p>
            </div>

            <div class="p-6 sm:p-8">
                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-[var(--error)]/20 bg-[var(--error-soft)] p-4 text-xs leading-5 text-[var(--error)]" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               placeholder="nama@email.com"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15"
                               aria-describedby="email-error">
                        @error('email')
                            <p id="email-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <label for="password" class="text-[11px] font-bold text-[var(--text)]">Kata Sandi</label>
                            <a href="{{ $helpUrl ?? \App\Support\FrontendUrls::help() }}" class="text-[10px] font-semibold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Lupa kata sandi?</a>
                        </div>
                        <div class="relative">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-11 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15"
                                   :type="showPassword ? 'text' : 'password'"
                                   aria-describedby="password-error">
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-subtle)] transition hover:text-[var(--brand)]"
                                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                <svg x-show="!showPassword" class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                <svg x-show="showPassword" x-cloak class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473a1 1 0 010-1.414l1.473-1.473a1 1 0 00-1.414-1.414L3.707 2.293zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p id="password-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox"
                               id="remember"
                               name="remember"
                               value="1"
                               class="h-4 w-4 rounded border-[var(--border)] text-[var(--brand)] focus:ring-[var(--brand)]/20">
                        <label for="remember" class="text-[11px] font-medium text-[var(--text-muted)]">Ingat saya</label>
                    </div>

                    <button type="submit"
                            class="flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 active:scale-[0.99]">
                        Masuk ke Akun
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
                    </button>
                </form>

                <p class="mt-6 border-t border-[var(--border-light)] pt-5 text-center text-[11px] text-[var(--text-muted)]">
                    Belum punya akun?
                    <a href="{{ $registerUrl }}" class="font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Daftar di sini</a>
                </p>
            </div>
        </div>

        <p class="mt-5 text-center text-[10px] leading-5 text-[var(--text-subtle)]">
            Dengan masuk, kamu menyetujui Syarat & Ketentuan serta Kebijakan Privasi ShopDress.
        </p>
    </div>
@endsection
