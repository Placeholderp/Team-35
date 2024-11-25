<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $customerId = $request->input('CustomerID');

        $cart = Cart::with('items.product')->where('CustomerID', $customerId)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->Quantity * $item->product->Price;
        });

        $order = Order::create([
            'CustomerID' => $customerId,
            'TotalAmount' => $totalAmount,
            'OrderDate' => now(),
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'OrderID' => $order->OrderID,
                'ProductID' => $item->ProductID,
                'Quantity' => $item->Quantity,
                'Price' => $item->product->Price,
            ]);
        }

        $cart->items()->delete();

        return response()->json(['message' => 'Order placed successfully.', 'OrderID' => $order->OrderID], 201);
    }
}
