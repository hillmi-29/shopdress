<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayRevenue = Order::where('payment_status', PaymentStatus::SETTLED)
            ->whereDate('created_at', Carbon::today())
            ->sum('grand_total');

        $totalRevenue = Order::where('payment_status', PaymentStatus::SETTLED)
            ->sum('grand_total');

        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $pendingOrders = Order::where('status', OrderStatus::PENDING)->count();
        $processingOrders = Order::whereIn('status', [OrderStatus::PAID, OrderStatus::PROCESSING])->count();

        $lowStockVariants = ProductVariant::where('stock', '<=', 5)->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return [
            Stat::make('Total Omzet Lunas', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Hari ini: Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([15, 22, 18, 30, 25, 40, 52]),

            Stat::make('Perlu Diproses Segera', "{$processingOrders} Pesanan")
                ->description("{$pendingOrders} menunggu pembayaran")
                ->descriptionIcon('heroicon-m-clock')
                ->color($processingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Peringatan Stok Menipis', "{$lowStockVariants} Varian")
                ->description('Stok <= 5 pcs segera restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockVariants > 0 ? 'danger' : 'success'),

            Stat::make('Pelanggan Terdaftar', number_format($totalCustomers, 0, ',', '.'))
                ->description('Total customer aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
