<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'unit_price' => $product->price,
            ];
        }

        // Create Order
        $orderData = [
            'total_price' => $totalPrice,
            'status'      => OrderStatus::Unpaid,
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
        ];
        $order = Order::create($orderData);

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Create Payment record (without any external session ID)
        $paymentData = [
            'order_id'   => $order->id,
            'amount'     => $totalPrice,
            'status'     => PaymentStatus::Pending,
            'type'       => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => null,
        ];
        Payment::create($paymentData);

        // Clear the user's cart items.
        CartItem::where(['user_id' => $user->id])->delete();

        // Redirect to a success page (adjust as necessary for your flow)
        return redirect()->route('checkout.success');
    }

    public function success(Request $request)
    {
        // Optionally, update payment/order statuses if needed.
        return view('checkout.success');
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => "Payment failed."]);
    }
}
