<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Mail\OrderUpdateEmail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        try {
            // Retrieve query parameters with default values
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', '');
            $sortField = $request->input('sort_field', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $status = $request->input('status', '');
            
            // Validate sort field
            $allowedSortFields = ['id', 'status', 'total_price', 'created_at', 'updated_at'];
            if (!in_array($sortField, $allowedSortFields)) {
                $sortField = 'created_at';
            }
            
            // Validate sort direction
            if (!in_array($sortDirection, ['asc', 'desc'])) {
                $sortDirection = 'desc';
            }
            
            // Build query
            $query = Order::with(['user', 'items.product']);
            
            // Apply search if provided
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            // Apply status filter if provided
            if (!empty($status)) {
                $query->where('status', $status);
            }
            
            // Apply sorting
            $query->orderBy($sortField, $sortDirection);
            
            // Process pagination
            $orders = $query->paginate($perPage);
            
            // Return a collection of orders using the OrderListResource
            return OrderListResource::collection($orders);
            
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => 'An error occurred while fetching orders',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Display a single order.
     */
    public function view(Order $order)
    {
        try {
            $order->load(['user', 'items.product']);
            return new OrderResource($order);
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Order not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not found'
            ], 404);
        }
    }

    /**
     * Retrieve the available order statuses.
     */
    public function getStatuses()
    {
        return OrderStatus::getStatuses();
    }

    /**
     * Change the status of a specific order.
     */
    public function changeStatus(Order $order, $status)
    {
        try {
            // Update the order status
            $order->status = $status;
            $order->save();

            // Send email notification
            Mail::to($order->user)->send(new OrderUpdateEmail($order));

            return response()->json([
                'message' => 'Order status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to update order status',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
    
    /**
     * Send invoice email
     */
    public function emailInvoice(Order $order)
    {
        try {
            $order->load(['user', 'items.product']);
            
            // Here you would implement your email sending logic
            // For example: Mail::to($order->user->email)->send(new OrderInvoice($order));
            
            return response()->json([
                'message' => 'Invoice email sent successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error sending invoice email: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send invoice email',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
    /**
 * Place a new order.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function placeOrder(Request $request)
{
    /** @var \App\Models\User $user */
    $user = $request->user();
    
    // Validate the request
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        // Add other fields as needed
    ]);
    
    // Start a database transaction to ensure data integrity
    \DB::beginTransaction();
    
    try {
        // Create the order
        $order = new Order();
        $order->status = 'pending';
        $order->created_by = $user->id;
        // Set other order properties from the request as needed
        $order->save();
        
        $totalAmount = 0;
        
        // Process each item
        foreach ($validated['items'] as $itemData) {
            $product = \App\Models\Product::findOrFail($itemData['product_id']);
            
            // Check if there's enough inventory
            if ($product->track_inventory && $product->quantity < $itemData['quantity'] && !$product->allow_backorder) {
                throw new \Exception("Not enough inventory for {$product->title}. Available: {$product->quantity}");
            }
            
            // Calculate item price
            $itemPrice = $product->price;
            $itemTotal = $itemPrice * $itemData['quantity'];
            $totalAmount += $itemTotal;
            
            // Create order item
            $item = $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemPrice,
                'total_price' => $itemTotal,
            ]);
            
            // Adjust inventory if tracking is enabled
            if ($product->track_inventory) {
                $product->adjustInventory(
                    -$itemData['quantity'], // Negative quantity to decrease stock
                    'sale',
                    'Order #' . $order->id,
                    'Order placed by ' . $user->name
                );
            }
        }
        
        // Update order with total amount
        $order->total_amount = $totalAmount;
        $order->save();
        
        // Commit the transaction
        \DB::commit();
        
        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'order' => $order->load('items.product')
        ]);
    } catch (\Exception $e) {
        // Rollback the transaction on failure
        \DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}

}