<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Katalog Produk';

    protected static ?string $navigationLabel = 'Produk & Baju';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('ProductTabs')
                    ->tabs([
                        Tab::make('Informasi Dasar')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Nama Produk Baju')
                                        ->placeholder('Contoh: Aurelia Silk Gamis Dress')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                    TextInput::make('slug')
                                        ->label('Slug URL')
                                        ->required()
                                        ->unique(Product::class, 'slug', ignoreRecord: true)
                                        ->maxLength(255),
                                ]),

                                Grid::make(2)->schema([
                                    Select::make('category_id')
                                        ->label('Kategori')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    TextInput::make('price')
                                        ->label('Harga Dasar (Rp)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required(),
                                ]),

                                Textarea::make('description')
                                    ->label('Deskripsi Lengkap & Bahan')
                                    ->placeholder('Jelaskan keunggulan bahan, kelenturan, dan petunjuk perawatan baju...')
                                    ->rows(5)
                                    ->required()
                                    ->columnSpanFull(),

                                Grid::make(2)->schema([
                                    Toggle::make('is_active')
                                        ->label('Tampilkan di Katalog (Aktif)')
                                        ->helperText('Jika nonaktif, produk disembunyikan dari katalog pelanggan.')
                                        ->default(true),

                                    Toggle::make('featured')
                                        ->label('Produk Unggulan (Featured)')
                                        ->helperText('Tampilkan produk ini di banner unggulan halaman depan toko.')
                                        ->default(false),
                                ]),
                            ]),

                        Tab::make('Varian Ukuran & Stok')
                            ->icon('heroicon-o-squares-2x2')
                            ->badge(fn (?Product $record) => $record?->variants()->count() ?? null)
                            ->schema([
                                Repeater::make('variants')
                                    ->relationship('variants')
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->default(fn () => 'SD-' . strtoupper(Str::random(6))),

                                        Select::make('size')
                                            ->label('Ukuran')
                                            ->options([
                                                'XS' => 'XS',
                                                'S' => 'S',
                                                'M' => 'M',
                                                'L' => 'L',
                                                'XL' => 'XL',
                                                'XXL' => 'XXL',
                                                'All Size' => 'All Size',
                                            ])
                                            ->required(),

                                        TextInput::make('color')
                                            ->label('Warna')
                                            ->placeholder('Sage Green, Hitam, dsb.')
                                            ->required(),

                                        TextInput::make('stock')
                                            ->label('Jumlah Stok')
                                            ->numeric()
                                            ->default(10)
                                            ->required(),

                                        TextInput::make('additional_price')
                                            ->label('Harga Tambahan')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('+ Rp'),
                                    ])
                                    ->columns(5)
                                    ->defaultItems(1)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Galeri Foto')
                            ->icon('heroicon-o-photo')
                            ->badge(fn (?Product $record) => $record?->images()->count() ?? null)
                            ->schema([
                                Repeater::make('images')
                                    ->relationship('images')
                                    ->schema([
                                        FileUpload::make('image_path')
                                            ->label('Upload Foto Produk')
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->required(),

                                        Toggle::make('is_primary')
                                            ->label('Jadikan Foto Utama / Thumbnail')
                                            ->default(false),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(1)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('primaryImage.image_path')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=100&auto=format&fit=crop&q=80'),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record) => Str::limit($record->description, 50)),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('total_stock')
                    ->label('Status Stok')
                    ->state(fn (Product $record) => $record->total_stock)
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => match (true) {
                        $state <= 0 => 'Habis (0)',
                        $state < 10 => "Menipis ({$state})",
                        default => "Tersedia ({$state})",
                    }),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('featured')
                    ->label('Featured'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Filter Kategori')
                    ->relationship('category', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                TernaryFilter::make('featured')
                    ->label('Status Featured'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
