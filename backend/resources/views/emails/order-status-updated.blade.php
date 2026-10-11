<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembaruan Status Pesanan - ShopDress</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f7f7f9; color: #222222; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .header { background-color: #0284c7; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 24px; }
        .status-badge { display: inline-block; background-color: #e0f2fe; color: #0369a1; padding: 8px 16px; border-radius: 20px; font-weight: bold; margin: 15px 0; }
        .btn { display: inline-block; background-color: #FF5D00; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #888; background: #fbfbfb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚚 Update Pesanan</h1>
            <p style="margin: 4px 0 0 0; opacity: 0.9;">ShopDress Order Notification</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->user->name ?? $order->recipient_name }}</strong>,</p>
            <p>Ada pembaruan status terkini untuk pesanan Anda <strong>#{{ $order->order_number }}</strong>:</p>

            <div style="text-align: center;">
                <span class="status-badge">{{ $order->status->label() }}</span>
            </div>

            @if($order->tracking_number)
            <div style="background: #f8fafc; border-left: 4px solid #0284c7; padding: 12px; margin: 15px 0;">
                <strong>Nomor Resi Pengiriman:</strong>
                <p style="font-size: 18px; font-family: monospace; margin: 5px 0 0 0; color: #0284c7;">{{ $order->tracking_number }}</p>
            </div>
            @endif

            <p>Kami terus memastikan barang Anda sampai ke tujuan dengan aman dan cepat.</p>

            <div style="text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}" class="btn">Periksa Status Pesanan</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ShopDress E-Commerce. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
