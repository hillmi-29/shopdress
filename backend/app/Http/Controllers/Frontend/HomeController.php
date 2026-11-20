<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the customer homepage.
     */
    public function homepage(): View
    {
        $categories = Category::withActiveProductCount()
            ->orderByPopularity()
            ->get();

        $featuredProducts = Product::with(['category', 'primaryImage', 'variants'])
            ->where('is_active', true)
            ->where('featured', true)
            ->orderByPopularity()
            ->take(4)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'primaryImage', 'variants'])
                ->where('is_active', true)
                ->orderByPopularity()
                ->take(4)
                ->get();
        }

        // Produk terbaru — 4 produk aktif terbaru (1 baris 4 kolom), exclude featured
        $featuredIds = $featuredProducts->pluck('id')->all();

        $newArrivals = Product::with(['category', 'primaryImage', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', $featuredIds)
            ->latest()
            ->take(4)
            ->get();

        $newArrivalIds = $newArrivals->pluck('id')->all();

        // Best sellers from settled orders first, then newest products to fill
        // the 4x3 grid. Featured products are excluded so this row stays distinct.
        $customerFavorites = Product::with(['category', 'primaryImage', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', array_merge($featuredIds, $newArrivalIds))
            ->orderByPopularity()
            ->take(12)
            ->get();

        // Testimoni unggulan dipilih admin dari panel. Bila belum ada yang
        // dikurasi, tampilkan ulasan bernilai tinggi terbaru seperti sebelumnya.
        $testimonials = Review::with(['user:id,name', 'product:id,name,slug'])
            ->visible()
            ->featured()
            ->ordered()
            ->take(2)
            ->get();

        if ($testimonials->isEmpty()) {
            $testimonials = Review::with(['user:id,name', 'product:id,name,slug'])
                ->visible()
                ->where('rating', '>=', 4)
                ->latest()
                ->take(2)
                ->get();
        }

        $testimonialStats = [
            'count' => Review::visible()->count(),
            'average' => round((float) (Review::visible()->avg('rating') ?? 0), 1),
        ];

        $wishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        $heroBanners = Banner::active()
            ->position(Banner::POSITION_HERO)
            ->ordered()
            ->get();

        $promoBanner = Banner::active()
            ->position(Banner::POSITION_PROMO)
            ->ordered()
            ->first();

        $homeFaqs = Faq::query()
            ->active()
            ->forPage(Faq::PAGE_HOME)
            ->ordered()
            ->get();

        $siteSettings = SiteSetting::values();

        $featuredCoupon = Coupon::firstActive();

        return view('frontend.home', compact(
            'categories',
            'newArrivals',
            'featuredProducts',
            'customerFavorites',
            'testimonials',
            'testimonialStats',
            'wishlistProductIds',
            'heroBanners',
            'promoBanner',
            'homeFaqs',
            'siteSettings',
            'featuredCoupon',
        ));
    }

    /**
     * Display login page.
     */
    public function login(Request $request): View
    {
        $this->rememberIntendedRedirect($request);

        return view('frontend.auth.login');
    }

    /**
     * Handle login form submission.
     */
    public function loginPost(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang kembali, '.auth()->user()->name.'!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle registration form submission.
     */
    public function registerPost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Akun berhasil dibuat! Selamat datang di '.SiteSetting::storeName().', '.$user->name.'!');
    }

    /**
     * Display registration page.
     */
    public function register(Request $request): View
    {
        $this->rememberIntendedRedirect($request);

        return view('frontend.auth.register');
    }

    /**
     * Display user profile page.
     */
    public function profile(): View
    {
        $addresses = auth()->user()->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('frontend.profile.edit', [
            'addresses' => $addresses,
            'siteSettings' => SiteSetting::values(),
        ]);
    }

    /**
     * Display user orders list.
     */
    public function orders(): View
    {
        $orders = Order::with(['items.variant.product.primaryImage'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Display single order detail.
     */
    public function orderDetail(int $id): View
    {
        $order = Order::with([
            'items.variant.product.primaryImage',
            'items.variant.product.category',
        ])->where('user_id', auth()->id())->findOrFail($id);

        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Logout user.
     */
    public function logout(): RedirectResponse
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari akun.');
    }

    /**
     * Simpan tujuan dari query string agar pengunjung kembali ke halaman
     * asal setelah masuk. Hanya path internal yang diterima supaya tidak
     * menjadi celah open redirect.
     */
    private function rememberIntendedRedirect(Request $request): void
    {
        $redirect = $request->query('redirect');

        if (! is_string($redirect) || $redirect === '') {
            return;
        }

        if (! str_starts_with($redirect, '/') || str_starts_with($redirect, '//') || str_contains($redirect, '\\')) {
            return;
        }

        $request->session()->put('url.intended', url($redirect));
    }
}
