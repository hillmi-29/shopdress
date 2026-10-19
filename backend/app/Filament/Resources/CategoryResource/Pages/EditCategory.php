<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Bottom action bar sticks to the viewport while scrolling,
     * matching the mockup's fixed footer.
     */
    public static bool $formActionsAreSticky = true;

    public function getSubheading(): ?string
    {
        return 'Perbarui nama, slug, deskripsi, dan gambar thumbnail kategori.';
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
