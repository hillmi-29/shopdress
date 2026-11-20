<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use App\Models\Faq;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola pertanyaan umum yang tampil di beranda dan halaman bantuan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah FAQ')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua')
                ->badge(Faq::count()),
        ];

        foreach (Faq::pageOptions() as $page => $label) {
            $tabs[$page] = Tab::make($label)
                ->badge(Faq::forPage($page)->count())
                ->modifyQueryUsing(fn ($query) => $query->where('page', $page));
        }

        return $tabs;
    }
}
