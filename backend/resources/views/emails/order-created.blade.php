<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Dibuat - ShopDress</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f7f7f9; color: #222222; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .header { background-color: #FF5D00; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 24px; }
        .order-meta { background: #fafafa; border-radius: 8px; padding: 14px; margin: 20px 0; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .order-table th, .order-table td { padding: 10px; border-bottom: 1px solid #eeeeee; text-align: left; }
        .order-table th { background: #fdfdfd; font-size: 13px; color: #666; }
        .btn { display: inline-block; background-color: #FF5D00; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #888; background: #fbfbfb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ ShopDress</h1>
            <p style="margin: 4px 0 0 0; opacity: 0.9;">Pesanan Anda Berhasil Dibuat</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->user->name ?? $order->recipient_name }}</strong>,</p>
            <p>Terima kasih atas pesanan Anda di <strong>ShopDress</strong>. Pesanan Anda saat ini telah tercatat dan menunggu proses pembayaran.</p>

            <div class="order-meta">
                <div><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</div>
                <div><strong>Status Pesanan:</strong> {{ $order->status->label() }}</div>
                <div><strong>Status Pembayaran:</strong> {{ $order->payment_status->label() }}</div>
                <div><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address }}</div>
            </div>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>
                                @if(is_array($item->variant_details))
                                    {{ $item->variant_details['size'] ?? '' }} / {{ $item->variant_details['color'] ?? '' }}
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Ongkos Kirim:</td>
                        <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; color: #28a745;">Diskon:</td>
                        <td style="color: #28a745;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; font-size: 15px;">Total Tagihan:</td>
                        <td style="font-weight: bold; font-size: 15px; color: #FF5D00;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}" class="btn">Lihat Detail Pesanan</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ShopDress E-Commerce. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
