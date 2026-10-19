<?php

namespace App\Observers;

use App\Models\ProductImage;
use App\Support\StoredFile;

class ProductImageObserver
{
    /**
     * Menangani foto yang dihapus langsung dari galeri produk
     * (mis. item repeater dihapus saat mengubah produk).
     */
    public function deleting(ProductImage $image): void
    {
        StoredFile::delete($image->image_path);
    }
}
