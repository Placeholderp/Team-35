<?php

namespace App\Providers;

use App\Http\Middleware\ProductIdCleanerMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->pushMiddlewareToGroup('api', ProductIdCleanerMiddleware::class);
    }
}