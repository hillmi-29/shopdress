<?php

namespace App\Http\Controllers\Frontend;

use App\Exceptions\CheckoutException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected MidtransService $midtransService
    ) {
    }

    /**
     * Display checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart(auth()->user());

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        $totalAmount = $cart->total_amount;
        $shippingCost = 20000;
        $grandTotal = $totalAmount + $shippingCost;

        return view('frontend.checkout.index', compact(
            'cart',
            'totalAmount',
            'shippingCost',
            'grandTotal',
        ));
    }

    /**
     * Process checkout - create order and redirect to Midtrans Snap.
     */
    public function process(CheckoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $cart = $this->cartService->getCart($user);

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        try {
            $data = $request->validated();
            if ($request->hasFile('landmark_photo')) {
                $data['landmark_photo'] = $request->file('landmark_photo')->store('landmarks', 'public');
            }

            $order = $this->checkoutService->checkout($user, $data);

            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('snap_token', $order->snap_token);
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Checkout web gagal diproses.', [
                'user_id' => $user?->id,
                'exception' => $e,
            ]);

            return back()->with('error', 'Checkout gagal diproses. Silakan coba lagi atau hubungi CS.');
        }
    }

    /**
     * Display order payment page with Midtrans Snap.
     */
    public function payment(int|string $orderId): View|RedirectResponse
    {
        $order = Order::with(['items.variant.product.primaryImage'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        $snapToken = session('snap_token') ?? $order->snap_token;
        if (! $snapToken) {
            try {
                $snapToken = $this->midtransService->createSnapToken($order);
            } catch (\Throwable $e) {
                Log::error('Gagal membuat Snap token untuk halaman pembayaran.', [
                    'order_id' => $order->id,
                    'exception' => $e,
                ]);
                $snapToken = null;
            }
        }

        return view('frontend.checkout.payment', compact('order', 'snapToken'));
    }

    /**
     * Display order success page.
     */
    public function success(int|string $orderId): View
    {
        $order = Order::with(['items.variant.product.primaryImage'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        return view('frontend.checkout.success', compact('order'));
    }
}
