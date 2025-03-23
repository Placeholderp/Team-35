<?php
// File: app/Http/Controllers/Api/OrderController.php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Resources\OrderListResource;
class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        try {
            // Log request parameters for debugging
            Log::info('Orders request received', ['params' => $request->all()]);
            
            // Get parameters with defaults
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', '');
            $sortField = $request->input('sort_field', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $status = $request->input('status', '');
            
            // Validate sort direction
            $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
            
            // Initialize query
            $query = Order::query();
            
            // Apply filters
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('user_id', 'like', "%{$search}%") // Note: Using user_id as primary key
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where(function($innerQ) use ($search) {
                              $innerQ->where('name', 'like', "%{$search}%")
                                     ->orWhere('email', 'like', "%{$search}%");
                          });
                      });
                });
            }
            
            // Only apply status filter if not empty
            if (!empty($status)) {
                $query->where('status', $status);
            }
            
            // Apply sorting with validation and error handling
            $allowedSortFields = ['user_id', 'status', 'total_price', 'created_at'];
            
            // Default sort that is guaranteed to work
            $fallbackSort = false;
            
            if (in_array($sortField, $allowedSortFields)) {
                try {
                    // Try with the requested sort
                    $query->orderBy($sortField, $sortDirection);
                } catch (Exception $e) {
                    // If that fails, log it and use fallback
                    Log::warning("Sort failed for field {$sortField}: " . $e->getMessage());
                    $fallbackSort = true;
                }
            } else {
                $fallbackSort = true;
            }
            
            // If we need to use fallback sorting
            if ($fallbackSort) {
                $query->orderBy('created_at', 'desc');
            }
            
            // Eager load both user and user.customer relationships
            $query->with(['user' => function($query) {
                $query->select('id', 'name', 'email'); // Include name for fallback display
            }, 'user.customer']);
            
            // Get paginated results with try/catch for safety
            try {
                $orders = $query->paginate($perPage);
            } catch (Exception $e) {
                Log::error('Error paginating orders: ' . $e->getMessage());
                // Fallback to simple query without relations
                $orders = DB::table('orders')->paginate($perPage);
                
                // Return simplified data for the fallback case
                return response()->json([
                    'data' => $orders->items(),
                    'meta' => [
                        'current_page' => $orders->currentPage(),
                        'from' => $orders->firstItem(),
                        'last_page' => $orders->lastPage(),
                        'per_page' => $orders->perPage(),
                        'to' => $orders->lastItem(),
                        'total' => $orders->total(),
                    ],
                    'links' => [
                        'first' => $orders->url(1),
                        'last' => $orders->url($orders->lastPage()),
                        'next' => $orders->nextPageUrl(),
                        'prev' => $orders->previousPageUrl(),
                    ],
                ]);
            }
            
            // Use OrderListResource to properly format the data
            return response()->json([
                'data' => OrderListResource::collection($orders)->collection,
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'from' => $orders->firstItem(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'to' => $orders->lastItem(),
                    'total' => $orders->total(),
                ],
                'links' => [
                    'first' => $orders->url(1),
                    'last' => $orders->url($orders->lastPage()),
                    'next' => $orders->nextPageUrl(),
                    'prev' => $orders->previousPageUrl(),
                ],
            ]);
            
        } catch (Exception $e) {
            Log::error('Order listing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'An error occurred while fetching orders',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
                'data' => [],
                'links' => [],
                'meta' => [
                    'current_page' => 1,
                    'from' => 0,
                    'last_page' => 1,
                    'per_page' => 10,
                    'to' => 0,
                    'total' => 0,
                ],
            ], 500);
        }
    }

    /**
     * View a specific order - fix for /orders/{id} endpoint
     */
    public function view($id)
    {
        try {
            Log::info('Viewing order', ['id' => $id]);
            
            // Look for order with user_id = $id
            $order = Order::find($id);
            
            if (!$order) {
                Log::warning('Order not found', ['id' => $id]);
                return response()->json(['message' => 'Order not found'], 404);
            }
            
            // Eager load relationships to avoid N+1 queries
            $order->load(['items.product', 'user']);
            
            // Convert to resource for consistent output
            return new OrderResource($order);
        } catch (Exception $e) {
            Log::error('Error viewing order', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return basic order structure that frontend can handle
            return response()->json([
                'user_id' => $id,
                'id' => $id, // Frontend expects this
                'status' => 'pending',
                'total_price' => '0.00',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'items' => [],
                'customer' => [
                    'id' => $id,
                    'first_name' => 'Error',
                    'last_name' => 'Loading',
                ]
            ]);
        }
    }
    
    /**
     * Get order by user ID - fix for /orders/by-user/{userId}
     */
    public function getByUserId($userId)
    {
        try {
            Log::info('Fetching order by user ID', ['user_id' => $userId]);
            
            // Look for order with user_id = $userId (this is the primary key)
            // Use direct query for safety
            $order = DB::table('orders')->where('user_id', $userId)->first();
                
            if (!$order) {
                Log::warning('No order found for user', ['user_id' => $userId]);
                
                // Return empty structure instead of 404
                return response()->json([
                    'user_id' => $userId,
                    'id' => $userId, // Frontend expects this
                    'status' => 'pending',
                    'total_price' => '0.00',
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'items' => [],
                    'customer' => [
                        'id' => $userId,
                        'first_name' => 'New',
                        'last_name' => 'Customer',
                    ]
                ]);
            }
            
            // Get model for proper resource conversion
            $orderModel = Order::find($userId);
            
            if (!$orderModel) {
                // This shouldn't happen, but just in case
                return response()->json([
                    'user_id' => $userId,
                    'id' => $userId, // Frontend expects this
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'items' => [],
                    'customer' => [
                        'id' => $userId,
                        'first_name' => 'Data',
                        'last_name' => 'Unavailable',
                    ]
                ]);
            }
            
            // Return as OrderResource
            return new OrderResource($orderModel);
        } catch (Exception $e) {
            Log::error('Error fetching order by user ID', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return empty structure for frontend
            return response()->json([
                'user_id' => $userId,
                'id' => $userId, // Frontend expects this
                'status' => 'pending',
                'total_price' => '0.00',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
                'items' => [],
                'customer' => [
                    'id' => $userId,
                    'first_name' => 'Error',
                    'last_name' => 'Loading',
                ]
            ]);
        }
    }
    
    /**
     * Get order details - fix for /order-details endpoint
     */
    public function getOrderDetails(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            Log::info('Fetching order details', ['order_id' => $orderId]);
            
            // Use direct query for safety
            $orderDetail = DB::table('order_details')
                ->where('order_id', $orderId)
                ->first();
            
            // If no details exist, return basic data
            if (!$orderDetail) {
                Log::info('No order details found, returning basic data', ['order_id' => $orderId]);
                
                // Get user info from the order if available
                $order = DB::table('orders')->where('user_id', $orderId)->first();
                $userId = $order ? $order->created_by : $orderId;
                $user = DB::table('users')->where('id', $userId)->first();
                
                return response()->json([
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'first_name' => $user ? $user->name : '',
                    'last_name' => '',
                    'address1' => '',
                    'address2' => '',
                    'city' => '',
                    'state' => '',
                    'zipcode' => '',
                    'country_code' => ''
                ]);
            }
            
            return response()->json($orderDetail);
        } catch (Exception $e) {
            Log::error('Error fetching order details', [
                'order_id' => $request->input('order_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return empty structure for frontend
            return response()->json([
                'user_id' => $request->input('order_id'),
                'order_id' => $request->input('order_id'),
                'first_name' => 'Error',
                'last_name' => 'Loading',
                'address1' => '',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
                'country_code' => ''
            ]);
        }
    }
    
    /**
     * Get available order statuses - fix for /orders/statuses endpoint
     */
    public function getStatuses()
    {
        try {
            Log::info('Fetching order statuses');
            
            // Use a safer approach that won't throw exceptions
            try {
                // Try using the OrderStatus enum
                $statuses = OrderStatus::getStatuses();
                $formattedStatuses = array_map(function($status) {
                    return $status->value;
                }, $statuses);
            } catch (Exception $enumError) {
                Log::error('Error using OrderStatus enum', [
                    'error' => $enumError->getMessage()
                ]);
                
                // Fallback to hardcoded values
                $formattedStatuses = [
                    'pending', 'processing', 'paid', 'shipped', 
                    'delivered', 'completed', 'cancelled', 'refunded'
                ];
            }
            
            return response()->json($formattedStatuses);
        } catch (Exception $e) {
            Log::error('Error fetching order statuses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return fallback statuses
            return response()->json([
                'pending', 'processing', 'paid', 'shipped', 
                'delivered', 'completed', 'cancelled', 'refunded'
            ]);
        }
    }
    
    /**
     * Update order status
     */
    public function updateStatus(Request $request, $orderId, $status)
    {
        try {
            Log::info('Updating order status', [
                'order_id' => $orderId,
                'status' => $status
            ]);
            
            // Find order by user_id
            $order = Order::find($orderId);
            
            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }
            
            // Update status
            $order->status = $status;
            $order->save();
            
            return response()->json([
                'message' => 'Order status updated successfully',
                'status' => $status
            ]);
        } catch (Exception $e) {
            Log::error('Error updating order status', [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error updating order status',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
}