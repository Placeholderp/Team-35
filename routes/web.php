<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for our application. 
|
*/

// Redirect root to login if not authenticated
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Logout routes - using __invoke method so no need to specify a method
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout']);

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
// Add these if you need password reset functionality
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Routes accessible to guests or verified users
Route::middleware(['guestOrVerified'])->group(function () {
    // Keeping the 'home' route name for compatibility with existing views
    Route::get('/dashboard', [ProductController::class, 'WebIndex'])->name('home');
    
    Route::get('/product/{product:slug}', [ProductController::class, 'view'])->name('product.view');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
    Route::get('/blog', [ProductController::class, 'blog'])->name('blog');
    Route::get('/about', [ProductController::class, 'about'])->name('about');
    Route::get('/calorie-calculator', [ProductController::class, 'calorieCalculator'])->name('calorie.calculator');
    Route::get('/contact', [ProductController::class, 'contact'])->name('contact');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/shop/{category?}', [ProductController::class, 'shop'])->name('shop');
    
    // Policy pages
    Route::get('/privacy-policy', function () {
        return view('privacypolicy');
    })->name('privacy-policy');
    
    Route::get('/terms-of-service', function () {
        return view('termsofservice');
    })->name('terms-of-service');
    
    Route::get('/modern-day-slavery-statement', function () {
        return view('moderndayslaverystatement');
    })->name('modern-day-slavery-statement');
});

// Routes only for authenticated users
Route::middleware(['auth', 'verified'])->group(function() {
    // Cart functionality
    Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
    Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    
    // Admin category management pages

    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    
    // Checkout and orders
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');
});

// Sanctum CSRF cookie route
Route::get('/sanctum/csrf-cookie', [Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);