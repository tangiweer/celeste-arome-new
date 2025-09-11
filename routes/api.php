<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WishlistApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Public vs Sanctum-protected routes
|--------------------------------------------------------------------------
*/

// -----------------------------
// 🔓 Public routes (no auth)
// -----------------------------
Route::get('/products',        [ProductApiController::class, 'index']);
Route::get('/products/{id}',   [ProductApiController::class, 'show']);

Route::post('/register', [AuthController::class, 'customerRegister']);
Route::post('/login',    [AuthController::class, 'customerLogin']);

// -----------------------------
// 🔒 Authenticated routes
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Profile & logout
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'customerLogout']);

    // Cart
    Route::get('/cart',                  [CartApiController::class, 'index']);
    Route::post('/cart/items',           [CartApiController::class, 'store']);
    Route::patch('/cart/items/{item}',   [CartApiController::class, 'update']);
    Route::delete('/cart/items/{item}',  [CartApiController::class, 'destroy']);
    Route::delete('/cart/clear',         [CartApiController::class, 'clear']);

    // Wishlist
    Route::get('/wishlist',                  [WishlistApiController::class, 'index']);
    Route::post('/wishlist/{productId}',     [WishlistApiController::class, 'add']);
    Route::delete('/wishlist/{productId}',   [WishlistApiController::class, 'remove']);

    // Orders
    Route::get('/orders',         [OrderApiController::class, 'index']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::post('/orders',        [OrderApiController::class, 'store']);

    // Payments
    Route::post('/payment', [PaymentController::class, 'pay'])->name('api.payment.pay');
});

// -----------------------------
// 🔒 Admin-only routes
// -----------------------------
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Product management
    Route::post('/products',        [ProductApiController::class, 'store']);
    Route::put('/products/{id}',    [ProductApiController::class, 'update']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);

    // Admin order management
    Route::prefix('admin')->group(function () {
        Route::get('/orders',            [OrderApiController::class, 'apiIndex']);
        Route::get('/orders/{order}',    [OrderApiController::class, 'apiShow']);
        Route::delete('/orders/{order}', [OrderApiController::class, 'apiDestroy']);
    });
});
