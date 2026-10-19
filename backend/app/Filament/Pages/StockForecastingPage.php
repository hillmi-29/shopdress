<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Services\StockForecastingService;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class StockForecastingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Analitik & Prediksi';

    protected static ?string $navigationLabel = 'Prediksi Stok & Permintaan';

    protected static ?string $title = 'Prediksi Stok & Permintaan';

    protected static ?string $slug = 'stock-forecasting';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.stock-forecasting-page';

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public ?string $selectedCategory = null;

    public ?string $selectedRisk = null;

    public ?string $search = '';

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

        $data = $service->getForecastAnalysis($category, $risk);

        if (! empty($this->search)) {
            $searchQuery = strtolower(trim($this->search));
            $data = $data->filter(function ($item) use ($searchQuery) {
                return str_contains(strtolower($item['product_name']), $searchQuery) ||
                       str_contains(strtolower($item['sku']), $searchQuery) ||
                       str_contains(strtolower($item['color']), $searchQuery) ||
                       str_contains(strtolower($item['size']), $searchQuery);
            });
        }

        return $data->values();
    }

    public function getRiskDistributionProperty(): array
    {
        $service = app(StockForecastingService::class);
        $forecasts = $service->getForecastAnalysis();
        $total = $forecasts->count();

        $critical = $forecasts->where('risk', 'CRITICAL')->count();
        $warning = $forecasts->where('risk', 'WARNING')->count();
        $slowMoving = $forecasts->where('risk', 'SLOW_MOVING')->count();
        $safe = $forecasts->where('risk', 'SAFE')->count();

        $safePct = $total > 0 ? round(($safe / $total) * 100) : 0;
        $slowMovingPct = $total > 0 ? round(($slowMoving / $total) * 100) : 0;
        $warningPct = $total > 0 ? round(($warning / $total) * 100) : 0;
        $criticalPct = $total > 0 ? round(($critical / $total) * 100) : 0;

        return [
            'total' => $total,
            'critical' => $critical,
            'critical_pct' => $criticalPct,
            'warning' => $warning,
            'warning_pct' => $warningPct,
            'slow_moving' => $slowMoving,
            'slow_moving_pct' => $slowMovingPct,
            'safe' => $safe,
            'safe_pct' => $safePct,
        ];
    }

    public function getCategoryStatsProperty(): Collection
    {
        $service = app(StockForecastingService::class);
        $forecasts = $service->getForecastAnalysis();
        $categories = Category::all();
        $maxStock = 1;

        $stats = $categories->map(function ($cat) use ($forecasts) {
            $catForecasts = $forecasts->where('category_name', $cat->name);
            $stock = (int) $catForecasts->sum('current_stock');
            $restock = (int) $catForecasts->sum('recommended_restock');

            return [
                'name' => $cat->name,
                'stock' => $stock,
                'restock' => $restock,
            ];
        });

        $highest = $stats->max('stock') ?: 1;

        return $stats->map(function ($item) use ($highest) {
            $item['bar_width'] = min(100, max(5, round(($item['stock'] / $highest) * 85)));
            $item['restock_width'] = $item['restock'] > 0 ? min(30, max(5, round(($item['restock'] / $highest) * 85))) : 0;

            return $item;
        });
    }

    public function setRiskFilter(?string $risk): void
    {
        $this->selectedRisk = $risk;
    }

    public function recomputeAi(): void
    {
        $service = app(StockForecastingService::class);
        $this->metrics = $service->getSummaryMetrics();
        $this->dispatch('notify', [
            'status' => 'success',
            'message' => 'Kalkulasi WMA, titik restok & stok pengaman berhasil diperbarui.',
        ]);
    }

    public function resetFilters(): void
    {
        $this->selectedCategory = null;
        $this->selectedRisk = null;
        $this->search = '';
    }

    public function generatePoTemplate(array $item): string
    {
        $text = "Halo Konveksi,\nKami ingin memesan restock produksi ShopDress:\n";
        $text .= "- Produk: {$item['product_name']}\n";
        $text .= "- Varian: {$item['color']} / Ukuran {$item['size']} (SKU: {$item['sku']})\n";
        $text .= "- Jumlah Pesanan Restock: {$item['recommended_restock']} pcs\n";
        $text .= "- Sisa Stok Saat Ini: {$item['current_stock']} pcs\n";
        $text .= "- Tingkat Kebutuhan: {$item['risk_label']}\n";
        $text .= 'Mohon konfirmasi ketersediaan bahan dan perkiraan jadwal pengerjaan. Terima kasih.';

        return 'https://wa.me/?text='.urlencode($text);
    }
}
