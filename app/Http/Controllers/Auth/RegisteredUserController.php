<?php
// Add this to your web.php file 
use Illuminate\Support\Facades\Route;
// Comment out any routes from auth.php that might be loading in the background
// At the bottom of web.php, replace:
// require __DIR__ . '/auth.php';

// With this:
// Custom auth routes are already defined above - don't load default ones
// require __DIR__ . '/auth.php';

// OR create a register routes function at the top that overrides any potential conflicts
// This ensures our routes take precedence
if (!function_exists('registerAuthRoutes')) {
    function registerAuthRoutes() {
        // Registration route - ensure our routes take precedence
        Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
            ->middleware('web')
            ->name('register');
    }
}

registerAuthRoutes();