<!-- Main heading for the order confirmation -->
<h1>
    New order has been created
</h1>

<!-- Order details table -->
<table>
    <!-- Order ID row -->
    <tr>
        <th>Order ID</th>
        <td>
            <!-- Link to view the order; if $forAdmin is true, use the backend URL, otherwise use the frontend route -->
            <a href="{{ $forAdmin ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}">
                {{$order->id}}
            </a>
        </td>
    </tr>
    <!-- Order status row -->
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>
    <!-- Order total price row -->
    <tr>
        <th>Order Price</th>
        <td>${{$order->total_price}}</td>
    </tr>
    <!-- Order creation date row -->
    <tr>
        <th>Order Date</th>
        <td>${{$order->created_at}}</td>
    </tr>
</table>

<!-- Order items table -->
<table>
    <!-- Table header for order items -->
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    <!-- Loop through each item in the order -->
    @foreach($order->items as $item)
        <tr>
            <!-- Product image cell -->
            <td>
                <img src="{{$item->product->image}}" style="width: 100px">
            </td>
            <!-- Product title cell -->
            <td>{{$item->product->title}}</td>
            <!-- Total price for the item (unit price multiplied by quantity) -->
            <td>${{$item->unit_price * $item->quantity}}</td>
            <!-- Product quantity cell -->
            <td>{{$item->quantity}}</td>
        </tr>
    @endforeach
</table>
