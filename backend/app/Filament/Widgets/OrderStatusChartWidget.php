<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Pesanan';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $pending = Order::where('status', OrderStatus::PENDING)->count();
        $paid = Order::where('status', OrderStatus::PAID)->count();
        $processing = Order::where('status', OrderStatus::PROCESSING)->count();
        $shipped = Order::where('status', OrderStatus::SHIPPED)->count();
        $completed = Order::where('status', OrderStatus::COMPLETED)->count();
        $cancelled = Order::where('status', OrderStatus::CANCELLED)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => [$pending, $paid, $processing, $shipped, $completed, $cancelled],
                    'backgroundColor' => [
                        '#f59e0b', // warning (pending)
                        '#10b981', // success (paid)
                        '#0ea5e9', // info (processing)
                        '#6366f1', // indigo (shipped)
                        '#059669', // emerald (completed)
                        '#ef4444', // danger (cancelled)
                    ],
                ],
            ],
            'labels' => [
                'Menunggu Bayar',
                'Sudah Dibayar',
                'Diproses',
                'Dikirim',
                'Selesai',
                'Dibatalkan',
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
                ],
            ],
        ];
    }
}
