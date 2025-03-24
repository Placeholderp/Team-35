<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Routes that require authentication but not admin privileges
Route::middleware(['auth:sanctum'])->group(function () {
    // Order routes available to all authenticated users
    Route::get('/orders/{order}/email-invoice', [OrderController::class, 'emailInvoice']);
    
    // Add any other non-admin authenticated routes here
});

// Admin-only routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Get inventory movements with pagination
    Route::get('/inventory/movements', 'App\Http\Controllers\Api\InventoryController@getMovements');

    // Get inventory movements for a specific product
    Route::get('/products/{productId}/inventory-movements', 'App\Http\Controllers\Api\InventoryController@getProductMovements');
    
    // Adjust inventory for a single product
    Route::post('/inventory/adjust', 'App\Http\Controllers\Api\InventoryController@adjustInventory');
    
    // Bulk adjust inventory for multiple products
    Route::post('/inventory/bulk-adjust', 'App\Http\Controllers\Api\InventoryController@bulkAdjustInventory');
    
    // Get inventory statistics
    Route::get('/inventory/stats', 'App\Http\Controllers\Api\InventoryController@getStats');
    
    // Add endpoint for individual product inventory adjustment
    Route::post('/products/{id}/adjust-inventory', 'App\Http\Controllers\Api\InventoryController@adjustProductInventory');
    
    // Category routes
    Route::get('/categories/list', 'App\Http\Controllers\CategoryController@list');
    Route::resource('/categories', 'App\Http\Controllers\CategoryController');
    Route::get('/categories/{category}/can-delete', [CategoryController::class, 'canDelete']);
    
    // Auth and user routes
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Product routes
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::patch('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
    
    // User and customer routes
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::get('/countries', [CustomerController::class, 'countries']);

    // Order Routes
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/statuses', [OrderController::class, 'getStatuses']);
    Route::post('orders', [OrderController::class, 'placeOrder']);

    Route::get('orders/{order}', [OrderController::class, 'view']);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('orders/{order}/return', [OrderController::class, 'processReturn']);
    Route::post('orders/{order}/email-invoice', [OrderController::class, 'emailInvoice']);
    
    // Customer analytics endpoints
    Route::get('/orders/by-user/{userId}', [OrderController::class, 'getByUserId']);
    
    // New endpoint for order details
    Route::get('/order-details', [OrderController::class, 'getOrderDetails']);
    
    // Order status endpoint - you have a similar one as 'getStatuses'
    Route::post('orders/change-status/{orderId}/{status}', [OrderController::class, 'updateStatus']);
    Route::post('orders/{orderId}/status/{status}', [OrderController::class, 'updateStatus']);
    Route::put('orders/{orderId}/status/{status}', [OrderController::class, 'updateStatus']);
    // Dashboard Routes
    Route::get('/dashboard/customers-count', [DashboardController::class, 'activeCustomers']);
    Route::get('/dashboard/products-count', [DashboardController::class, 'activeProducts']);
    Route::get('/dashboard/orders-count', [DashboardController::class, 'paidOrders']);
    Route::get('/dashboard/income-amount', [DashboardController::class, 'totalIncome']);
    Route::get('/dashboard/orders-by-country', [DashboardController::class, 'ordersByCountry']);
    Route::get('/dashboard/latest-customers', [DashboardController::class, 'latestCustomers']);
    Route::get('/dashboard/latest-orders', [DashboardController::class, 'latestOrders']);
    Route::get('/orders/user/{userId}', [OrderController::class, 'getByUserId']);
    // Report routes
    Route::prefix('reports')->group(function () {
        Route::get('/summary', [ReportController::class, 'summary']);
        Route::get('/orders', [ReportController::class, 'ordersReport']);
        Route::get('/customers', [ReportController::class, 'customersReport']);
        Route::get('/orders/export', [ReportController::class, 'exportOrders']);
        Route::get('/customers/export', [ReportController::class, 'exportCustomers']);
        Route::get('/customers/demographics/export', [ReportController::class, 'exportCustomerDemographics']);
        
        // New order report routes
        Route::get('/orders', [OrderController::class, 'getReportData']); // Note: This conflicts with above route
        Route::get('/products/top', [OrderController::class, 'getTopProducts']);
    });
});