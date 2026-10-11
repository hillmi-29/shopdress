<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Services\StockForecastingService;
use Filament\Widgets\ChartWidget;

class CategoryRestockDemandChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Stok Fisik vs Saran Restock per Kategori';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $service = app(StockForecastingService::class);
        $forecasts = $service->getForecastAnalysis();

        $categories = Category::all();

        $labels = [];
        $currentStockData = [];
        $recommendedRestockData = [];

        foreach ($categories as $cat) {
            $catForecasts = $forecasts->where('category_name', $cat->name);

            $labels[] = $cat->name;
            $currentStockData[] = (int) $catForecasts->sum('current_stock');
            $recommendedRestockData[] = (int) $catForecasts->sum('recommended_restock');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Stok Fisik Tersedia (Pcs)',
                    'data' => $currentStockData,
                    'backgroundColor' => '#0ea5e9', // Sky blue
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Saran Order Restock (Pcs)',
                    'data' => $recommendedRestockData,
                    'backgroundColor' => '#FF5D00', // ShopDress Orange
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'font' => [
                            'size' => 10,
                        ],
                    ],
                ],
            ],
        ];
    }
}
