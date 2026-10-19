<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Katalog Produk';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi & Detail Kategori')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->placeholder('Contoh: Gamis & Abaya, Kemeja Linen')
                                    ->required()
                                    ->validationAttribute('nama kategori')
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
                                    ] : [])
                                    ->helperText('Nama kategori akan tampil pada navigasi toko online dan filter katalog.'),

                                TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->prefix('shopdress.id/kategori/')
                                    ->required()
                                    ->validationAttribute('slug URL')
                                    ->unique(Category::class, 'slug', ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText(fn (string $operation): string => $operation === 'create'
                                        ? 'Terisi otomatis dari nama kategori (SEO-friendly slug) — bisa diubah manual.'
                                        : 'Ubah slug hanya bila perlu; tautan kategori di katalog akan ikut berubah.'),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi ringkas kategori untuk optimasi SEO dan tampilan katalog...')
                            ->rows(4)
                            ->maxLength(300)
                            ->live(debounce: 700)
                            ->hint(fn (?string $state): string => mb_strlen((string) $state).'/300 karakter')
                            ->helperText('Deskripsi singkat akan dipakai sebagai meta description Google dan teks kartu kategori (maks. 300 karakter).'),

                        FileUpload::make('image')
                            ->label('Gambar Thumbnail Kategori')
                            ->image()
                            ->directory('categories')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->required()
                            ->validationAttribute('gambar thumbnail')
                            ->helperText('Rekomendasi 600×600 px (PNG/JPG), maksimal 2MB.'),
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
                    ->size(56)
                    ->defaultImageUrl(asset('images/placeholder-category.svg')),

                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->url(fn (Category $record): string => static::productsFilterUrl($record))
                    ->description(fn (Category $record): ?string => $record->description !== null
                        ? Str::limit($record->description, 64)
                        : null),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->extraCellAttributes(['class' => 'sd-cell-slug']),

                TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->counts('products')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-tag')
                    ->formatStateUsing(fn (int $state): string => $state.' produk')
                    ->url(fn (Category $record): string => static::productsFilterUrl($record))
                    ->tooltip('Lihat daftar produk di kategori ini'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Ubah kategori'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus kategori'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * URL daftar Produk dengan filter kategori terisi otomatis.
     */
    protected static function productsFilterUrl(Category $record): string
    {
        return ProductResource::getUrl('index', [
            'tableFilters' => [
                'category_id' => ['value' => $record->getKey()],
            ],
        ], isAbsolute: false);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
