<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Produk Baru')
                ->icon('heroicon-m-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Baju'),

            'active' => Tab::make('Aktif (Tayang)')
                ->badge(Product::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),

            'featured' => Tab::make('Unggulan (Featured)')
                ->badge(Product::where('featured', true)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('featured', true)),

            'low_stock' => Tab::make('Stok Menipis')
                ->badge(Product::whereHas('variants', fn ($q) => $q->where('stock', '<=', 5))->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('variants', fn ($q) => $q->where('stock', '<=', 5))),

            'draft' => Tab::make('Nonaktif (Draft)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }
}
