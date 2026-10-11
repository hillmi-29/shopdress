<?php

namespace App\Filament\Widgets;

use App\Services\StockForecastingService;
use Filament\Widgets\ChartWidget;

class StockRiskChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Risiko Ketahanan Stok';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $service = app(StockForecastingService::class);
        $forecasts = $service->getForecastAnalysis();

        $critical = $forecasts->where('risk', 'CRITICAL')->count();
        $warning = $forecasts->where('risk', 'WARNING')->count();
        $slowMoving = $forecasts->where('risk', 'SLOW_MOVING')->count();
        $safe = $forecasts->where('risk', 'SAFE')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Varian',
                    'data' => [$critical, $warning, $slowMoving, $safe],
                    'backgroundColor' => [
                        '#ef4444', // Red (Critical)
                        '#f59e0b', // Amber (Warning)
                        '#64748b', // Slate (Slow Moving)
                        '#10b981', // Emerald (Safe)
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => [
                'Kritis (<= 3 Hari)',
                'Waspada (4 - 7 Hari)',
                'Stok Mengendap (Slow Moving)',
                'Stok Aman (> 7 Hari)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
            'cutout' => '70%',
        ];
    }
}
