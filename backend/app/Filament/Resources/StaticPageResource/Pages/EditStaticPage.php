<?php

namespace App\Filament\Resources\StaticPageResource\Pages;

use App\Filament\Resources\StaticPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaticPage extends EditRecord
{
    protected static string $resource = StaticPageResource::class;

    public static bool $formActionsAreSticky = true;

    public function getSubheading(): ?string
    {
        return 'Perbarui judul, pembuka, gambar, dan konten tambahan halaman.';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(StaticPageResource::getUrl('index')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()->icon('heroicon-o-x-mark'),
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()->label('Simpan Perubahan');
    }
}
