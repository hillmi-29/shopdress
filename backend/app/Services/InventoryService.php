<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Check if requested quantity is available for the given variant.
     */
    public function checkStock(int $variantId, int $quantity): bool
    {
        $variant = ProductVariant::find($variantId);
        return $variant && $variant->stock >= $quantity;
    }

    /**
     * Reserve stock for all items in the order.
     * Throws Exception if any item lacks sufficient stock.
     */
    public function reserveStock(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);

                    if (! $variant || $variant->stock < $item->quantity) {
                        throw new Exception("Stok untuk varian {$item->product_name} tidak mencukupi.");
                    }

                    $variant->decrement('stock', $item->quantity);
                }
            }
        });
    }

    /**
     * Restore stock back when order is cancelled, expired, or failed.
     */
    public function restoreStock(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            }
        });
    }
}
