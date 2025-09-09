<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WishlistApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentApiController;

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);

Route::post('/register', [AuthController::class, 'customerRegister']);
Route::post('/login',    [AuthController::class, 'customerLogin']);
Route::post('/logout',   [AuthController::class, 'customerLogout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/products',        [ProductApiController::class, 'store']);
    Route::put('/products/{id}',    [ProductApiController::class, 'update']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());

    Route::get('/cart',                [CartApiController::class, 'index']);
    Route::post('/cart/items',         [CartApiController::class, 'store']);
    Route::patch('/cart/items/{item}', [CartApiController::class, 'update']);
    Route::delete('/cart/items/{item}',[CartApiController::class, 'destroy']);
    Route::delete('/cart/clear',       [CartApiController::class, 'clear']);

    Route::get('/wishlist',                [WishlistApiController::class, 'index']);
    Route::post('/wishlist/{productId}',   [WishlistApiController::class, 'add']);
    Route::delete('/wishlist/{productId}', [WishlistApiController::class, 'remove']);

    Route::get('/orders',         [OrderApiController::class, 'index']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::post('/orders',        [OrderApiController::class, 'store']);

    Route::post('/payment', [PaymentApiController   ::class, 'pay'])->name('api.payment.pay');
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/orders',           [OrderApiController::class, 'apiIndex']);
    Route::get('/orders/{order}',   [OrderApiController::class, 'apiShow']);
    Route::delete('/orders/{order}',[OrderApiController::class, 'apiDestroy']);
});
