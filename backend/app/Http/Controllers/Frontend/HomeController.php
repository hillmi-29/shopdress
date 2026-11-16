<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
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
        $categories = Category::withCount('products')->get();

        $featuredProducts = Product::with(['category', 'primaryImage', 'variants'])
            ->where('is_active', true)
            ->where('featured', true)
            ->latest()
            ->take(4)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'primaryImage', 'variants'])
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get();
        }

        $testimonials = Review::with(['user:id,name', 'product:id,name,slug'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(2)
            ->get();

        $wishlistProductIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->all()
            : [];

        return view('frontend.home', compact(
            'categories',
            'featuredProducts',
            'testimonials',
            'wishlistProductIds',
        ));
    }

    /**
     * Display login page.
     */
    public function login(): View
    {
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
                ->with('success', 'Selamat datang kembali, ' . auth()->user()->name . '!');
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

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di ShopDress, ' . $user->name . '!');
    }

    /**
     * Display registration page.
     */
    public function register(): View
    {
        return view('frontend.auth.register');
    }

    /**
     * Display user profile page.
     */
    public function profile(): View
    {
        return view('frontend.profile.edit');
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
}