<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Promosi & Diskon';

    protected static ?string $navigationLabel = 'Kupon & Voucher';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kupon')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('Kode Kupon')
                                ->required()
                                ->unique(Coupon::class, 'code', ignoreRecord: true)
                                ->maxLength(50),

                            Select::make('discount_type')
                                ->label('Jenis Diskon')
                                ->options([
                                    'fixed' => 'Potongan Tetap (Nominal Rp)',
                                    'percent' => 'Persentase (%)',
                                ])
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('discount_amount')
                                ->label('Nilai Diskon')
                                ->numeric()
                                ->required(),

                            TextInput::make('min_spend')
                                ->label('Minimum Belanja (Rp)')
                                ->numeric()
                                ->default(0),
                        ]),

                        Grid::make(2)->schema([
                            DateTimePicker::make('expires_at')
                                ->label('Kedaluwarsa Pada'),

                            TextInput::make('usage_limit')
                                ->label('Batas Maksimal Penggunaan')
                                ->numeric()
                                ->placeholder('Kosongkan untuk tanpa batas'),
                        ]),

                        Toggle::make('is_active')
                            ->label('Status Kupon Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Kupon')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('discount_type')
                    ->label('Tipe')
                    ->badge(),

                TextColumn::make('discount_amount')
                    ->label('Nilai Diskon')
                    ->formatStateUsing(fn (Coupon $record): string => $record->discount_type === 'percent'
                        ? "{$record->discount_amount}%"
                        : 'Rp ' . number_format($record->discount_amount, 0, ',', '.')),

                TextColumn::make('min_spend')
                    ->label('Min. Belanja')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('used_count')
                    ->label('Terpakai')
                    ->formatStateUsing(fn (Coupon $record): string => "{$record->used_count} / " . ($record->usage_limit ?? '∞')),

                TextColumn::make('expires_at')
                    ->label('Kadaluwarsa')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Tanpa Batas'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
