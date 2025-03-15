<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Welcome to Our Store</h1>
        
        @if($products->count())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No products available at the moment.</p>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartForms = document.querySelectorAll('form[action^="{{ url('cart/add/') }}"]');
            
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update cart count in the header/navigation
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.count;
                        }
                        
                        // Optional: Show a toast or notification
                        alert('Product added to cart!');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add product to cart');
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>