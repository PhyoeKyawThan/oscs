@extends('layouts.template')
@section('content')
    <!-- Product Details Page -->
    <div id="productDetailsPage" class="page">
        <div class="max-w-6xl mx-auto">
            <!-- Back button -->
            <a href="{{ route('products.index') }}" class="mb-6 inline-flex items-center text-primary hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>

            <!-- Product Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Product Image -->
                    <div class="md:w-1/2 p-8">
                        <div class="relative h-96 overflow-hidden rounded-lg">
                            <img src="{{ $product->product_image }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">

                            @if($product->stock === 0)
                                <div
                                    class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                    Out of Stock
                                </div>
                            @elseif($product->stock < 10)
                                <div
                                    class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                    Low Stock
                                </div>
                            @endif
                        </div>

                        <!-- Additional images gallery (if you have multiple images) -->
                        <div class="flex space-x-2 mt-4">
                            <div class="w-20 h-20 border-2 border-primary rounded-lg overflow-hidden cursor-pointer">
                                <img src="{{ $product->product_image }}" class="w-full h-full object-cover">
                            </div>
                            <!-- Add more thumbnails here if you have multiple images -->
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="md:w-1/2 p-8">
                        <!-- Category Badge -->
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 bg-primary bg-opacity-10 text-primary text-sm rounded-full">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        <!-- Product Name -->
                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                        <!-- Price -->
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-primary">${{ number_format($product->price, 2) }}</span>

                            <!-- Stock Status -->
                            <div class="mt-2">
                                @if($product->stock > 0)
                                    <p class="text-green-500">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $product->stock }} in stock
                                    </p>
                                @else
                                    <p class="text-red-500">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Out of stock
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-2">Description</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-3">Product Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Category</p>
                                    <p class="font-medium">{{ $product->category->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">SKU</p>
                                    <p class="font-medium">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Availability</p>
                                    <p class="font-medium {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Shipping</p>
                                    <p class="font-medium">Free Shipping</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                <button class="px-4 py-2 text-gray-500 hover:text-primary" id="decreaseQty">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                    class="w-16 text-center border-0 focus:ring-0 bg-transparent">
                                <button class="px-4 py-2 text-gray-500 hover:text-primary" id="increaseQty">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button id="addToCart"
                                class="flex-1 bg-primary text-white py-3 rounded-lg hover:bg-opacity-90 transition-colors font-semibold flex items-center justify-center add-to-cart"
                                data-product-id="{{ $product->id }}" {{ $product->stock === 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Add to Cart
                            </button>

                            <!-- Wishlist Button -->
                            <button
                                class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary hover:text-primary transition-colors">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="relatedProducts">
                    <!-- Related products will be loaded here via AJAX -->
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading related products...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadRelatedProducts();
            function loadRelatedProducts() {
                fetch(`/products/{{ $product->id }}/related`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('relatedProducts');

                        if (data.products && data.products.length > 0) {
                            let html = '';
                            data.products.forEach(product => {
                                html += `
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                    <a href="/product/${product.id}/view">
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="${product.product_image}" 
                                                 alt="${product.name}"
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold mb-2 truncate">${product.name}</h3>
                                            <p class="text-primary font-bold text-lg">$${parseFloat(product.price).toFixed(2)}</p>
                                        </div>
                                    </a>
                                </div>`;
                            });
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">No related products found.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading related products:', error);
                    });
            }
        });
    </script>
@endpush