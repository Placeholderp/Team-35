<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use App\Enums\OrderStatus;
use App\Models\OrderDetail;
class OrderController extends Controller
{
    /**
     * Display a paginated list of orders for the authenticated user.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user(); 

        // Query orders that were created by the authenticated user.
        $orders = Order::query()
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    /**
     * Display the details of a specific order.
     */
    public function view(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = request()->user(); 

        // Verify that the order belongs to the authenticated user.
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }

        return view('order.view', compact('order'));
    }
    public function getByUserId($userId)
    {
        try {
            Log::info('Fetching order by user ID', ['user_id' => $userId]);
            
            // Find the order where user_id matches
            $order = Order::where('created_by', $userId)
                ->with(['items.product', 'user'])
                ->firstOrFail();
            
            // Return as OrderResource
            return new OrderResource($order);
        } catch (\Exception $e) {
            Log::error('Error fetching order by user ID', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Order not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not found'
            ], 404);
        }
    }
    public function getOrderDetails(Request $request)
{
    try {
        $orderId = $request->input('order_id');
        Log::info('Fetching order details', ['order_id' => $orderId]);
        
        // First, find the order with proper primary key
        $order = Order::where('user_id', $orderId)->first();
        
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }
        
        // Find matching order detail
        $orderDetail = OrderDetail::where('order_id', $order->user_id)
            ->with(['country'])
            ->first();
        
        if (!$orderDetail) {
            return response()->json([
                'user_id' => $order->user_id,
                'first_name' => '',
                'last_name' => '',
                // Other empty fields...
            ]);
        }
        
        return response()->json($orderDetail);
    } catch (\Exception $e) {
        // Error handling...
    }
}
    /**
     * Get available order statuses
     * This maps to /api/orders/statuses
     */
    public function getOrderStatuses()
    {
        try {
            Log::info('Fetching order statuses');
            
            // Use the OrderStatus enum to get all possible statuses
            $statuses = OrderStatus::getStatuses();
            
            // Format them as expected by the frontend
            $formattedStatuses = array_map(function($status) {
                return $status->value;
            }, $statuses);
            
            return response()->json($formattedStatuses);
        } catch (\Exception $e) {
            Log::error('Error fetching order statuses', [
                'error' => $e->getMessage()
            ]);
            
            // Default fallback statuses
            return response()->json([
                'pending', 'processing', 'paid', 'shipped', 
                'delivered', 'completed', 'cancelled', 'refunded'
            ]);
        }
    }
    

    /**
     * Get the requested includes from the request
     * 
     * @return array
     */
    private function getRequestedIncludes()
    {
        $includes = request('include', '');
        return $includes ? explode(',', $includes) : [];
    }

}
