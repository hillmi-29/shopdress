{{-- Bar progres navigasi: mulai saat link diklik (halaman lama), tuntaskan saat halaman baru siap --}}
<div class="sd-nav-progress" id="sd-nav-progress" aria-hidden="true"></div>
<script>
    (() => {
        const bar = document.getElementById('sd-nav-progress');
        const key = 'sd-nav-progress';

        if (!bar || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        // Halaman ini dibuka lewat klik link internal: kalau begitu, tuntaskan bar
        // dari posisi awal yang terlihat (bukan dari 0) supaya tidak menyentak.
        let resuming = false;

        try {
            resuming = sessionStorage.getItem(key) !== null;
            sessionStorage.removeItem(key);
        } catch (e) {
            resuming = false;
        }

        if (resuming) {
            bar.classList.add('sd-nav-progress--instant');
            void bar.offsetWidth;
            bar.classList.remove('sd-nav-progress--instant');
            bar.classList.add('sd-nav-progress--done');
        }

        // Klik link internal: tandai navigasi + nyalakan bar (langsung terlihat).
        document.addEventListener('click', (event) => {
            const link = event.target.closest('a[href]');

            if (!link || event.defaultPrevented || event.button !== 0
                || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey
                || (link.target && link.target !== '_self') || link.hasAttribute('download')) {
                return;
            }

            const url = new URL(link.href, location.href);

            if (url.origin !== location.origin || !url.pathname.startsWith('/admin')) {
                return;
            }

            // Link ke halaman yang sedang dibuka (mis. aksi tabel href="#...") tidak dihitung navigasi.
            if (url.pathname === location.pathname && url.search === location.search) {
                return;
            }

            try {
                sessionStorage.setItem(key, '1');
            } catch (e) {
                // Abaikan — bar tetap tampil di halaman ini.
            }

            bar.classList.remove('sd-nav-progress--done');
            bar.classList.add('sd-nav-progress--instant');
            void bar.offsetWidth;
            bar.classList.remove('sd-nav-progress--instant');
            bar.classList.add('sd-nav-progress--loading');
        }, true);
    })();
</script>
