<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * Bottom action bar sticks to the viewport while scrolling,
     * matching the mockup's fixed footer.
     */
    public static bool $formActionsAreSticky = true;

    public function getTitle(): string
    {
        return 'Ubah Produk';
    }

    public function getSubheading(): ?string
    {
        return 'Perbarui informasi produk, varian ukuran & warna, serta galeri foto.';
    }

    /**
     * Setelah simpan, kembali ke daftar produk agar alur kerja tetap cepat.
     */
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
                ->url(ProductResource::getUrl('index')),

            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Mockup order: Batal on the left, save action on the right.
     *
     * @return array<Actions\Action>
     */
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
