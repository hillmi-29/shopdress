<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getHeader(): ?View
    {
        return view('filament.pages.partials.resource-list-header', [
            'title' => UserResource::getTitleCasePluralModelLabel(),
            'createUrl' => UserResource::canCreate() ? UserResource::getUrl('create') : null,
            'createLabel' => 'Tambah Pengguna',
            'createIcon' => 'add_circle',
        ]);
    }
}
