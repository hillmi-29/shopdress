<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\StockForecastingService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Katalog Produk';

    protected static ?string $navigationLabel = 'Produk & Baju';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                    'lg' => 12,
                ])
                    ->schema([
                        Group::make()
                            ->columnSpan(['lg' => 8])
                            ->schema([
                                Section::make('Informasi Utama & Deskripsi')
                                    ->icon('heroicon-o-pencil-square')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Produk')
                                            ->placeholder('Contoh: Aurelia Silk Gamis Dress')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                if ($operation === 'create') {
                                                    $set('slug', Str::slug((string) $state));
                                                }
                                            })
                                            ->extraInputAttributes(fn (string $operation): array => $operation === 'create' ? [
                                                // Isi slug instan di sisi klien, tanpa menunggu roundtrip Livewire.
                                                'x-on:input' => <<<'JS'
                                                    const slug = document.getElementById('data.slug');
                                                    if (slug) {
                                                        slug.value = $event.target.value.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-');
                                                        slug.dispatchEvent(new Event('input', { bubbles: true }));
                                                    }
                                                    JS,
                                            ] : []),

                                        TextInput::make('slug')
                                            ->label('Slug URL')
                                            ->prefix('shopdress.id/produk/')
                                            ->required()
                                            ->unique(Product::class, 'slug', ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText(fn (string $operation): string => $operation === 'create'
                                                ? 'Terisi otomatis dari nama produk — bisa diubah manual.'
                                                : 'Ubah slug hanya bila perlu; tautan halaman produk akan ikut berubah.'),

                                        Textarea::make('description')
                                            ->label('Deskripsi Lengkap Produk')
                                            ->placeholder('Jelaskan keunggulan bahan, kelenturan, dan petunjuk perawatan baju...')
                                            ->rows(6)
                                            ->required(),
                                    ]),

                                Section::make('Media & Galeri Foto Produk')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Repeater::make('images')
                                            ->relationship('images')
                                            ->label('Foto Produk')
                                            ->addActionLabel('Tambah Foto')
                                            ->schema([
                                                FileUpload::make('image_path')
                                                    ->label('Upload Foto Produk')
                                                    ->image()
                                                    ->directory('products')
                                                    ->imageEditor()
                                                    ->required(),

                                                Toggle::make('is_primary')
                                                    ->label('Jadikan Foto Utama')
                                                    ->default(false),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(1),
                                    ]),

                                Section::make('Varian & Manajemen Stok')
                                    ->icon('heroicon-o-squares-2x2')
                                    ->schema([
                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->label('Varian')
                                            ->addActionLabel('Tambah Varian Produk Baru')
                                            ->schema([
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

                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->required()
                                                    ->default(fn () => 'SD-'.strtoupper(Str::random(6))),

                                                TextInput::make('additional_price')
                                                    ->label('Harga Tambahan')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->prefix('+ Rp'),

                                                TextInput::make('stock')
                                                    ->label('Stok Fisik')
                                                    ->numeric()
                                                    ->default(10)
                                                    ->required(),
                                            ])
                                            ->columns(5)
                                            ->defaultItems(1),
                                    ]),
                            ]),

                        Group::make()
                            ->columnSpan(['lg' => 4])
                            ->schema([
                                Section::make('Status & Visibilitas')
                                    ->icon('heroicon-o-eye')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Aktif di Katalog')
                                            ->helperText('Produk tampil dan dapat dibeli pelanggan.')
                                            ->default(true),

                                        Toggle::make('featured')
                                            ->label('Produk Unggulan')
                                            ->helperText('Tampilkan produk ini di banner unggulan halaman depan toko.')
                                            ->default(false),
                                    ]),

                                Section::make('Kategori & Klasifikasi')
                                    ->icon('heroicon-o-tag')
                                    ->schema([
                                        Select::make('category_id')
                                            ->label('Kategori Busana')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->helperText('Pilih dari kategori yang sudah ada.')
                                            ->hintAction(
                                                Action::make('kelolaKategori')
                                                    ->label('Kelola kategori')
                                                    ->icon('heroicon-m-arrow-top-right-on-square')
                                                    ->url(fn (): string => CategoryResource::getUrl('index'))
                                                    ->openUrlInNewTab()
                                            ),
                                    ]),

                                Section::make('Penetapan Harga')
                                    ->icon('heroicon-o-banknotes')
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Harga Dasar (Jual)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['category', 'primaryImage', 'variants']))
            ->columns([
                ImageColumn::make('primaryImage.image_path')
                    ->label('Foto')
                    ->square()
                    ->size(56)
                    ->state(function (Product $record): ?string {
                        $path = $record->primaryImage?->image_path;

                        return $path && Storage::disk('public')->exists($path) ? $path : null;
                    })
                    ->defaultImageUrl(asset('images/placeholder-product.svg')),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(function (Product $record): string {
                        $count = $record->variants->count();

                        if ($count === 0) {
                            return 'Belum ada varian';
                        }

                        $sizes = $record->variants->pluck('size')->filter()->unique()->implode(', ');

                        return $count.' varian'.($sizes !== '' ? ' ('.$sizes.')' : '');
                    }),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('total_stock')
                    ->label('Status Stok')
                    ->state(fn (Product $record) => $record->total_stock)
                    ->badge()
                    ->color(fn (Product $record, int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        static::restockVariantCount($record) > 0 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(function (Product $record, int $state): string {
                        if ($state <= 0) {
                            return 'Habis (0)';
                        }

                        $restockCount = static::restockVariantCount($record);

                        return $restockCount > 0
                            ? "Perlu Restok ({$restockCount} varian)"
                            : "Tersedia ({$state})";
                    }),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('featured')
                    ->label('Unggulan'),

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

                SelectFilter::make('stock_status')
                    ->label('Status Stok')
                    ->options([
                        'ready' => 'Stok Aman',
                        'low' => 'Perlu Restok',
                        'empty' => 'Stok Habis',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $status = $data['value'] ?? null;

                        return filled($status) ? static::stockStatusQuery($query, $status) : $query;
                    }),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                TernaryFilter::make('featured')
                    ->label('Status Unggulan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah produk'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus produk'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Stock buckets shared by the list filter and the header stat cards.
     * Exclusive buckets: ready = every variant above 5, low = some stock
     * left with a variant at 5 or below, empty = no stock at all.
     */
    public static function stockStatusQuery(Builder $query, string $status): Builder
    {
        $restockVariantIds = static::restockVariantIds();

        return match ($status) {
            'ready' => $query
                ->whereHas('variants')
                ->whereDoesntHave('variants', fn (Builder $variantQuery) => $variantQuery->whereIn('id', $restockVariantIds)),

            'low' => $query
                ->whereHas('variants', fn (Builder $variantQuery) => $variantQuery->whereIn('id', $restockVariantIds))
                ->whereHas('variants', fn (Builder $variantQuery) => $variantQuery->where('stock', '>', 0)),

            'empty' => $query
                ->whereDoesntHave('variants', fn (Builder $variantQuery) => $variantQuery->where('stock', '>', 0)),

            default => $query,
        };
    }

    /**
     * ID varian yang sudah mencapai titik restok (ROP) — sumber tunggal
     * definisi "perlu restok" untuk tabel, filter, tab, dan dashboard.
     *
     * @return array<int>
     */
    public static function restockVariantIds(): array
    {
        static $restockVariantIds = null;

        return $restockVariantIds ??= app(StockForecastingService::class)->getRestockVariantIds();
    }

    /**
     * Jumlah varian produk yang sudah mencapai titik restok (ROP).
     */
    public static function restockVariantCount(Product $product): int
    {
        $restockVariantIds = static::restockVariantIds();

        return $product->variants
            ->filter(fn (ProductVariant $variant): bool => in_array($variant->id, $restockVariantIds, true))
            ->count();
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
