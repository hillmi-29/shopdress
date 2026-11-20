<?php

namespace App\Filament\Resources\StaticPageResource\Pages;

use App\Filament\Resources\StaticPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaticPages extends ListRecords
{
    protected static string $resource = StaticPageResource::class;

    public function getSubheading(): ?string
    {
        return 'Kelola konten halaman Tentang Kami dan Bantuan yang tampil di situs pelanggan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Halaman')
                ->icon('heroicon-o-plus'),
        ];
    }
}
