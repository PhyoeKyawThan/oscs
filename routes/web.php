<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthCustomer;
use App\Http\Middleware\GuestCustomer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::middleware(GuestCustomer::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name("customer.home.index");
    // products
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/product/{id}/view', [ProductsController::class, 'viewProduct'])->name('customer.product.view')->where('id', '[0-9]+');
    Route::get('/products/{id}/related', [ProductsController::class, 'getRelatedProducts']);
    Route::get('/products/filter', [ProductsController::class, 'index']); // For AJAX filtering
    Route::get('/products/search', [ProductsController::class, 'search'])->name('products.search');
    // categories
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    // auth
    Route::get('/login', [UserController::class, 'login'])->name("customer.login.index");
    Route::get('/signup', [UserController::class, 'signup'])->name("customer.signup.index");
    Route::post('/login', [UserController::class, 'login'])->name('doLogin');
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
        Route::put('/update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::delete('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
        Route::get('/data', [CartController::class, 'getCartData'])->name('cart.data');
    });
});

Route::middleware(AuthCustomer::class)->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile'])->name('customer.profile');
});