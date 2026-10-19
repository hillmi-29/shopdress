<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    public function getHeader(): ?View
    {
        return view('filament.pages.partials.category-list-header', [
            'stats' => $this->getCategoryStats(),
        ]);
    }

    /**
     * Angka ringkas untuk header: total kategori, total produk, dan kategori
     * dengan produk terbanyak beserta porsinya dari seluruh katalog.
     *
     * @return array{total: int, products: int, top_name: ?string, top_count: int, top_share: string}
     */
    protected function getCategoryStats(): array
    {
        $products = Product::query()->count();

        $top = Category::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->first();

        return [
            'total' => Category::query()->count(),
            'products' => $products,
            'top_name' => $top?->name,
            'top_count' => $top->products_count ?? 0,
            'top_share' => ($products > 0 && $top !== null)
                ? number_format($top->products_count / $products * 100, 1, ',', '.')
                : '0',
        ];
    }
}
