<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan & Pendapatan (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return Carbon::today()->subDays($daysAgo);
        });

        $labels = $days->map(fn ($date) => $date->translatedFormat('d M'))->toArray();

        $revenueData = $days->map(function ($date) {
            return (float) Order::where('payment_status', PaymentStatus::SETTLED)
                ->whereDate('created_at', $date)
                ->sum('grand_total');
        })->toArray();

        $orderCountData = $days->map(function ($date) {
            return Order::whereDate('created_at', $date)->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $revenueData,
                    'borderColor' => '#FF5D00',
                    'backgroundColor' => 'rgba(255, 93, 0, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => '(value) => "Rp " + new Intl.NumberFormat("id-ID").format(value)',
                    ],
                ],
            ],
        ];
    }
}
