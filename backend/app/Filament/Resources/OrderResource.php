<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Transaksi & Pesanan';

    protected static ?string $navigationLabel = 'Daftar Pesanan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ringkasan Pesanan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('order_number')
                                ->label('Nomor Pesanan')
                                ->disabled(),

                            Select::make('status')
                                ->label('Status Pesanan')
                                ->options([
                                    OrderStatus::PENDING->value => OrderStatus::PENDING->label(),
                                    OrderStatus::PAID->value => OrderStatus::PAID->label(),
                                    OrderStatus::PROCESSING->value => OrderStatus::PROCESSING->label(),
                                    OrderStatus::SHIPPED->value => OrderStatus::SHIPPED->label(),
                                    OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->label(),
                                    OrderStatus::CANCELLED->value => OrderStatus::CANCELLED->label(),
                                ])
                                ->required(),

                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options([
                                    PaymentStatus::UNPAID->value => PaymentStatus::UNPAID->label(),
                                    PaymentStatus::EXPIRED->value => PaymentStatus::EXPIRED->label(),
                                    PaymentStatus::SETTLED->value => PaymentStatus::SETTLED->label(),
                                    PaymentStatus::FAILED->value => PaymentStatus::FAILED->label(),
                                ])
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('tracking_number')
                                ->label('Nomor Resi Pengiriman (No. Resi)')
                                ->placeholder('Contoh: JNE123456789 / JNT987654321'),

                            TextInput::make('recipient_name')
                                ->label('Nama Penerima'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('recipient_phone')
                                ->label('No. Telepon Penerima'),

                            Textarea::make('shipping_address')
                                ->label('Alamat Pengiriman')
                                ->rows(2),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('total_amount')
                                ->label('Subtotal Produk')
                                ->prefix('Rp')
                                ->numeric()
                                ->disabled(),

                            TextInput::make('shipping_cost')
                                ->label('Ongkir')
                                ->prefix('Rp')
                                ->numeric()
                                ->disabled(),

                            TextInput::make('grand_total')
                                ->label('Grand Total')
                                ->prefix('Rp')
                                ->numeric()
                                ->disabled(),
                        ]),
                    ]),

                Section::make('Rincian Item Baju yang Dipesan')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('product_name')
                                    ->label('Nama Produk')
                                    ->disabled(),

                                TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->prefix('Rp')
                                    ->disabled(),

                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->disabled(),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('Rp')
                                    ->disabled(),
                            ])
                            ->columns(4)
                            ->addable(false)
                            ->deletable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

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

                TextColumn::make('tracking_number')
                    ->label('Resi')
                    ->placeholder('- Belum Dikirim -')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status Pesanan')
                    ->options([
                        OrderStatus::PENDING->value => OrderStatus::PENDING->label(),
                        OrderStatus::PAID->value => OrderStatus::PAID->label(),
                        OrderStatus::PROCESSING->value => OrderStatus::PROCESSING->label(),
                        OrderStatus::SHIPPED->value => OrderStatus::SHIPPED->label(),
                        OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->label(),
                        OrderStatus::CANCELLED->value => OrderStatus::CANCELLED->label(),
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Filter Pembayaran')
                    ->options([
                        PaymentStatus::UNPAID->value => PaymentStatus::UNPAID->label(),
                        PaymentStatus::SETTLED->value => PaymentStatus::SETTLED->label(),
                        PaymentStatus::EXPIRED->value => PaymentStatus::EXPIRED->label(),
                        PaymentStatus::FAILED->value => PaymentStatus::FAILED->label(),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('inputResi')
                    ->label('Kirim Resi')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->form([
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi Pengiriman')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'tracking_number' => $data['tracking_number'],
                            'status' => OrderStatus::SHIPPED,
                        ]);

                        if ($record->user && $record->user->email) {
                            Mail::to($record->user->email)->queue(new OrderStatusUpdatedMail($record));
                        }

                        Notification::make()
                            ->title('Resi Berhasil Diperbarui')
                            ->body("Pesanan #{$record->order_number} ditandai SHIPPED dan email terkirim.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Order $record) {
                        $phone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $record->recipient_phone ?? $record->user->phone ?? ''));
                        return "https://wa.me/{$phone}?text=" . urlencode("Halo {$record->recipient_name}, kami dari ShopDress mengonfirmasi pesanan #{$record->order_number} Anda.");
                    }, shouldOpenInNewTab: true)
                    ->visible(fn (Order $record) => ! empty($record->recipient_phone ?? $record->user->phone)),

                Tables\Actions\Action::make('markCompleted')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Pesanan Selesai')
                    ->modalDescription('Apakah Anda yakin pesanan ini sudah sampai dan diterima oleh pembeli?')
                    ->visible(fn (Order $record) => $record->status === OrderStatus::SHIPPED)
                    ->action(function (Order $record) {
                        $record->update(['status' => OrderStatus::COMPLETED]);
                        Notification::make()
                            ->title('Pesanan Selesai')
                            ->body("Pesanan #{$record->order_number} telah ditandai Selesai.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
