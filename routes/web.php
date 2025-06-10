<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ThemeController;
use Livewire\Volt\Volt;

// Public Routes
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('products', [HomepageController::class, 'products']);
Route::get('product/{slug}', [HomepageController::class, 'product']);
Route::get('categories', [HomepageController::class, 'categories']);
Route::get('category/{slug}', [HomepageController::class, 'category']);
Route::get('themes', [ThemeController::class, 'themes']);
Route::get('cart', [HomepageController::class, 'cart']);
Route::get('checkout', [HomepageController::class, 'checkout']);
Route::resource('products', ProductController::class);

// Dashboard Routes (Requires Auth & Verification)
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', ProductCategoryController::class);
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::resource('themes', ThemeController::class);
});

// Settings Routes (Requires Auth)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::prefix('customer')->controller(CustomerAuthController::class)->group(function () {
    Route::middleware('check_customer_login')->group(function () {
        Route::get('login', 'login')->name('customer.login');
        Route::post('login', 'store_login')->name('customer.store_login');
        Route::get('register', 'register')->name('customer.register');
        Route::post('register', 'store_register')->name('customer.store_register');
    });
    Route::post('logout', 'logout')->name('customer.logout');
});
   
require __DIR__ . '/auth.php';