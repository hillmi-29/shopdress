<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Helper bersama untuk membersihkan file gambar di disk public.
 */
class StoredFile
{
    /**
     * Hapus file bila path-nya lokal (bukan URL eksternal) dan memang ada.
     */
    public static function delete(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'http')) {
            return;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}
