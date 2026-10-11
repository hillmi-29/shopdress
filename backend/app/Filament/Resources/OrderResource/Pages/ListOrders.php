<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pesanan'),

            'unpaid' => Tab::make('Menunggu Bayar')
                ->badge(Order::where('payment_status', PaymentStatus::UNPAID)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', PaymentStatus::UNPAID)),

            'processing' => Tab::make('Perlu Diproses')
                ->badge(Order::whereIn('status', [OrderStatus::PAID, OrderStatus::PROCESSING])->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [OrderStatus::PAID, OrderStatus::PROCESSING])),

            'shipped' => Tab::make('Dalam Pengiriman')
                ->badge(Order::where('status', OrderStatus::SHIPPED)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::SHIPPED)),

            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::COMPLETED)),

            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::CANCELLED)),
        ];
    }
}
