<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    public function getHeader(): ?View
    {
        return view('filament.pages.partials.resource-list-header', [
            'title' => CouponResource::getTitleCasePluralModelLabel(),
            'createUrl' => CouponResource::canCreate() ? CouponResource::getUrl('create') : null,
            'createLabel' => 'Tambah Kupon',
            'createIcon' => 'add_circle',
        ]);
    }
}
