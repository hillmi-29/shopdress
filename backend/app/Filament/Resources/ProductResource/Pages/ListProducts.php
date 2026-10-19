<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public function getHeader(): ?View
    {
        return view('filament.pages.partials.product-list-header', [
            'stats' => $this->getInventoryStats(),
        ]);
    }

    /**
     * Counts per stock bucket using the same definitions as the table's
     * "Status Stok" filter, so header stats and filter results agree.
     *
     * @return array{total: int, active: int, ready: int, low: int, empty: int}
     */
    protected function getInventoryStats(): array
    {
        return [
            'total' => Product::query()->count(),
            'active' => Product::query()->where('is_active', true)->count(),
            'ready' => ProductResource::stockStatusQuery(Product::query(), 'ready')->count(),
            'low' => ProductResource::stockStatusQuery(Product::query(), 'low')->count(),
            'empty' => ProductResource::stockStatusQuery(Product::query(), 'empty')->count(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Produk'),

            'active' => Tab::make('Aktif')
                ->badge(Product::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),

            'featured' => Tab::make('Unggulan')
                ->badge(Product::where('featured', true)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('featured', true)),

            'low_stock' => Tab::make('Perlu Restok')
                ->badge(ProductResource::stockStatusQuery(Product::query(), 'low')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => ProductResource::stockStatusQuery($query, 'low')),

            'draft' => Tab::make('Nonaktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }
}
