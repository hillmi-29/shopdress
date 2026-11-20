<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten & Tampilan';

    protected static ?string $navigationLabel = 'Banner & Hero';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banner & Hero';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konten Banner')
                    ->icon('heroicon-o-megaphone')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                Select::make('position')
                                    ->label('Posisi Banner')
                                    ->options([
                                        Banner::POSITION_HERO => 'Hero Beranda (slider)',
                                        Banner::POSITION_PROMO => 'Banner Promo Beranda',
                                    ])
                                    ->default(Banner::POSITION_HERO)
                                    ->required()
                                    ->helperText('Hero tampil sebagai slider di paling atas beranda. Promo tampil sebagai banner kupon di tengah beranda.'),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka kecil tampil lebih dulu.'),
                            ]),

                        TextInput::make('eyebrow')
                            ->label('Teks Eyebrow (label kecil di atas judul)')
                            ->placeholder('Contoh: Koleksi 2026')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Gunakan {highlight} untuk menandai bagian judul yang diberi warna aksen, contoh: Diskon {highlight} untuk belanja pertamamu.'),

                        TextInput::make('highlight')
                            ->label('Teks Highlight')
                            ->placeholder('Contoh: 20% atau paling percaya diri')
                            ->maxLength(255)
                            ->helperText('Menggantikan penanda {highlight} pada judul.'),

                        Textarea::make('subtitle')
                            ->label('Subjudul / Deskripsi')
                            ->rows(3)
                            ->maxLength(500),

                        TextInput::make('coupon_code')
                            ->label('Kode Kupon (opsional)')
                            ->placeholder('Contoh: SHOPDRESSNEW')
                            ->maxLength(50)
                            ->helperText('Bila kode ini muncul di subjudul, kode akan ditampilkan sebagai chip kupon.'),
                    ]),

                Section::make('Media')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Banner')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->maxSize(3072)
                            ->imageEditor()
                            ->columnSpanFull()
                            ->helperText('Rekomendasi 1200×900 px (JPG/PNG/WebP), maksimal 3MB.'),

                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextInput::make('image_alt')
                                    ->label('Alt Text Gambar')
                                    ->placeholder('Deskripsi singkat isi gambar untuk SEO')
                                    ->maxLength(255),

                                TextInput::make('badge')
                                    ->label('Badge di Atas Gambar')
                                    ->placeholder('Contoh: New arrival / Hemat 20% / 20%')
                                    ->maxLength(100),

                                TextInput::make('caption')
                                    ->label('Caption Gambar')
                                    ->placeholder('Contoh: The Soft Ivory Edit')
                                    ->maxLength(255),

                                TextInput::make('caption_sub')
                                    ->label('Sub-caption Gambar')
                                    ->placeholder('Contoh: Satin flow dress')
                                    ->maxLength(255),

                                Select::make('badge_style')
                                    ->label('Gaya Badge')
                                    ->options([
                                        'light' => 'Terang dengan titik status',
                                        'accent' => 'Aksen warna penuh',
                                        'light_check' => 'Terang dengan ikon centang',
                                    ])
                                    ->default('light'),
                            ]),
                    ]),

                Section::make('Tombol Aksi')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextInput::make('cta_label')
                                    ->label('Label Tombol Utama')
                                    ->placeholder('Contoh: Belanja Sekarang')
                                    ->maxLength(100),

                                TextInput::make('cta_url')
                                    ->label('URL Tombol Utama')
                                    ->placeholder('Contoh: /produk atau https://...')
                                    ->maxLength(255),

                                TextInput::make('secondary_label')
                                    ->label('Label Tautan Sekunder')
                                    ->placeholder('Contoh: Lihat promo bulan ini')
                                    ->maxLength(100),

                                TextInput::make('secondary_url')
                                    ->label('URL Tautan Sekunder')
                                    ->placeholder('Contoh: /promo')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Elemen Tambahan')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Textarea::make('footnote')
                            ->label('Poin Tambahan / Catatan Kecil')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Satu poin per baris. Pada banner hero tampil sebagai poin keunggulan; pada banner promo tampil sebagai catatan kaki.'),

                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                Toggle::make('show_rating')
                                    ->label('Tampilkan Blok Rating Pelanggan')
                                    ->helperText('Hanya dipakai pada banner hero.'),

                                TextInput::make('rating_text')
                                    ->label('Teks Rating')
                                    ->placeholder('Contoh: 4.9/5 dari 1.200+ pelanggan')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Publikasi')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktifkan Banner')
                            ->default(true)
                            ->helperText('Banner nonaktif tidak akan tampil di beranda pelanggan.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->square()
                    ->size(56),

                TextColumn::make('position')
                    ->label('Posisi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Banner::POSITION_HERO => 'Hero',
                        Banner::POSITION_PROMO => 'Promo',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => $state === Banner::POSITION_PROMO ? 'warning' : 'primary'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(48)
                    ->weight('bold')
                    ->description(fn (Banner $record): ?string => $record->eyebrow),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('position')
                    ->label('Filter Posisi')
                    ->options([
                        Banner::POSITION_HERO => 'Hero Beranda',
                        Banner::POSITION_PROMO => 'Banner Promo',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah banner'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus banner'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('position')->orderBy('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
