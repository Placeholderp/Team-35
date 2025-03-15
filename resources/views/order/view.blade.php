<!-- Wrap the content in the main application layout -->
<x-app-layout>

    <!-- Container for the order details, centered with responsive width and padding -->
    <div class="container mx-auto lg:w-2/3 p-5">
        <!-- Order title showing the order number -->
        <h1 class="text-3xl font-bold mb-2">Order #{{$order->id}}</h1>

        <div class="bg-white rounded-lg p-3">
            <!-- Table displaying order meta information -->
            <table>
                <tbody>
                    <!-- Order ID row -->
                    <tr>
                        <td class="font-bold py-1 px-2">Order #</td>
                        <td>{{$order->id}}</td>
                    </tr>
                    <!-- Order date row -->
                    <tr>
                        <td class="font-bold py-1 px-2">Order Date</td>
                        <td>{{$order->created_at}}</td>
                    </tr>
                    <!-- Order status row -->
                    <tr>
                        <td class="font-bold py-1 px-2">Order Status</td>
                        <td>
                            <span class="text-white py-1 px-2 rounded {{$order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400'}}">
                                {{$order->status}}
                            </span>
                        </td>
                    </tr>
                    <!-- Order subtotal row -->
                    <tr>
                        <td class="font-bold py-1 px-2">SubTotal</td>
                        <td>${{ $order->total_price }}</td>
                    </tr>
                </tbody>
            </table>

            <hr class="my-5"/>

            <!-- Loop through each order item -->
            @foreach($order->items as $item)
                <!-- Order Item Container: Responsive flex layout -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <!-- Product image linking to the product view -->
                    <a href="{{ route('product.view', $item->product) }}"
                       class="w-36 h-32 flex items-center justify-center overflow-hidden">
                        <img src="{{$item->product->image}}" class="object-cover" alt=""/>
                    </a>
                    <!-- Product details -->
                    <div class="flex flex-col justify-between">
                        <div class="flex justify-between mb-3">
                            <!-- Product title -->
                            <h3>
                                {{$item->product->title}}
                            </h3>
                        </div>
                        <div class="flex justify-between items-center">
                            <!-- Quantity display -->
                            <div class="flex items-center">Qty: {{$item->quantity}}</div>
                            <!-- Unit price display -->
                            <span class="text-lg font-semibold"> ${{$item->unit_price}} </span>
                        </div>
                    </div>
                </div>
                <!-- Divider between order items -->
                <hr class="my-3"/>
            @endforeach

            <!-- Payment button: Only show if the order is not paid -->
            @if (!$order->isPaid())
                <form action="{{ route('cart.checkout-order', $order) }}" method="POST">
                    @csrf
                    
                    <button class="btn-primary flex items-center justify-center w-full mt-3">
                        <!-- Payment icon -->
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                            />
                        </svg>
                        <!-- Button label -->
                        Make a Payment
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
