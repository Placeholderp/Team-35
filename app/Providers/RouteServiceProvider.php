<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     */
    public const HOME = '/dashboard';  // This is the path, but the route name is still 'home'

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+(?::[0-9]+)?');
        // Add custom route model binding for products to handle IDs with colons
        Route::bind('product', function ($value) {
            // For API routes, handle IDs with colons
            if (request()->is('api/*')) {
                // Clean the ID by removing anything after a colon
                if (is_string($value) && strpos($value, ':') !== false) {
                    $value = explode(':', $value)[0];
                }
                
                // Find by ID for API routes
                if (is_numeric($value)) {
                    return Product::findOrFail($value);
                }
            }
            
            
            // For web routes, find by slug
            if (!is_numeric($value)) {
                return Product::where('slug', $value)->firstOrFail();
            }
            
            // Default case
            return Product::findOrFail($value);
        });

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}