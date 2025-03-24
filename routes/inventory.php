<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Get inventory movements with pagination
    Route::get('/inventory/movements', [InventoryController::class, 'getMovements']);
    Route::get('/inventory/products', [InventoryController::class, 'getInventoryProducts']);
    // Get inventory movements for a specific product
    Route::get('/products/{productId}/inventory-movements', [InventoryController::class, 'getProductMovements']);
    
    // Adjust inventory for a single product
    Route::post('/inventory/adjust', [InventoryController::class, 'adjustInventory']);
    
    // Bulk adjust inventory for multiple products
    Route::post('/inventory/bulk-adjust', [InventoryController::class, 'bulkAdjustInventory']);
    
    // Get inventory statistics
    Route::get('/inventory/stats', [InventoryController::class, 'getStats']);
    
    // Add endpoint for individual product inventory adjustment
    Route::post('/products/{id}/adjust-inventory', [InventoryController::class, 'adjustProductInventory']);
});