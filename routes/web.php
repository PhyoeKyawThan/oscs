<?php

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthCustomer;
use App\Http\Middleware\GuestCustomer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin;


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
    Route::post('/signup', [UserController::class, 'signup'])->name('signup.store');
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
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'profile'])->name('profile.update');
    Route::get('/profile/data', [ProfileController::class, 'getProfileData'])->name('profile.data');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Order listing and management
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'getOrderHistory'])->name('customer.orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('customer.orders.track');
    Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('customer.orders.reorder');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('customer.orders.invoice');
    Route::get('/orders/stats', [OrderController::class, 'getOrderStats'])->name('customer.orders.stats');
    Route::get('/orders/{orderNumber}/invoice/download', [OrderController::class, 'invoice'])->name('customer.orders.invoice.download');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication Routes
    Route::get('login', [Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login']);
    Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth:admin'])->group(function () {
        // Dashboard
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('profile', [Admin\AuthController::class, 'profile'])->name('profile');
        Route::put('profile', [Admin\AuthController::class, 'updateProfile']);
        
        // Orders
        Route::resource('orders', Admin\OrderController::class)->except(['create', 'store']);
        Route::post('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('orders/{order}/invoice', [Admin\OrderController::class, 'invoice'])->name('orders.invoice');
        
        // Products
        Route::resource('products', Admin\ProductController::class);
        Route::post('products/bulk', [Admin\ProductController::class, 'bulkUpdate'])->name('products.bulk');
        
        // Categories
        Route::resource('categories', Admin\CategoryController::class);
        
        // Customers
        Route::resource('customers', Admin\CustomerController::class);
        Route::get('customers/{customer}/orders', [Admin\CustomerController::class, 'getCustomerOrders'])->name('customers.orders');
        
        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('general', [Admin\SettingsController::class, 'general'])->name('general');
            Route::put('general', [Admin\SettingsController::class, 'updateGeneral']);
            
            Route::get('payment', [Admin\SettingsController::class, 'payment'])->name('payment');
            Route::put('payment', [Admin\SettingsController::class, 'updatePayment']);
            
            Route::get('shipping', [Admin\SettingsController::class, 'shipping'])->name('shipping');
            Route::put('shipping', [Admin\SettingsController::class, 'updateShipping']);
            
            Route::get('notifications', [Admin\SettingsController::class, 'notifications'])->name('notifications');
            Route::put('notifications', [Admin\SettingsController::class, 'updateNotifications']);
        });
    });
});
Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'general'])->name('index');
    Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
    Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
    Route::put('/payment', [SettingsController::class, 'updatePayment'])->name('payment.update');
    Route::get('/shipping', [SettingsController::class, 'shipping'])->name('shipping');
    Route::put('/shipping', [SettingsController::class, 'updateShipping'])->name('shipping.update');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
    Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::get('/social', [SettingsController::class, 'social'])->name('social');
    Route::put('/social', [SettingsController::class, 'updateSocial'])->name('social.update');
    Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
    Route::post('/reset', [SettingsController::class, 'reset'])->name('reset');
});