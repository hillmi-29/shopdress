<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    public function getSubheading(): ?string
    {
        return 'Pantau ulasan pelanggan, sembunyikan yang tidak pantas, dan pilih testimoni unggulan untuk beranda.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Review::count()),

            'featured' => Tab::make('Testimoni Beranda')
                ->badge(Review::featured()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('is_featured', true)),

            'hidden' => Tab::make('Disembunyikan')
                ->badge(Review::where('is_visible', false)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('is_visible', false)),
        ];
    }
}
