<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="color-scheme" content="light dark"/>
    <title>Masuk ke Panel Admin — ShopDress</title>

    <script>
        // Ikuti pilihan tema panel (localStorage 'theme') sebelum paint,
        // supaya login tidak berkedip terang saat panel memakai mode gelap.
        (() => {
            const theme = localStorage.getItem('theme') ?? 'system';

            if (
                theme === 'dark' ||
                (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    {{-- Design system yang sama dengan seluruh panel admin --}}
    <link
        href="{{ asset('css/admin-shopdress.css') }}?v={{ @filemtime(public_path('css/admin-shopdress.css')) }}"
        rel="stylesheet"
    />

    <style>
        /* Reset ringan — layout ini berdiri sendiri di luar kerangka panel */
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--sd-canvas, #F8FAFC);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>
<body>
    {{-- Boot loader dirender oleh layout — DI LUAR root komponen Livewire. Ini penting:
         kalau elemen ini ada di dalam view komponen, morphing Livewire menyisipkan ulang
         elemen yang sudah dihapus setiap kali ada respons request (validasi blur, submit),
         sehingga overlay "loading" muncul lagi tiba-tiba di tengah interaksi dan
         memblokir halaman. --}}
    <div class="sd-auth-loader" id="auth-loader" aria-hidden="true">
        <div class="sd-auth-loader-box">
            <div class="sd-auth-loader-inner">
                <span class="sd-auth-loader-glow"></span>
                <span class="sd-auth-loader-ring"></span>
                <span class="sd-auth-loader-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </span>
            </div>
            <p class="sd-auth-loader-text">Menyiapkan panel admin&hellip;</p>
        </div>
    </div>
    <noscript>
        <style>.sd-auth-loader { display: none !important; }</style>
    </noscript>

    <script>
        // Loader hanya untuk muat pertama: sembunyikan setelah halaman siap,
        // dengan durasi minimum singkat supaya tidak berkedip.
        (() => {
            const loader = document.getElementById('auth-loader');

            if (!loader) {
                return;
            }

            const shownAt = performance.now();
            const minVisible = 500;
            let finished = false;

            const finish = () => {
                if (finished) {
                    return;
                }

                finished = true;
                document.documentElement.classList.add('sd-auth-ready');
                loader.classList.add('sd-auth-loader--done');

                // transitionend ikut terpicu oleh transisi anak (box) — tunggu
                // fade opacity overlay supaya tidak terhapus terlalu dini.
                const remove = (event) => {
                    if (event && (event.target !== loader || event.propertyName !== 'opacity')) {
                        return;
                    }

                    loader.removeEventListener('transitionend', remove);
                    loader.remove();
                };

                loader.addEventListener('transitionend', remove);
                setTimeout(remove, 900);
            };

            window.addEventListener('load', () => {
                const elapsed = performance.now() - shownAt;
                setTimeout(finish, Math.max(0, minVisible - elapsed));
            }, { once: true });

            setTimeout(finish, 3500);
        })();
    </script>

    {{ $slot }}

    @livewireScripts
</body>
</html>
