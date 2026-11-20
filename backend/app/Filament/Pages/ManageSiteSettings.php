<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Konten & Tampilan';

    protected static ?string $navigationLabel = 'Pengaturan Toko';

    protected static ?string $title = 'Pengaturan Toko';

    protected static ?string $slug = 'pengaturan-toko';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::values());
    }

    public function getSubheading(): ?string
    {
        return 'Kelola identitas toko, kontak, ongkos kirim, jam operasional, media sosial, dan pengumuman yang tampil di seluruh halaman pelanggan.';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Toko')
                    ->description('Nama dan warna brand yang dipakai di navbar, footer, judul halaman, dan email.')
                    ->icon('heroicon-o-sparkles')
                    ->columns(2)
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Nama Toko')
                            ->required()
                            ->maxLength(60)
                            ->helperText('Tampil di navbar, footer, judul halaman, dan email.'),

                        ColorPicker::make('brand_color')
                            ->label('Warna Brand')
                            ->required()
                            ->helperText('Dipakai untuk tombol, aksen, dan header email.'),
                    ]),

                Section::make('Kontak Toko')
                    ->description('Informasi ini tampil di footer, halaman bantuan, dan tombol WhatsApp.')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        TextInput::make('store_address')
                            ->label('Alamat Toko')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('store_email')
                            ->label('Email Customer Care')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('store_phone')
                            ->label('Nomor Telepon (tampilan)')
                            ->tel()
                            ->maxLength(50)
                            ->helperText('Ditampilkan di footer. Boleh memakai spasi dan tanda hubung.'),

                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(30)
                            ->helperText('Format 62812xxxxxxx. Tanda +, spasi, dan tanda hubung dibersihkan otomatis.'),

                        TextInput::make('operational_days')
                            ->label('Hari Operasional (footer)')
                            ->maxLength(100),

                        TextInput::make('operational_hours')
                            ->label('Jam Operasional (footer)')
                            ->maxLength(100),

                        TextInput::make('help_operational_hours')
                            ->label('Jam Layanan di Halaman Bantuan')
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengiriman')
                    ->description('Ongkos kirim tetap yang diterapkan ke setiap pesanan.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Dipakai di checkout, halaman bantuan, dan perhitungan total pesanan.'),
                    ]),

                Section::make('Media Sosial')
                    ->description('Tautan kosong tidak akan ditampilkan di footer.')
                    ->icon('heroicon-o-share')
                    ->columns(2)
                    ->schema([
                        TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/shopdress'),

                        TextInput::make('social_tiktok')
                            ->label('TikTok')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@shopdress'),

                        TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/shopdress'),
                    ]),

                Section::make('Footer & Layanan')
                    ->icon('heroicon-o-bars-3-bottom-left')
                    ->schema([
                        Textarea::make('footer_description')
                            ->label('Deskripsi Singkat Brand di Footer')
                            ->rows(3)
                            ->maxLength(500),

                        TextInput::make('return_policy')
                            ->label('Teks Garansi Retur')
                            ->maxLength(100)
                            ->helperText('Contoh: 7 hari tukar ukuran.'),

                        Textarea::make('payment_methods')
                            ->label('Metode Pembayaran di Footer')
                            ->rows(2)
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Pisahkan dengan koma. Contoh: BCA, MANDIRI, BNI, BRI, QRIS, GOPAY.'),
                    ]),

                Section::make('Pengumuman Situs')
                    ->description('Bar tipis di atas navbar. Hanya tampil bila toggle aktif dan teks diisi.')
                    ->icon('heroicon-o-megaphone')
                    ->columns(2)
                    ->schema([
                        Toggle::make('announcement_enabled')
                            ->label('Tampilkan Bar Pengumuman'),

                        TextInput::make('announcement_text')
                            ->label('Teks Pengumuman')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Contoh: Gratis ongkir untuk belanja minimal Rp250.000'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (array_keys(SiteSetting::defaults()) as $key) {
            $value = $data[$key] ?? null;

            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            if ($key === 'whatsapp_number') {
                $value = SiteSetting::normalizeWhatsappNumber($value) ?? '';
            }

            if ($key === 'brand_color') {
                $value = '#'.ltrim((string) $value, '#');
            }

            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        $this->form->fill(SiteSetting::values());

        Notification::make()
            ->success()
            ->title('Pengaturan toko disimpan')
            ->body('Perubahan langsung tampil di halaman pelanggan.')
            ->send();
    }
}
