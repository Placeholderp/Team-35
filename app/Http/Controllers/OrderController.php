<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


class OrderController extends Controller
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This function shows the orders on a page and places an order on the database
********************************/
    public function showOrders()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function placeOrder(Request $request)
    {
        //Creates new entry in order table
        $order = new Order();
        //Requests userID and total amount of purchase
        $order->userID = $request->input('userID');
        $order->totalAmount = $request->input('totalAmount');
        //Saves the order date as current time
        $order->orderDate = now();
        $order->save();
        //Shows orderPlaced page with the order
        return view('ordersPlaced', ['ordersPlaced' => $order]);
    }
}
