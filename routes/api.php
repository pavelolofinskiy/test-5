<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', RegisterController::class)->name('api.auth.register');
    Route::post('auth/login', LoginController::class)->name('api.auth.login');

    Route::get('products', [ProductController::class, 'index'])->name('api.products.index');
    Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('api.products.show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('orders', [OrderController::class, 'store'])->name('api.orders.store');
        Route::get('orders', [OrderController::class, 'index'])->name('api.orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');

        Route::post('orders/{order}/checkout', CheckoutController::class)->name('api.orders.checkout');
    });
});
