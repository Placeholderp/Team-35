<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Places order
Route::post(
    '/place-order', 
    [OrderController::class, 'placeOrder']
)->name('placeOrder');