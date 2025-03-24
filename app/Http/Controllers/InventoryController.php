<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Http\Resources\ProductResource;

class InventoryController extends Controller
{
    /**
     * Get inventory movements with pagination and optional filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMovements(Request $request)
    {
        // Start query builder for inventory movements
        $query = InventoryMovement::with(['product', 'createdBy'])
                                 ->orderBy('created_at', 'desc');
        
        // Apply filters if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Apply date range filters
        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', now()->subDay()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                // 'all' doesn't need a filter (default)
            }
        }
        
        // Paginate the results
        $perPage = $request->input('per_page', 15);
        $movements = $query->paginate($perPage);
        
        return response()->json($movements);
    }

    /**
 * Get products with inventory filtering
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function getInventoryProducts(Request $request)
{
    // Start query builder for products
    $query = Product::with(['category']);
    
    // Apply stock status filter if provided
    if ($request->filled('stock_status')) {
        $stockStatus = $request->stock_status;
        
        if ($stockStatus === 'low') {
            // Low stock: greater than 0 but less than or equal to reorder_level
            $query->whereRaw('quantity <= reorder_level AND quantity > 0 AND track_inventory = 1');
        } else if ($stockStatus === 'out') {
            // Out of stock: exactly 0
            $query->where('quantity', 0)
                  ->where('track_inventory', true);
        }
    }
    
    // Filter by tracking status if specified
    if ($request->filled('track_inventory')) {
        $query->where('track_inventory', (bool)$request->track_inventory);
    }
    
    // Apply category filter if provided
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    
    // Apply search filter if provided
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
    
    // Apply sorting
    $sortField = $request->input('sort_field', 'updated_at');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);
    
    // Paginate the results
    $perPage = $request->input('per_page', 15);
    $products = $query->paginate($perPage);
    
    return response()->json($products);
}

    /**
     * Get product inventory movements.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function getProductMovements(Request $request, $productId)
    {
        // Validate product exists
        $product = Product::findOrFail($productId);
        
        // Get movements with pagination
        $perPage = $request->input('per_page', 10);
        $movements = $product->inventoryMovements()
                            ->with('createdBy')
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
        
        return response()->json($movements);
    }

    /**
     * Adjust inventory for a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adjustInventory(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'type' => 'required|string|in:purchase,sale,adjustment,return,initial',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Get the product
            $product = Product::findOrFail($validated['product_id']);
            
            // Check if inventory tracking is enabled
            if (!$product->track_inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory tracking is not enabled for this product'
                ], 422);
            }
            
            // Check if we're trying to remove more than we have
            if ($validated['quantity'] < 0 && abs($validated['quantity']) > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove more units than available in stock'
                ], 422);
            }
            
            // Adjust inventory
            $product->adjustInventory(
                $validated['quantity'],
                $validated['type'],
                $validated['reference'] ?? null,
                $validated['notes'] ?? null
            );
            
            // Get the latest movement (the one we just created)
            $movement = $product->inventoryMovements()->latest()->first();
            
            // Commit transaction
            DB::commit();
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Inventory adjusted successfully',
                'data' => [
                    'product' => new ProductResource($product),
                    'movement' => $movement,
                    'new_quantity' => $product->quantity
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            Log::error('Error adjusting inventory: ' . $e->getMessage(), [
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'type' => $validated['type'],
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjust inventory for a specific product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adjustProductInventory(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|string|in:purchase,sale,adjustment,return,initial',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Get the product
            $product = Product::findOrFail($id);
            
            // Check if inventory tracking is enabled
            if (!$product->track_inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory tracking is not enabled for this product'
                ], 422);
            }
            
            // Check if we're trying to remove more than we have
            if ($validated['quantity'] < 0 && abs($validated['quantity']) > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove more units than available in stock'
                ], 422);
            }
            
            // Adjust inventory
            $product->adjustInventory(
                $validated['quantity'],
                $validated['type'],
                $validated['reference'] ?? null,
                $validated['notes'] ?? null
            );
            
            // Get the latest movement (the one we just created)
            $movement = $product->inventoryMovements()->latest()->first();
            
            // Commit transaction
            DB::commit();
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Inventory adjusted successfully',
                'data' => [
                    'product' => new ProductResource($product),
                    'movement' => $movement,
                    'new_quantity' => $product->quantity
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            Log::error('Error adjusting product inventory: ' . $e->getMessage(), [
                'product_id' => $id,
                'quantity' => $validated['quantity'],
                'type' => $validated['type'],
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk adjust inventory for multiple products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAdjustInventory(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer',
            'products.*.type' => 'required|string|in:purchase,sale,adjustment,return,initial',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            $results = [];
            $successCount = 0;
            
            foreach ($validated['products'] as $adjustment) {
                $product = Product::findOrFail($adjustment['product_id']);
                
                // Skip products without inventory tracking
                if (!$product->track_inventory) {
                    $results[] = [
                        'product_id' => $adjustment['product_id'],
                        'success' => false,
                        'message' => 'Inventory tracking is not enabled for this product',
                        'skipped' => true
                    ];
                    continue;
                }
                
                // Check if we're trying to remove more than we have
                if ($adjustment['quantity'] < 0 && abs($adjustment['quantity']) > $product->quantity) {
                    $results[] = [
                        'product_id' => $adjustment['product_id'],
                        'success' => false,
                        'message' => 'Cannot remove more units than available in stock',
                        'skipped' => true
                    ];
                    continue;
                }
                
                // Adjust inventory
                $product->adjustInventory(
                    $adjustment['quantity'],
                    $adjustment['type'],
                    $validated['reference'] ?? null,
                    $validated['notes'] ?? null
                );
                
                $results[] = [
                    'product_id' => $adjustment['product_id'],
                    'success' => true,
                    'product' => new ProductResource($product),
                    'new_quantity' => $product->quantity
                ];
                
                $successCount++;
            }
            
            // Commit transaction
            DB::commit();
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => "{$successCount} products adjusted successfully",
                'results' => $results
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            Log::error('Error in bulk inventory adjustment: ' . $e->getMessage(), [
                'products' => $validated['products'],
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStats()
    {
        $stats = [
            'total_products' => Product::where('track_inventory', true)->count(),
            'low_stock_products' => Product::whereRaw('quantity <= reorder_level AND quantity > 0 AND track_inventory = 1')->count(),
            'out_of_stock_products' => Product::where('quantity', 0)->where('track_inventory', true)->count(),
            'total_inventory_value' => Product::where('track_inventory', true)->selectRaw('SUM(quantity * price) as total_value')->first()->total_value ?? 0,
        ];
        
        // Get recent movements
        $recentMovements = InventoryMovement::with(['product', 'createdBy'])
                                          ->orderBy('created_at', 'desc')
                                          ->limit(5)
                                          ->get();
        
        return response()->json([
            'stats' => $stats,
            'recent_movements' => $recentMovements
        ]);
    }
}