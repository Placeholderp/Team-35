<!DOCTYPE html>
<html lang="en">
<head>
<!--
Developer: Oliver Blatchford
University ID: 230163795
Function: This constructs a page to show the orders
-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
<body>
    <div class="container mt-5">
        <h1>Your Orders</h1>
        @if($orders->isEmpty())
            <p>You have no orders yet.</p>
            {{ csrf_token() }}
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->OrderID }}</td>
                            <td>${{ number_format($order->totalAmount, 2) }}</td>
                            <td>{{ $order->orderDate->format('Y-m-d') }}</td>
        
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>