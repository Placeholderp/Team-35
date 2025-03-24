<!-- Heading displaying the new order status -->
<h2>
    Your order status was changed into "{{$order->status}}"
</h2>

<!-- Paragraph with a link to view the order -->
<p>
    Link to your order:
    <!-- The link navigates to the order view route. -->
    <a href="{{route('order.view', $order, true)}}">Order #{{$order->id}}</a>
</p>
