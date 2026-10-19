<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * Bottom action bar sticks to the viewport while scrolling,
     * matching the mockup's fixed footer.
     */
    public static bool $formActionsAreSticky = true;

    public function getTitle(): string
    {
        return 'Tambah Produk Baru';
    }

    public function getSubheading(): ?string
    {
        return 'Lengkapi informasi produk busana, varian ukuran, warna, harga, serta inventaris stok.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(ProductResource::getUrl('index')),
        ];
    }

    /**
     * Mockup order: Batal on the left, save actions on the right.
     *
     * @return array<Actions\Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()->icon('heroicon-o-x-mark'),
            $this->getCreateAnotherFormAction(),
            $this->getCreateFormAction(),
        ];
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()->label('Simpan Produk');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Buat Lagi')
            ->color('primary')
            ->outlined();
    }
}
