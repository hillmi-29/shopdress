<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Services\StockForecastingService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class StockForecastingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'AI & Machine Learning';

    protected static ?string $navigationLabel = 'Prediksi Stok & Demand';

    protected static ?string $title = 'AI Demand Forecasting & Restock Alert';

    protected static ?string $slug = 'stock-forecasting';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.stock-forecasting-page';

    public ?string $selectedCategory = null;

    public ?string $selectedRisk = null;

    public array $metrics = [];

    public function mount(StockForecastingService $forecastingService): void
    {
        $this->metrics = $forecastingService->getSummaryMetrics();
    }

    public function getCategoriesProperty(): Collection
    {
        return Category::select('id', 'name', 'slug')->get();
    }

    public function getForecastDataProperty(): Collection
    {
        $service = app(StockForecastingService::class);
        $risk = ($this->selectedRisk === 'ALL' || empty($this->selectedRisk)) ? null : $this->selectedRisk;
        $category = ($this->selectedCategory === 'ALL' || empty($this->selectedCategory)) ? null : $this->selectedCategory;

        return $service->getForecastAnalysis($category, $risk);
    }

    public function updateFilter(): void
    {
        // Re-renders the reactive view
    }

    public function generatePoTemplate(array $item): string
    {
        $text = "Halo Konveksi,\nKami ingin memesan restock produksi ShopDress:\n";
        $text .= "• Produk: {$item['product_name']}\n";
        $text .= "• Varian: {$item['color']} / Size {$item['size']} (SKU: {$item['sku']})\n";
        $text .= "• Jumlah Pesanan Restock: {$item['recommended_restock']} pcs\n";
        $text .= "• Sisa Stok Toko: {$item['current_stock']} pcs (Status: {$item['risk_label']})\n";
        $text .= "Mohon konfirmasi ketersediaan bahan dan estimasi pengerjaan. Terima kasih!";

        return "https://wa.me/?text=" . urlencode($text);
    }
}
