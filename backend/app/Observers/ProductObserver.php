<?php

namespace App\Observers;

use App\Models\Product;
use App\Support\StoredFile;

class ProductObserver
{
    /**
     * Baris product_images ikut terhapus lewat cascade foreign key
     * (tanpa event Eloquent), jadi file-nya dibersihkan di sini
     * sebelum record produk benar-benar hilang.
     */
    public function deleting(Product $product): void
    {
        foreach ($product->images()->pluck('image_path') as $path) {
            StoredFile::delete($path);
        }
    }
}
