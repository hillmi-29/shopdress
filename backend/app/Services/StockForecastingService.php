<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Analisis prediksi stok per varian.
 *
 * Metode yang dipakai (praktik umum inventory management):
 * - Permintaan harian: Weighted Moving Average (70% penjualan 7 hari, 30% penjualan 30 hari).
 * - Kelas ABC: kontribusi pendapatan 30 hari (A = 80% teratas, B = 15% berikutnya, C = sisanya).
 * - Stok pengaman: Z x sigma permintaan harian x akar waktu tunggu.
 * - Titik restok (ROP): permintaan harian x waktu tunggu + stok pengaman.
 * - Stok mengendap: ketahanan stok melebihi 60 hari (tanpa penjualan 30 hari
 *   hanya dihitung bila stok > 15 pcs agar stok sisa kecil tidak ter-flag).
 */
class StockForecastingService
{
    /**
     * Waktu tunggu (lead time) konveksi/supplier dalam hari.
     */
    protected int $leadTimeDays = 7;

    /**
     * Faktor service level (Z) per kelas ABC: A = 95%, B/C = 90%.
     *
     * @var array<string, float>
     */
    protected array $serviceLevelZ = [
        'A' => 1.65,
        'B' => 1.28,
        'C' => 1.28,
    ];

    /**
     * Buffer cadangan (dalam hari permintaan) saat deviasi permintaan belum bisa dihitung.
     */
    protected int $fallbackSafetyStockDays = 3;

    /**
     * Ambang ketahanan stok (hari) yang dianggap stok mengendap (overstock).
     */
    protected int $slowMovingCoverDays = 60;

    /**
     * Analyze sales history and generate demand forecast for all variants.
     */
    public function getForecastAnalysis(?string $categorySlug = null, ?string $riskLevel = null): Collection
    {
        $variants = ProductVariant::with(['product.category', 'product.primaryImage'])
            ->whereHas('product', function ($q) use ($categorySlug) {
                $q->where('is_active', true);
                if ($categorySlug) {
                    $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug));
                }
            })
            ->get();

        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Fetch sales data within the last 30 days
        $salesHistory = OrderItem::whereHas('order', function ($q) use ($thirtyDaysAgo) {
            $q->whereIn('status', [OrderStatus::PAID, OrderStatus::PROCESSING, OrderStatus::SHIPPED, OrderStatus::COMPLETED])
                ->where('created_at', '>=', $thirtyDaysAgo);
        })
            ->get()
            ->groupBy('product_variant_id');

        $variantClasses = $this->classifyAbcRevenue($salesHistory);

        $forecasts = $variants->map(function (ProductVariant $variant) use ($salesHistory, $sevenDaysAgo, $variantClasses) {
            $variantSales = $salesHistory->get($variant->id, collect());

            $salesLast30Days = $variantSales->sum('quantity');
            $salesLast7Days = $variantSales->filter(fn ($item) => $item->created_at >= $sevenDaysAgo)->sum('quantity');

            // Weighted Moving Average (70% bobot 7 hari terakhir, 30% basis bulanan)
            $dailyRate7Days = $salesLast7Days / 7;
            $dailyRate30Days = $salesLast30Days / 30;

            if ($salesLast30Days == 0) {
                $weightedDailyDemand = 0.05; // Base minimal demand prior
            } else {
                $weightedDailyDemand = ($dailyRate7Days * 0.7) + ($dailyRate30Days * 0.3);
            }

            $currentStock = $variant->stock;
            $abcClass = $variantClasses[$variant->id] ?? 'C';
            $demandDeviation = $this->calculateDailyDemandDeviation($variantSales);

            // Stok pengaman: Z x sigma permintaan harian x akar waktu tunggu.
            // Varian tanpa penjualan 30 hari tidak diberi buffer restock.
            $safetyStock = 0;
            if ($salesLast30Days > 0) {
                $serviceLevel = $this->serviceLevelZ[$abcClass];
                $safetyStock = $demandDeviation > 0
                    ? (int) ceil($serviceLevel * $demandDeviation * sqrt($this->leadTimeDays))
                    : max(1, (int) ceil($dailyRate30Days * $this->fallbackSafetyStockDays));
            }

            // Titik restok (reorder point): permintaan harian x waktu tunggu + stok pengaman.
            $effectiveDailyDemand = $salesLast30Days > 0 ? $weightedDailyDemand : 0.0;
            $reorderPoint = (int) ceil($effectiveDailyDemand * $this->leadTimeDays) + $safetyStock;

            // Days until stockout calculation
            if ($weightedDailyDemand > 0) {
                $daysUntilStockout = (int) floor($currentStock / $weightedDailyDemand);
            } else {
                $daysUntilStockout = 999;
            }

            // Determine Risk Level
            if ($currentStock <= 0) {
                $risk = 'CRITICAL';
                $riskLabel = 'Habis (0 pcs)';
                $riskColor = 'danger';
            } elseif ($currentStock <= $safetyStock) {
                $risk = 'CRITICAL';
                $riskLabel = 'Kritis (stok di bawah batas pengaman)';
                $riskColor = 'danger';
            } elseif ($currentStock <= $reorderPoint) {
                $risk = 'WARNING';
                $riskLabel = "Waspada (sudah melewati titik restok, sekitar {$daysUntilStockout} hari tersisa)";
                $riskColor = 'warning';
            } elseif ($daysUntilStockout > $this->slowMovingCoverDays
                && ($salesLast30Days > 0 || $currentStock > 15)) {
                $risk = 'SLOW_MOVING';
                $riskLabel = 'Stok Mengendap (ketahanan > '.$this->slowMovingCoverDays.' hari)';
                $riskColor = 'gray';
            } else {
                $risk = 'SAFE';
                $riskLabel = "Aman ({$daysUntilStockout} hari)";
                $riskColor = 'success';
            }

            // Saran restock: dipicu saat stok menyentuh titik restok.
            // Varian tanpa penjualan 30 hari tidak disarankan restock.
            $recommendedRestockQty = 0;
            if ($salesLast30Days > 0 && $currentStock <= $reorderPoint) {
                $recommendedRestockQty = max(1, $reorderPoint - $currentStock);
            }

            // Minimum production batch for convection is usually 12 pcs (1 lusin)
            if ($recommendedRestockQty > 0 && $recommendedRestockQty < 12) {
                $recommendedRestockQty = 12;
            }

            return [
                'variant_id' => $variant->id,
                'product_id' => $variant->product->id,
                'product_name' => $variant->product->name,
                'category_name' => $variant->product->category->name ?? '-',
                'image_url' => $this->resolveImageUrl($variant->product->primaryImage?->image_path),
                'sku' => $variant->sku,
                'size' => $variant->size,
                'color' => $variant->color,
                'current_stock' => $currentStock,
                'sales_30d' => $salesLast30Days,
                'sales_7d' => $salesLast7Days,
                'daily_demand' => round($weightedDailyDemand, 2),
                'days_until_stockout' => $daysUntilStockout,
                'abc_class' => $abcClass,
                'safety_stock' => $safetyStock,
                'reorder_point' => $reorderPoint,
                'demand_deviation' => round($demandDeviation, 2),
                'service_level_z' => $salesLast30Days > 0 ? $this->serviceLevelZ[$abcClass] : null,
                'risk' => $risk,
                'risk_label' => $riskLabel,
                'risk_color' => $riskColor,
                'recommended_restock' => $recommendedRestockQty,
            ];
        });

        if ($riskLevel) {
            $forecasts = $forecasts->where('risk', $riskLevel);
        }

        // Sort by risk priority (CRITICAL first, then WARNING)
        return $forecasts->sortBy(function ($item) {
            return match ($item['risk']) {
                'CRITICAL' => 1,
                'WARNING' => 2,
                'SLOW_MOVING' => 3,
                'SAFE' => 4,
                default => 5,
            };
        })->values();
    }

    /**
     * URL gambar varian bila file-nya ada; null agar blade memakai placeholder ikon.
     */
    protected function resolveImageUrl(?string $path): ?string
    {
        return $path && Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : null;
    }

    /**
     * Klasifikasi ABC dari kontribusi pendapatan 30 hari:
     * A = varian sampai kumulatif 80% pendapatan, B = sampai 95%, C = sisanya.
     *
     * @param  Collection<int|string, Collection<int, OrderItem>>  $salesHistory
     * @return array<int, string>
     */
    protected function classifyAbcRevenue(Collection $salesHistory): array
    {
        $revenueByVariant = $salesHistory
            ->map(fn (Collection $items): float => (float) $items->sum(fn (OrderItem $item): float => (float) $item->quantity * (float) $item->price))
            ->sortDesc();

        $totalRevenue = (float) $revenueByVariant->sum();
        $classes = [];
        $cumulativeRevenue = 0.0;

        foreach ($revenueByVariant as $variantId => $revenue) {
            $cumulativeShare = $totalRevenue > 0 ? $cumulativeRevenue / $totalRevenue : 0.0;

            $classes[(int) $variantId] = match (true) {
                $cumulativeShare < 0.80 => 'A',
                $cumulativeShare < 0.95 => 'B',
                default => 'C',
            };

            $cumulativeRevenue += (float) $revenue;
        }

        return $classes;
    }

    /**
     * Simpangan baku permintaan harian (sigma) dari penjualan 30 hari terakhir.
     *
     * @param  Collection<int, OrderItem>  $variantSales
     */
    protected function calculateDailyDemandDeviation(Collection $variantSales): float
    {
        if ($variantSales->isEmpty()) {
            return 0.0;
        }

        $dailyUnits = [];
        for ($dayOffset = 29; $dayOffset >= 0; $dayOffset--) {
            $dailyUnits[Carbon::now()->subDays($dayOffset)->toDateString()] = 0.0;
        }

        foreach ($variantSales as $sale) {
            $dayKey = $sale->created_at->toDateString();
            if (array_key_exists($dayKey, $dailyUnits)) {
                $dailyUnits[$dayKey] += (float) $sale->quantity;
            }
        }

        $averageDailyUnits = array_sum($dailyUnits) / count($dailyUnits);
        if ($averageDailyUnits <= 0) {
            return 0.0;
        }

        $variance = array_sum(array_map(
            fn (float $units): float => ($units - $averageDailyUnits) ** 2,
            $dailyUnits
        )) / count($dailyUnits);

        return sqrt($variance);
    }

    /**
     * Get summary metrics for the ML Dashboard.
     */
    public function getSummaryMetrics(): array
    {
        $all = $this->getForecastAnalysis();

        $criticalCount = $all->where('risk', 'CRITICAL')->count();
        $warningCount = $all->where('risk', 'WARNING')->count();
        $slowMovingCount = $all->where('risk', 'SLOW_MOVING')->count();
        $totalRecommendedRestock = $all->sum('recommended_restock');

        return [
            'critical_count' => $criticalCount,
            'warning_count' => $warningCount,
            'slow_moving_count' => $slowMovingCount,
            'total_restock_units' => $totalRecommendedRestock,
        ];
    }

    /**
     * ID varian yang stoknya sudah mencapai atau melewati titik restok (termasuk stok habis).
     * Sumber tunggal definisi "perlu restok" untuk dashboard dan daftar produk.
     *
     * @return array<int>
     */
    public function getRestockVariantIds(): array
    {
        return $this->getForecastAnalysis()
            ->filter(fn (array $item): bool => $item['current_stock'] <= $item['reorder_point'])
            ->pluck('variant_id')
            ->all();
    }
}
