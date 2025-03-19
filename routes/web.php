<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\StockMovementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for our application. 
|
*/

Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{product:slug}', [ProductController::class, 'view'])->name('product.view');
    // For Laravel
    Route::get('/shop', 'App\Http\Controllers\ProductController@shop')->name('shop');
Route::get('/category/{slug}', 'App\Http\Controllers\CategoryController@show')->name('category.show');
Route::post('/api/inventory/adjust', 'InventoryController@adjust');
    // Add missing routes
    Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
    Route::get('/blog', [ProductController::class, 'blog'])->name('blog');
    Route::get('/about', [ProductController::class, 'about'])->name('about');
    Route::get('/calorie-calculator', [ProductController::class, 'calorieCalculator'])->name('calorie.calculator');
    Route::get('/contact', [ProductController::class, 'contact'])->name('contact');
// Policy pages
Route::get('/privacy-policy', function () {
    return view('privacypolicy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('termsofservice');
})->name('terms-of-service');
Route::get('/sanctum/csrf-cookie', [Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);
Route::get('/modern-day-slavery-statement', function () {
    return view('moderndayslaverystatement');
})->name('modern-day-slavery-statement');
    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
    Route::get('/shop/{category?}', [ProductController::class, 'shop'])->name('shop');
});

Route::middleware(['auth', 'verified'])->group(function() {
        // Admin category management pages
        Route::get('/admin/categories', 'App\Http\Controllers\AdminController@categories')->name('app.categories');
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');
});



require __DIR__ . '/auth.php';