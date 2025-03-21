<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('web.homepage');
});
Route::get('product', function () {
    return view('web.product');
});
Route::get('product/{slug}', function ($slug) {
    return view('web.singlle_product');
});
Route::get('categoty', function () {
    return view('web.category');
});
Route::get('cart', function () {
    return view('web.cart');
});
Route::get('checkout', function () {
    return view('web.checkout');
});
Route::get('category/{slug}', function ($slug) {
    return view('web.single_category');
});


// require __DIR__.'/auth.php';
