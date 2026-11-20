<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaticPageResource\Pages;
use App\Models\StaticPage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

class StaticPageResource extends Resource
{
    protected static ?string $model = StaticPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Konten & Tampilan';

    protected static ?string $navigationLabel = 'Halaman Statis';

    protected static ?string $modelLabel = 'Halaman Statis';

    protected static ?string $pluralModelLabel = 'Halaman Statis';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Halaman')
                    ->icon('heroicon-o-document')
                    ->schema([
                        Select::make('slug')
                            ->label('Halaman')
                            ->options(StaticPage::slugOptions())
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->helperText('Slug menentukan halaman frontend yang memakai konten ini.'),

                        TextInput::make('kicker')
                            ->label('Teks Kicker (label kecil di atas judul)')
                            ->maxLength(255)
                            ->placeholder('Contoh: Cerita ShopDress'),

                        TextInput::make('title')
                            ->label('Judul Utama')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Pada halaman Tentang Kami, teks ini tampil sebagai baris pertama judul.'),

                        TextInput::make('highlight')
                            ->label('Bagian Judul Berwarna Aksen')
                            ->maxLength(255)
                            ->helperText('Tampil miring dengan warna brand di bawah judul utama.'),

                        Textarea::make('subtitle')
                            ->label('Paragraf Pembuka')
                            ->rows(3)
                            ->maxLength(1000),

                        Textarea::make('meta_description')
                            ->label('Meta Description (SEO)')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Ringkasan halaman untuk mesin pencari, maksimal sekitar 160 karakter.'),
                    ]),

                Section::make('Media Halaman')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Hero')
                            ->image()
                            ->disk('public')
                            ->directory('pages')
                            ->maxSize(3072)
                            ->imageEditor()
                            ->columnSpanFull()
                            ->helperText('Dipakai pada halaman Tentang Kami. Rekomendasi 1200×1500 px, maksimal 3MB.'),

                        TextInput::make('image_alt')
                            ->label('Alt Text Gambar')
                            ->maxLength(255)
                            ->placeholder('Deskripsi singkat isi gambar untuk SEO'),
                    ]),

                Section::make('Konten Tambahan')
                    ->description('Opsional. Blok ini tampil di bawah hero halaman.')
                    ->icon('heroicon-o-bars-3-bottom-left')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Isi Konten Tambahan')
                            ->columnSpanFull(),
                    ]),

                Section::make('Publikasi')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktifkan Halaman')
                            ->default(true)
                            ->helperText('Bila nonaktif, halaman memakai konten bawaan tema.'),
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
                    ->size(48),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(48)
                    ->weight('bold')
                    ->description(fn (StaticPage $record): ?string => $record->kicker),

                TextColumn::make('slug')
                    ->label('Halaman')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => StaticPage::slugOptions()[$state] ?? $state)
                    ->color('primary'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('slug')
            ->filters([
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
                    ->tooltip('Ubah halaman'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaticPages::route('/'),
            'create' => Pages\CreateStaticPage::route('/create'),
            'edit' => Pages\EditStaticPage::route('/{record}/edit'),
        ];
    }
}
