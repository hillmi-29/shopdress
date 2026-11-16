# Dokumentasi ShopDress

Kumpulan dokumen proyek, referensi desain, dan panduan penggunaan.

## Daftar Isi

| Dokumen | Deskripsi |
| --- | --- |
| [Project Progress & UI Spec](project-progress-and-ui-spec.md) | Ringkasan progres fitur, design token, palet warna, tipografi, dan spesifikasi UI. |
| [QA Report](qa-report.md) | Hasil audit kualitas, temuan bug, dan status perbaikan. |
| [Design Reference — Admin](design-reference/admin/) | Mockup HTML statis halaman admin (dashboard, produk, kategori, login) sebagai referensi visual. |
| [Panduan Penggunaan](guides/cara-menggunakan-sekarang.docx) | Panduan operasional penggunaan aplikasi. |

## Struktur Repositori

```text
shopdress/
├── backend/          # Aplikasi Laravel (web, API, admin Filament, database)
├── docs/             # Dokumentasi, referensi desain, dan panduan
├── .agents/          # Skill & aturan agent AI (tooling pengembangan)
├── README.md         # Dokumentasi utama proyek
└── CLAUDE.md         # Panduan kerja agent
```

Dokumentasi teknis aplikasi (setup, perintah, arsitektur) ada di
[`backend/README.md`](../backend/README.md).
