<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('web.homepage');
});

Route::get('products', function () {
    return view('web.products');
});

Route::get('product/{slug}', function ($slug) {
    return view('web.product_by_slug', ['slug' => $slug]);
});

Route::get('categories', function () {
    return view('web.categories');
});

Route::get('category/{slug}', function ($slug) {
    return view('web.category_by_slug', ['slug' => $slug]);
});
Route::get('cart', function () {
    return view('web.cart');
});
Route::get('checkout', function () {
    return view('web.checkout');
});


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
