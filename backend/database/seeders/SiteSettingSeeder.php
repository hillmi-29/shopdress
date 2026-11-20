<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Simpan nilai bawaan ke database agar admin bisa langsung mengeditnya
     * dari panel. Nilai yang sudah ada tidak ditimpa.
     */
    public function run(): void
    {
        foreach (SiteSetting::defaults() as $key => $value) {
            SiteSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
