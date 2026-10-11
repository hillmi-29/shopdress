<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->setupConfig();
    }

    protected function setupConfig(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    /**
     * Generate Midtrans Snap Token for an Order.
     */
    public function createSnapToken(Order $order): string
    {
        $this->setupConfig();

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) ($item->product_variant_id ?? $item->id),
                'price' => (int) round($item->price),
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name, 0, 50),
            ];
        }

        // Add shipping cost if exists
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round($order->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        // Add discount as negative item if exists
        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round($order->discount_amount),
                'quantity' => 1,
                'name' => 'Diskon Voucher',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) round($order->grand_total),
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name ?? $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->recipient_phone ?? $order->user->phone ?? '08123456789',
                'billing_address' => [
                    'first_name' => $order->recipient_name ?? $order->user->name,
                    'address' => $order->shipping_address,
                ],
                'shipping_address' => [
                    'first_name' => $order->recipient_name ?? $order->user->name,
                    'address' => $order->shipping_address,
                ],
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
            return $snapToken;
        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), ['order' => $order->order_number]);
            throw $e;
        }
    }

    /**
     * Verify Midtrans Signature Key.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Handle incoming webhook notification payload from Midtrans.
     */
    public function handleNotification(array $payload): array
    {
        $orderNumber = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (! $orderNumber) {
            return ['status' => 'error', 'message' => 'Missing order_id'];
        }

        return DB::transaction(function () use ($orderNumber, $statusCode, $grossAmount, $signatureKey, $transactionStatus, $fraudStatus) {
            $order = Order::where('order_number', $orderNumber)->lockForUpdate()->first();
            if (! $order) {
                return ['status' => 'error', 'message' => 'Order not found'];
            }

            // Verify signature if provided
            if ($signatureKey && ! $this->verifySignature($orderNumber, $statusCode, $grossAmount, $signatureKey)) {
                Log::warning('Midtrans Webhook Invalid Signature', ['order_id' => $orderNumber]);
                return ['status' => 'error', 'message' => 'Invalid signature'];
            }

            // 1. Idempotency check for PAID / SETTLED orders
            if ($order->payment_status === PaymentStatus::SETTLED || $order->status === OrderStatus::PAID) {
                return ['status' => 'success', 'message' => 'Order already settled (Idempotent response)'];
            }

            // 2. Idempotency check for CANCELLED / EXPIRED orders (prevent duplicate stock restoration)
            if ($order->status === OrderStatus::CANCELLED) {
                return ['status' => 'success', 'message' => 'Order already cancelled (Idempotent response)'];
            }

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markOrderAsPaid($order);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markOrderAsPaid($order);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $this->markOrderAsCancelled($order, $transactionStatus);
            } elseif ($transactionStatus === 'pending') {
                $order->update([
                    'payment_status' => PaymentStatus::UNPAID,
                    'status' => OrderStatus::PENDING,
                ]);
            }

            return ['status' => 'success', 'message' => 'Notification processed successfully'];
        });
    }

    protected function markOrderAsPaid(Order $order): void
    {
        $order->update([
            'payment_status' => PaymentStatus::SETTLED,
            'status' => OrderStatus::PAID,
        ]);

        try {
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->queue(new PaymentSuccessMail($order));
            }
        } catch (Exception $e) {
            Log::error('Failed to queue PaymentSuccessMail: ' . $e->getMessage());
        }
    }

    protected function markOrderAsCancelled(Order $order, string $reason): void
    {
        $paymentStatus = ($reason === 'expire') ? PaymentStatus::EXPIRED : PaymentStatus::FAILED;

        $order->update([
            'payment_status' => $paymentStatus,
            'status' => OrderStatus::CANCELLED,
        ]);

        // Restore reserved stock back to variants
        $this->inventoryService->restoreStock($order);
    }
}
