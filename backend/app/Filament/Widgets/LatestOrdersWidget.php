<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(6)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->weight('bold')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin'),

                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->description(fn (Order $record) => $record->recipient_phone ?? $record->user->phone ?? '-'),

                TextColumn::make('items_count')
                    ->label('Total Item')
                    ->counts('items')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('grand_total')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->color(fn (Order $record): string => $record->status->color())
                    ->formatStateUsing(fn (Order $record): string => $record->status->label()),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (Order $record): string => $record->payment_status->color())
                    ->formatStateUsing(fn (Order $record): string => $record->payment_status->label()),

                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->since(),
            ])
            ->actions([
                Action::make('open')
                    ->label('Kelola')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
