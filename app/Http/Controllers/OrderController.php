<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
}
