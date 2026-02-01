@if($products->count() > 0)
    @foreach($products as $product)
        <a href="{{ route('customer.product.view', ['id' => $product->id]) }}"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow block group">
            <!-- Product Image -->
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $product->product_image }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @if($product->stock === 0)
                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs">
                        Out of Stock
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="p-4">
                <!-- Category Badge -->
                <div class="mb-2">
                    <span class="inline-block px-2 py-1 bg-primary bg-opacity-10 text-gray-200 text-xs rounded-full">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>
                </div>

                <!-- Product Name -->
                <h3 class="font-bold text-lg mb-2 truncate group-hover:text-primary transition-colors">{{ $product->name }}</h3>

                <!-- Product Description -->
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                    {{ $product->description }}
                </p>

                <!-- Price and Actions -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xl font-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                            <p class="text-xs text-green-500">{{ $product->stock }} in stock</p>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        <!-- Wishlist button - prevent default to avoid navigating -->
                        <button class="p-2 text-gray-500 hover:text-primary transition-colors wishlist-btn"
                            onclick="event.preventDefault(); toggleWishlist({{ $product->id }})">
                            <i class="far fa-heart"></i>
                        </button>

                        <!-- Add to cart button - prevent default -->
                        <button onclick="event.preventDefault();" class="p-2 bg-primary text-white rounded-lg hover:bg-opacity-90 transition-colors add-to-cart"
                            data-product-id="{{ $product->id }}" {{ $product->stock === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
@else
    <div class="col-span-full text-center py-12">
        <i class="fas fa-search text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold mb-2">No products found</h3>
        <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or search term</p>
    </div>
@endif

@push('scripts')
    <script>
        // Prevent event bubbling when clicking on buttons inside the card
        document.addEventListener('click', function (e) {
            if (e.target.closest('.wishlist-btn') || e.target.closest('.cart-btn')) {
                e.stopPropagation();
            }
        });

        function toggleWishlist(productId) {
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update heart icon
                        const heartIcon = document.querySelector(`[data-product="${productId}"] .fa-heart`);
                        if (heartIcon) {
                            if (data.in_wishlist) {
                                heartIcon.classList.remove('far');
                                heartIcon.classList.add('fas', 'text-red-500');
                            } else {
                                heartIcon.classList.remove('fas', 'text-red-500');
                                heartIcon.classList.add('far');
                            }
                        }
                        showNotification(data.message, 'success');
                    }
                });
        }

        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart!', 'success');
                        updateCartCount(data.cart_count);
                    } else {
                        showNotification(data.message || 'Failed to add to cart', 'error');
                    }
                });
        }
    </script>
@endpush