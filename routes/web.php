<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::group(["middleware" => "guest"], function () {
    Route::get('/', [HomeController::class, 'index'])->name("customer.home.index");
    Route::get('/products', [ProductsController::class, 'index'])->name("customer.products.index");
    Route::get('/login', [UserController::class, 'login'])->name("customer.login.index");
    Route::get('/signup', [UserController::class, 'signup'])->name("customer.signup.index");
});