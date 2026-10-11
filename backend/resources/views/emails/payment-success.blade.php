<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil - ShopDress</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f7f7f9; color: #222222; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .header { background-color: #28a745; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 24px; }
        .success-box { background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; padding: 16px; margin: 15px 0; text-align: center; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .order-table th, .order-table td { padding: 10px; border-bottom: 1px solid #eeeeee; text-align: left; }
        .btn { display: inline-block; background-color: #FF5D00; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #888; background: #fbfbfb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Pembayaran Berhasil!</h1>
            <p style="margin: 4px 0 0 0; opacity: 0.9;">Pesanan Anda Sedang Kami Siapkan</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->user->name ?? $order->recipient_name }}</strong>,</p>
            
            <div class="success-box">
                <h3 style="margin: 0; color: #2e7d32;">Pembayaran Terkonfirmasi</h3>
                <p style="margin: 6px 0 0 0; font-size: 14px;">Nomor Pesanan: <strong>{{ $order->order_number }}</strong></p>
                <p style="margin: 2px 0 0 0; font-size: 14px;">Total Dibayar: <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></p>
            </div>

            <p>Kabar gembira! Pembayaran untuk pesanan Anda telah kami terima dan diverifikasi. Tim ShopDress akan segera mengemas dan memproses pengiriman produk Anda.</p>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}" class="btn">Lacak Pesanan Anda</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ShopDress E-Commerce. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
