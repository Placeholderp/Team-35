<?php

use App\Models\Order;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: The routing for the pages and also allowing the use of some functions from controllers
********************************/

//Places order
Route::post(
    '/place-order',
    [OrderController::class, 'placeOrder']
)->name('placeOrder');


//Shows all orders
Route::get('/orders', function () {$orders = Order::all(); return view('orders', compact('orders'));})->name('orders');


//Showing all the pages



//Shows the about us page
Route::view('/about', 'about')->name('page.about');

//Shows the cart page
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
//Updates the quantity of items in the cart
Route::post('/cart/update-stock/{product}', [CartController::class, 'update_stock'])->name('cart.updateStock');
//Adds a product to cart
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
//Removes a product from the cart
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');


//Shows the payment page
Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
//Checks if payment has been processesed
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');


//Shows the product page
//Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//When you click on a product it shows you a page with its specific details
//Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

//temporary testing delete later
Route::get('/products', function () {
    return view('products');
});


//Shows register form
Route::get('/register', [UsersController::class, 'showRegisterForm'])->name('register');
//Registers the user account in database
Route::post('/register', [UsersController::class, 'register'])->name('register.store');
//Shows login form
Route::get('/login', [UsersController::class, 'showLoginForm'])->name('login');
//Logs the user in if the details match the database
Route::post('/login', [UsersController::class, 'login'])->name('login.process');

//Shows the about us page
Route::view('/about', 'about')->name('about');
//Shows the contact us page
Route::view('/contact', 'contact')->name('contact');
//Shows the admin page
Route::view('/admin', 'admin.index')->name('admin');
//Shows the calorie counter page
Route::view('caloriecounter', 'caloriecounter.index')->name('caloriecounter');











