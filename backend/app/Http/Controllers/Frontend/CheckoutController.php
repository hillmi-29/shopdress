<?php

namespace App\Http\Controllers\Frontend;

use App\Exceptions\CheckoutException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\User;
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
    ) {}

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

        $savedAddresses = auth()->user()->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('frontend.checkout.index', compact(
            'cart',
            'totalAmount',
            'shippingCost',
            'grandTotal',
            'savedAddresses',
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

            if ($request->boolean('save_address')) {
                $this->saveCheckoutAddress($user, $data);
            }

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

    /**
     * Optionally keep the address entered at checkout in the customer's address book.
     */
    private function saveCheckoutAddress(User $user, array $data): void
    {
        try {
            $street = trim((string) ($data['street'] ?? ''));

            if ($street === '') {
                return;
            }

            $exists = $user->addresses()
                ->where('recipient_name', $data['recipient_name'])
                ->where('recipient_phone', $data['recipient_phone'])
                ->where('street', $street)
                ->exists();

            if ($exists) {
                return;
            }

            $user->addresses()->create([
                'label' => 'Alamat pengiriman',
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'province_code' => $data['province_code'] ?? null,
                'province_name' => $data['province_name'] ?? null,
                'regency_code' => $data['regency_code'] ?? null,
                'regency_name' => $data['regency_name'] ?? null,
                'district_code' => $data['district_code'] ?? null,
                'district_name' => $data['district_name'] ?? null,
                'village_code' => $data['village_code'] ?? null,
                'village_name' => $data['village_name'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'rt' => $data['rt'] ?? null,
                'rw' => $data['rw'] ?? null,
                'street' => $street,
                'is_default' => ! $user->addresses()->exists(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan alamat dari checkout.', [
                'user_id' => $user->id,
                'exception' => $e,
            ]);
        }
    }
}
