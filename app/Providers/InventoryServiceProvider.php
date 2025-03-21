<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class InventoryServiceProvider extends ServiceProvider
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
    public function boot()
    {
        // Register inventory route model binding
        Route::model('inventoryMovement', \App\Models\InventoryMovement::class);
        
        // Load inventory routes
        $this->loadInventoryRoutes();
    }
    
    /**
     * Load inventory routes from a separate file.
     *
     * @return void
     */
    protected function loadInventoryRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/inventory.php'));
    }
}