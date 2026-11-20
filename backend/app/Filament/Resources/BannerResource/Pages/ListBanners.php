<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Models\Banner;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola hero slider dan banner promo yang tampil di beranda pelanggan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Banner')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Banner::count()),
            'hero' => Tab::make('Hero')
                ->badge(Banner::position(Banner::POSITION_HERO)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('position', Banner::POSITION_HERO)),
            'promo' => Tab::make('Promo')
                ->badge(Banner::position(Banner::POSITION_PROMO)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('position', Banner::POSITION_PROMO)),
        ];
    }
}
