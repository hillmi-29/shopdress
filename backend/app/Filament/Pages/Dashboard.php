<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\StockForecastingService;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string $view = 'filament.pages.dashboard';

    /**
     * Chart series palette shared by the donut chart and its legend.
     * CSS tokens keep it theme-aware (lighter variants under .dark).
     *
     * @var array<string>
     */
    public const CHART_COLORS = [
        'var(--sd-chart-1)',
        'var(--sd-chart-2)',
        'var(--sd-chart-3)',
        'var(--sd-chart-4)',
        'var(--sd-chart-5)',
        'var(--sd-chart-6)',
        'var(--sd-chart-7)',
        'var(--sd-chart-8)',
    ];

    public function getHeader(): ?View
    {
        return view('filament.pages.partials.dashboard-header', [
            'today' => Carbon::now()->locale('id')->translatedFormat('l, d F Y'),
            'quickActions' => $this->getQuickActions(),
        ]);
    }

    /**
     * @return array<array{label: string, icon: string, url: string, variant: string}>
     */
    public function getQuickActions(): array
    {
        return [
            [
                'label' => 'Kelola Pesanan',
                'icon' => 'receipt_long',
                'url' => OrderResource::getUrl('index'),
                'variant' => 'outline',
            ],
            [
                'label' => 'Analitik Stok',
                'icon' => 'monitoring',
                'url' => StockForecastingPage::getUrl(),
                'variant' => 'outline',
            ],
            [
                'label' => 'Tambah Produk',
                'icon' => 'add',
                'url' => ProductResource::getUrl('create'),
                'variant' => 'primary',
            ],
        ];
    }

    // ------------------------------------------------------------------
    // Alert banner
    // ------------------------------------------------------------------

    public function getLowStockCountProperty(): int
    {
        return count(app(StockForecastingService::class)->getRestockVariantIds());
    }

    public function getLowStockUrlProperty(): string
    {
        return ProductResource::getUrl('index', ['activeTab' => 'low_stock']);
    }

    // ------------------------------------------------------------------
    // KPI cards
    // ------------------------------------------------------------------

    /**
     * @return array<array{label: string, value: string, icon: string, tone: string, trend: array{type: string, text: string}, caption: string}>
     */
    public function getKpisProperty(): array
    {
        $now = Carbon::now();

        $monthRevenue = (float) Order::query()
            ->where('payment_status', PaymentStatus::SETTLED)
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->sum('grand_total');

        $lastMonth = $now->copy()->subMonthNoOverflow();
        $lastMonthRevenue = (float) Order::query()
            ->where('payment_status', PaymentStatus::SETTLED)
            ->whereBetween('created_at', [
                $lastMonth->copy()->startOfMonth(),
                $lastMonth->copy()->endOfMonth(),
            ])
            ->sum('grand_total');

        $weekOrders = Order::query()
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->count();
        $previousWeekOrders = Order::query()
            ->whereBetween('created_at', [
                $now->copy()->subDays(13)->startOfDay(),
                $now->copy()->subDays(7)->endOfDay(),
            ])
            ->count();

        $products = Product::query()->count();
        $newProducts = Product::query()
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        $customers = User::query()->where('role', 'customer')->count();
        $newCustomers = User::query()
            ->where('role', 'customer')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        return [
            [
                'label' => 'Total Penjualan',
                'value' => 'Rp '.number_format($monthRevenue, 0, ',', '.'),
                'icon' => 'payments',
                'tone' => 'primary',
                ...$this->trend($monthRevenue, $lastMonthRevenue, 'vs bulan lalu'),
            ],
            [
                'label' => 'Pesanan Baru',
                'value' => number_format($weekOrders, 0, ',', '.'),
                'icon' => 'shopping_bag',
                'tone' => 'info',
                ...$this->trend($weekOrders, $previousWeekOrders, '7 hari terakhir'),
            ],
            [
                'label' => 'Total Produk',
                'value' => number_format($products, 0, ',', '.'),
                'icon' => 'checkroom',
                'tone' => 'violet',
                'trend' => [
                    'type' => 'neutral',
                    'text' => '+'.$newProducts.' baru',
                    'icon' => 'inventory_2',
                ],
                'caption' => 'katalog aktif',
            ],
            [
                'label' => 'Pelanggan Terdaftar',
                'value' => number_format($customers, 0, ',', '.'),
                'icon' => 'group',
                'tone' => 'neutral',
                'trend' => [
                    'type' => 'neutral',
                    'text' => '+'.$newCustomers.' bulan ini',
                    'icon' => 'person_add',
                ],
                'caption' => 'total pelanggan',
            ],
        ];
    }

    // ------------------------------------------------------------------
    // 30-day sales trend (line chart)
    // ------------------------------------------------------------------

    /**
     * Builds the SVG geometry for the 30-day revenue line chart, comparing
     * the current period against the previous 30 days.
     *
     * @return array{current_path: string, previous_path: string, area_path: string, peak: array{x: float, y: float, label: string}|null, y_labels: array<array{value: string, y: int}>, x_labels: array<array{label: string, peak: bool}>}
     */
    public function getSalesTrendProperty(): array
    {
        $today = Carbon::today();

        $orders = Order::query()
            ->where('payment_status', PaymentStatus::SETTLED)
            ->where('created_at', '>=', $today->copy()->subDays(59)->startOfDay())
            ->get(['created_at', 'grand_total']);

        $current = [];
        $previous = [];

        for ($i = 0; $i < 30; $i++) {
            $current[$today->copy()->subDays(29 - $i)->toDateString()] = 0.0;
            $previous[$today->copy()->subDays(59 - $i)->toDateString()] = 0.0;
        }

        foreach ($orders as $order) {
            $key = $order->created_at->toDateString();

            if (array_key_exists($key, $current)) {
                $current[$key] += (float) $order->grand_total;
            } elseif (array_key_exists($key, $previous)) {
                $previous[$key] += (float) $order->grand_total;
            }
        }

        $currentValues = array_values($current);
        $previousValues = array_values($previous);

        // Chart canvas: x 40→740, y 30→180 (0 baseline), 4 grid lines.
        $left = 40.0;
        $right = 740.0;
        $top = 30.0;
        $bottom = 180.0;

        $max = max(max($currentValues), max($previousValues), 0.0);
        $scaleMax = $this->niceCeiling($max);

        $toPoints = function (array $values) use ($left, $right, $top, $bottom, $scaleMax): array {
            $count = count($values);
            $step = $count > 1 ? ($right - $left) / ($count - 1) : 0;

            return array_map(
                fn (int $i, float $value): array => [
                    'x' => round($left + ($step * $i), 2),
                    'y' => round($bottom - (($value / $scaleMax) * ($bottom - $top)), 2),
                ],
                array_keys($values),
                $values,
            );
        };

        $currentPoints = $toPoints($currentValues);
        $previousPoints = $toPoints($previousValues);

        $peak = null;
        $peakIndex = null;
        $peakValue = max($currentValues);

        if ($peakValue > 0) {
            $peakIndex = array_search($peakValue, $currentValues, true);
            $peak = [
                'x' => $currentPoints[$peakIndex]['x'],
                'y' => $currentPoints[$peakIndex]['y'],
                'label' => Carbon::parse(array_keys($current)[$peakIndex])->locale('id')->translatedFormat('d M').' · Rp '.number_format($peakValue, 0, ',', '.'),
            ];
        }

        $yLabels = [];

        for ($i = 0; $i < 4; $i++) {
            $value = $scaleMax * (1 - ($i / 3));
            $yLabels[] = [
                'value' => $this->shortCurrency($value),
                'y' => (int) ($top + ((($bottom - $top) / 3) * $i)),
            ];
        }

        $xLabels = [];

        foreach (array_keys($current) as $i => $date) {
            if ($i % 5 !== 0 && $i !== 29) {
                continue;
            }

            $xLabels[] = [
                'label' => 'Tgl '.Carbon::parse($date)->format('d'),
                'peak' => $peakIndex !== null && $i === $peakIndex,
            ];
        }

        return [
            'current_path' => $this->smoothPath($currentPoints),
            'previous_path' => $this->smoothPath($previousPoints),
            'area_path' => $this->smoothPath($currentPoints).sprintf(' L %.2f,%.2f L %.2f,%.2f Z', $right, $bottom, $left, $bottom),
            'peak' => $peak,
            'y_labels' => $yLabels,
            'x_labels' => $xLabels,
            'grid' => [$top, $top + (($bottom - $top) / 3), $top + (2 * (($bottom - $top) / 3)), $bottom],
        ];
    }

    // ------------------------------------------------------------------
    // Category sales share (donut chart)
    // ------------------------------------------------------------------

    /**
     * @return array{segments: array<array{name: string, units: int, pct: float, color: string, dash: float, offset: float}>, total: int, circumference: float}
     */
    public function getCategoryShareProperty(): array
    {
        $rows = OrderItem::query()
            ->leftJoin('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', fn ($query) => $query->where('status', '!=', OrderStatus::CANCELLED))
            ->selectRaw("COALESCE(categories.name, 'Tanpa Kategori') as category_name, SUM(order_items.quantity) as units")
            ->groupBy('category_name')
            ->orderByDesc('units')
            ->get();

        $total = (int) $rows->sum('units');

        // Donuts read best with <=6 slices: merge the long tail into "Lainnya".
        if ($rows->count() > 6) {
            $tail = $rows->slice(5);
            $rows = $rows->take(5)->push((object) [
                'category_name' => 'Lainnya',
                'units' => (int) $tail->sum('units'),
            ]);
        }

        $radius = 70.0;
        $circumference = 2 * M_PI * $radius;

        $segments = [];
        $offset = 0.0;

        foreach ($rows->values() as $index => $row) {
            $units = (int) $row->units;
            $dash = $total > 0 ? ($circumference * $units) / $total : 0.0;

            $segments[] = [
                'name' => $row->category_name,
                'units' => $units,
                'pct' => $total > 0 ? round(($units / $total) * 100) : 0,
                'color' => self::CHART_COLORS[$index % count(self::CHART_COLORS)],
                'dash' => round($dash, 2),
                'offset' => round(-$offset, 2),
            ];

            $offset += $dash;
        }

        return [
            'segments' => $segments,
            'total' => $total,
            'circumference' => round($circumference, 2),
        ];
    }

    // ------------------------------------------------------------------
    // Latest orders table
    // ------------------------------------------------------------------

    /**
     * @return array<array{number: string, url: string, customer_name: string, customer_sub: string, initials: string, avatar_tone: string, item_summary: string, extra_items: int, total: string, status_label: string, status_tone: string, time: string}>
     */
    public function getLatestOrdersProperty(): array
    {
        $orders = Order::query()
            ->with([
                'user:id,name,email',
                'items:id,order_id,product_name,quantity',
            ])
            ->latest()
            ->limit(5)
            ->get();

        $avatarTones = ['purple', 'blue', 'emerald', 'amber', 'red'];
        $rows = [];

        foreach ($orders as $index => $order) {
            $firstItem = $order->items->first();

            $rows[] = [
                'number' => $order->order_number,
                'url' => OrderResource::getUrl('edit', ['record' => $order]),
                'customer_name' => $order->user?->name ?? $order->recipient_name ?? 'Tanpa Akun',
                'customer_sub' => $order->user?->email ?? $order->recipient_phone ?? '-',
                'initials' => $this->initials($order->user?->name ?? $order->recipient_name ?? 'NA'),
                'avatar_tone' => $avatarTones[$index % count($avatarTones)],
                'item_summary' => $firstItem?->product_name ?? 'Belum ada item',
                'extra_items' => max($order->items->count() - 1, 0),
                'total' => 'Rp '.number_format((float) $order->grand_total, 0, ',', '.'),
                'status_label' => $order->status->label(),
                'status_tone' => $this->statusTone($order->status),
                'time' => $this->humanTime($order->created_at),
            ];
        }

        return $rows;
    }

    // ------------------------------------------------------------------
    // Top selling products
    // ------------------------------------------------------------------

    /**
     * @return array<array{name: string, category: string, chip_tone: string, price: string, units: int, bar_pct: int, image_url: string|null}>
     */
    public function getTopProductsProperty(): array
    {
        $rows = OrderItem::query()
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', fn ($query) => $query->where('status', '!=', OrderStatus::CANCELLED))
            ->selectRaw('products.id as product_id, products.name as product_name, products.price as price, categories.name as category_name, SUM(order_items.quantity) as units')
            ->groupBy('products.id', 'products.name', 'products.price', 'categories.name')
            ->orderByDesc('units')
            ->limit(5)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $maxUnits = (int) ($rows->max('units') ?: 1);
        $chipTones = ['purple', 'emerald', 'amber', 'sky', 'slate'];

        $images = Product::query()
            ->with('primaryImage:id,product_id,image_path')
            ->whereIn('id', $rows->pluck('product_id'))
            ->get(['id'])
            ->mapWithKeys(function (Product $product): array {
                $path = $product->primaryImage?->image_path;

                return [
                    $product->id => $path && Storage::disk('public')->exists($path)
                        ? Storage::disk('public')->url($path)
                        : null,
                ];
            });

        return $rows->map(fn ($row): array => [
            'name' => $row->product_name,
            'category' => $row->category_name ?? 'Tanpa Kategori',
            'chip_tone' => $chipTones[crc32((string) $row->category_name) % count($chipTones)],
            'price' => 'Rp '.number_format((float) $row->price, 0, ',', '.'),
            'units' => (int) $row->units,
            'bar_pct' => max(5, (int) round(((int) $row->units / $maxUnits) * 100)),
            'image_url' => $images[$row->product_id] ?? null,
        ])->all();
    }

    public function getRestockUrlProperty(): string
    {
        return StockForecastingPage::getUrl();
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * @return array{trend: array{type: string, text: string, icon: string}, caption: string}
     */
    protected function trend(float $current, float $previous, string $caption): array
    {
        if ($previous <= 0) {
            return [
                'trend' => [
                    'type' => $current > 0 ? 'up' : 'neutral',
                    'text' => $current > 0 ? 'Baru' : 'Belum ada data',
                    'icon' => $current > 0 ? 'trending_up' : 'remove',
                ],
                'caption' => $caption,
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return [
            'trend' => [
                'type' => $percentage >= 0 ? 'up' : 'down',
                'text' => ($percentage >= 0 ? '+' : '').number_format($percentage, 1, ',', '.').'%',
                'icon' => $percentage >= 0 ? 'trending_up' : 'trending_down',
            ],
            'caption' => $caption,
        ];
    }

    protected function statusTone(OrderStatus $status): string
    {
        return match ($status) {
            OrderStatus::PENDING => 'warning',
            OrderStatus::PAID, OrderStatus::COMPLETED => 'success',
            OrderStatus::PROCESSING => 'info',
            OrderStatus::SHIPPED => 'primary',
            OrderStatus::CANCELLED => 'danger',
        };
    }

    protected function humanTime(Carbon $time): string
    {
        if ($time->isToday()) {
            return 'Hari ini, '.$time->format('H:i');
        }

        if ($time->isYesterday()) {
            return 'Kemarin, '.$time->format('H:i');
        }

        return $time->locale('id')->translatedFormat('d M Y, H:i');
    }

    protected function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];

        return strtoupper(collect($words)->take(2)->map(fn (string $word): string => mb_substr($word, 0, 1))->implode(''));
    }

    /**
     * Rounds a value up to a pleasant axis ceiling (1/2/2.5/5/10 x 10^n).
     */
    protected function niceCeiling(float $value): float
    {
        if ($value <= 0) {
            return 1.0;
        }

        $exponent = floor(log10($value));
        $magnitude = 10 ** $exponent;
        $normalized = $value / $magnitude;

        $step = match (true) {
            $normalized <= 1 => 1,
            $normalized <= 2 => 2,
            $normalized <= 2.5 => 2.5,
            $normalized <= 5 => 5,
            default => 10,
        };

        return (float) ($step * $magnitude);
    }

    protected function shortCurrency(float $value): string
    {
        if ($value >= 1_000_000) {
            $millions = round($value / 1_000_000, 1);

            $formatted = fmod($millions, 1.0) === 0.0
                ? number_format($millions, 0, ',', '.')
                : number_format($millions, 1, ',', '.');

            return $formatted.' jt';
        }

        if ($value >= 1_000) {
            return number_format(round($value / 1_000), 0, ',', '.').' rb';
        }

        return (string) (int) round($value);
    }

    /**
     * Converts a list of points into a smoothed cubic bezier path
     * (Catmull-Rom style control points), matching the design's soft curves.
     *
     * @param  array<array{x: float, y: float}>  $points
     */
    protected function smoothPath(array $points): string
    {
        if ($points === []) {
            return '';
        }

        $path = sprintf('M %.2f,%.2f', $points[0]['x'], $points[0]['y']);
        $count = count($points);

        for ($i = 0; $i < $count - 1; $i++) {
            $p0 = $points[max($i - 1, 0)];
            $p1 = $points[$i];
            $p2 = $points[$i + 1];
            $p3 = $points[min($i + 2, $count - 1)];

            $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) / 6;
            $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) / 6;
            $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) / 6;
            $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) / 6;

            $path .= sprintf(' C %.2f,%.2f %.2f,%.2f %.2f,%.2f', $cp1x, $cp1y, $cp2x, $cp2y, $p2['x'], $p2['y']);
        }

        return $path;
    }
}
