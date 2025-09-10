<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware('auth')->group(function () {

    // Profile & Account
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/account', [ProfileController::class, 'account'])->name('account.index');
    Route::get('/account/orders', [ProfileController::class, 'orders'])->name('account.orders');
    Route::get('/account/favorites', [ProfileController::class, 'favorites'])->name('account.favorites');
    Route::get('/account/profile', [ProfileController::class, 'accountProfile'])->name('account.profile');
    Route::get('/account/password', [ProfileController::class, 'password'])->name('account.password');
    Route::get('/account/addresses', [ProfileController::class, 'addresses'])->name('account.addresses');

    Route::post('/account/addresses', [ProfileController::class, 'addressesStore'])->name('account.addresses.store');
    Route::patch('/account/addresses/{address}', [ProfileController::class, 'addressesUpdate'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [ProfileController::class, 'addressesDestroy'])->name('account.addresses.destroy');

    Route::get('/orders', fn() => redirect()->route('account.orders'))->name('orders.index');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart/{product}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::post('/wishlist/move-all-to-cart', [WishlistController::class, 'moveAllToCart'])->name('wishlist.moveAllToCart');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/item/{item}/qty', [CartController::class, 'updateQty'])->name('cart.updateQty');
    Route::delete('/cart/item/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Payment
    Route::get('/payment', [PaymentController::class, 'show'])->name('payment.index');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

    // Guard accidental GETs to /payment/process (prevents 405)
    Route::get('/payment/process', function () {
        return redirect()->route('payment.index')
            ->with('error', 'Please submit the payment form to continue.');
    });

    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed',  [PaymentController::class, 'failed'])->name('payment.failed');
});

// Admin routes (SINGLE group — no nesting)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $since = now()->subDays(30);

            $stats = [
                'revenue'            => Order::whereDate('created_at', '>=', $since)->sum('total'),
                'orders'             => Order::whereDate('created_at', '>=', $since)->count(),
                'customers'          => User::whereDate('created_at', '>=', $since)->count(),
                'pending_deliveries' => Order::where('status', 'pending')->count(),
                'income'             => Order::whereDate('created_at', '>=', $since)->sum('total'),
                'expenses'           => 0,
                'balance'            => Order::whereDate('created_at', '>=', $since)->sum('total'),
            ];

            $topProducts = Product::whereHas('orderItems')
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->limit(4)
                ->get();

            $offers = [
                ['title' => 'Summer Sale 20% Off', 'date' => 'Aug 2025'],
                ['title' => 'Buy 2 Get 1 Free', 'date' => 'Sep 2025'],
                ['title' => 'Free Shipping Over $50', 'date' => 'Ongoing'],
            ];

            return view('admin.dashboard', compact('stats', 'topProducts', 'offers'));
        })->name('dashboard');

        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products',   AdminProductController::class);
        Route::resource('customers',  CustomerController::class);

      Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);


        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });

require __DIR__ . '/auth.php';