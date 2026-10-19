<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Bottom action bar sticks to the viewport while scrolling,
     * matching the mockup's fixed footer.
     */
    public static bool $formActionsAreSticky = true;

    public function getSubheading(): ?string
    {
        return 'Tambahkan kategori busana baru ke katalog produk ShopDress.';
    }

    /**
     * Setelah simpan, kembali ke daftar kategori agar alur kerja tetap cepat.
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
                ->url(CategoryResource::getUrl('index')),
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
        return parent::getCreateFormAction()->label('Simpan Kategori');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Buat Lagi')
            ->color('primary')
            ->outlined();
    }
}
