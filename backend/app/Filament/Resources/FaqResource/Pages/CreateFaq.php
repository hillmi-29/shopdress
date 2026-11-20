<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    public static bool $formActionsAreSticky = true;

    public function getSubheading(): ?string
    {
        return 'Tambahkan pertanyaan dan jawaban baru untuk pelanggan.';
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
                ->url(FaqResource::getUrl('index')),
        ];
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()->label('Simpan FAQ');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Buat Lagi')
            ->color('primary')
            ->outlined();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()->icon('heroicon-o-x-mark'),
            $this->getCreateAnotherFormAction(),
            $this->getCreateFormAction(),
        ];
    }
}
