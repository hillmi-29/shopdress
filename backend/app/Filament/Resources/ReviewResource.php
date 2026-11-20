<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Konten & Tampilan';

    protected static ?string $navigationLabel = 'Ulasan & Testimoni';

    protected static ?string $modelLabel = 'Ulasan';

    protected static ?string $pluralModelLabel = 'Ulasan & Testimoni';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ulasan Pelanggan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                Placeholder::make('customer')
                                    ->label('Pelanggan')
                                    ->content(fn (?Review $record): string => $record?->user?->name ?? '—'),

                                Placeholder::make('product')
                                    ->label('Produk')
                                    ->content(fn (?Review $record): string => $record?->product?->name ?? '—'),
                            ]),

                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                5 => '5 bintang — Sangat puas',
                                4 => '4 bintang — Puas',
                                3 => '3 bintang — Cukup',
                                2 => '2 bintang — Kurang',
                                1 => '1 bintang — Tidak puas',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('comment')
                            ->label('Isi Ulasan')
                            ->rows(5)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media Ulasan')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Placeholder::make('review_photos')
                            ->label('Foto')
                            ->content(function (?Review $record) {
                                $urls = $record?->photo_urls ?? [];

                                if (empty($urls)) {
                                    return '—';
                                }

                                return new HtmlString(collect($urls)
                                    ->map(fn (string $url) => '<a href="'.e($url).'" target="_blank" rel="noopener"><img src="'.e($url).'" alt="Foto ulasan" style="height:88px;width:88px;object-fit:cover;border-radius:10px;margin:0 8px 8px 0;border:1px solid #e5e7eb;"></a>')
                                    ->implode(''));
                            }),

                        Placeholder::make('review_video')
                            ->label('Video')
                            ->content(fn (?Review $record) => $record?->video_url
                                ? new HtmlString('<video src="'.e($record->video_url).'" controls preload="metadata" style="max-height:180px;border-radius:10px;"></video>')
                                : '—'),
                    ])
                    ->visible(fn (?Review $record): bool => (bool) ($record?->photos || $record?->video)),

                Section::make('Tampilan & Publikasi')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label('Tampilkan di Storefront')
                                    ->default(true)
                                    ->helperText('Ulasan yang disembunyikan tidak tampil di halaman produk maupun testimoni beranda.'),

                                Toggle::make('is_featured')
                                    ->label('Jadikan Testimoni Beranda')
                                    ->default(false)
                                    ->helperText('Beranda menampilkan maksimal 2 testimoni unggulan sesuai urutan di bawah.'),

                                TextInput::make('sort_order')
                                    ->label('Urutan Testimoni')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka kecil tampil lebih dulu pada bagian testimoni beranda.'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Review $record): ?string => $record->user?->email),

                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(36)
                    ->toggleable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => $state.'/5')
                    ->color(fn (int $state): string => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'primary',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('comment')
                    ->label('Ulasan')
                    ->searchable()
                    ->limit(70)
                    ->wrap(),

                ToggleColumn::make('is_featured')
                    ->label('Testimoni Beranda'),

                ToggleColumn::make('is_visible')
                    ->label('Tampil'),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('is_visible')
                    ->label('Status Tampil')
                    ->options([
                        '1' => 'Tampil',
                        '0' => 'Disembunyikan',
                    ]),

                SelectFilter::make('is_featured')
                    ->label('Testimoni Beranda')
                    ->options([
                        '1' => 'Unggulan',
                        '0' => 'Bukan unggulan',
                    ]),

                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        5 => '5 bintang',
                        4 => '4 bintang',
                        3 => '3 bintang',
                        2 => '2 bintang',
                        1 => '1 bintang',
                    ]),

                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah ulasan'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus ulasan'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('show')
                        ->label('Tampilkan')
                        ->icon('heroicon-o-eye')
                        ->action(function ($records): void {
                            $records->each->update(['is_visible' => true]);

                            Notification::make()
                                ->title('Ulasan berhasil ditampilkan')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('hide')
                        ->label('Sembunyikan')
                        ->icon('heroicon-o-eye-slash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            $records->each->update([
                                'is_visible' => false,
                                'is_featured' => false,
                            ]);

                            Notification::make()
                                ->title('Ulasan berhasil disembunyikan')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('feature')
                        ->label('Jadikan Testimoni')
                        ->icon('heroicon-o-star')
                        ->action(function ($records): void {
                            $records->each->update([
                                'is_visible' => true,
                                'is_featured' => true,
                            ]);

                            Notification::make()
                                ->title('Ulasan dijadikan testimoni beranda')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('Hapus dari Testimoni')
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->action(function ($records): void {
                            $records->each->update(['is_featured' => false]);

                            Notification::make()
                                ->title('Ulasan dikeluarkan dari testimoni beranda')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'user:id,name,email',
            'product:id,name',
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
