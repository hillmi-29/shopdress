<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Salin FAQ yang sebelumnya ditulis langsung di Blade ke database.
     */
    public function run(): void
    {
        foreach (Faq::pageOptions() as $page => $label) {
            foreach (Faq::defaultsFor($page) as $index => $item) {
                Faq::firstOrCreate(
                    [
                        'page' => $page,
                        'question' => $item['q'],
                    ],
                    [
                        'answer' => $item['a'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
