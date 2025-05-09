<?php
    /** @var \Illuminate\Database\Eloquent\Collection $orders */
?>

<x-app-layout>
    <!-- Main container with responsive width and padding -->
    <div class="container mx-auto lg:w-2/3 p-5">
        <!-- Page title -->
        <h1 class="text-3xl font-bold mb-2">My Orders</h1>
        
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
            <!-- Orders table -->
            <table class="table-auto w-full">
                <!-- Table header -->
                <thead>
                    <tr class="border-b-2">
                        <th class="text-left p-2">Order #</th>
                        <th class="text-left p-2">Date</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">SubTotal</th>
                        <th class="text-left p-2">Items</th>
                        <th class="text-left p-2">Actions</th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody>
                    <!-- Loop through each order in the collection -->
                    @foreach($orders as $order)
                        <tr class="border-b">
                            <!-- Order ID cell with a link to view order details -->
                            <td class="py-1 px-2">
                                <a href="{{ route('order.view', $order) }}"
                                   class="text-purple-600 hover:text-purple-500">
                                    #{{$order->id}}
                                </a>
                            </td>
                            <!-- Order creation date cell -->
                            <td class="py-1 px-2 whitespace-nowrap">{{$order->created_at}}</td>
                            <!-- Order status cell with a conditional badge -->
                            <td class="py-1 px-2">
                                <small class="text-white py-1 px-2 rounded 
                                    {{$order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}">
                                    {{$order->status}}
                                </small>
                            </td>
                            <!-- Order total price cell -->
                            <td class="py-1 px-2">${{$order->total_price}}</td>
                            <!-- Number of items in the order -->
                            <td class="py-1 px-2 whitespace-nowrap">{{$order->items()->count()}} item(s)</td>
                            <!-- shows a payment button if the order is not paid -->
                            <td class="py-1 px-2 flex gap-2 w-[100px]">
                                @if (!$order->isPaid())
                                    <form action="{{ route('cart.checkout-order', $order) }}" method="POST">
                                        @csrf
                                        <button class="flex items-center py-1 btn-primary whitespace-nowrap">
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
                                            Pay
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination links for navigating through the orders -->
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
