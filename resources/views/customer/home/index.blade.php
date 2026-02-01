@extends('layouts.template')
@section('content')
    <!-- Home Page (Default) -->
    <div id="homePage" class="page active">
        <!-- Hero Section -->
        <section class="mb-12">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-8 md:p-12 text-white">
                <div class="max-w-2xl">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4">Discover Amazing Products</h2>
                    <p class="text-xl mb-6 opacity-90">Shop the latest trends in electronics, fashion, home goods
                        and more with free shipping on orders over $50.</p>
                    <a href="#"
                        class="inline-block bg-white text-primary font-bold px-6 py-3 rounded-lg hover:bg-gray-100 transition-all-300"
                        data-page="products">Shop Now</a>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Shop by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                    data-category="electronics" onclick="openProductWithCategory('Electronics')">
                    <div
                        class="h-16 w-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-laptop text-2xl text-primary"></i>
                    </div>
                    <h3 class="font-bold text-lg">Electronics</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Phones, Laptops & more</p>
                </div>

                <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                    data-category="fashion" onclick="openProductWithCategory('Fashion')">
                    <div
                        class="h-16 w-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tshirt text-2xl text-secondary"></i>
                    </div>
                    <h3 class="font-bold text-lg">Fashion</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Clothing, Shoes & Accessories</p>
                </div>

                <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                    data-category="home" onclick="openProductWithCategory('Home & Kitchen')">
                    <div
                        class="h-16 w-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-2xl text-accent"></i>
                    </div>
                    <h3 class="font-bold text-lg" >Home & Kitchen</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Furniture, Appliances & Decor</p>
                </div>

                <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                    data-category="sports" onclick="openProductWithCategory('Sports & Outdoors')">
                    <div
                        class="h-16 w-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-futbol text-2xl text-red-500"></i>
                    </div>
                    <h3 class="font-bold text-lg">Sports & Outdoors</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Fitness, Camping & Gear</p>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="text-primary font-medium hover:underline" data-page="products">View All</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="featuredProducts">
                @if($featured_products->count() > 0)
                    @foreach($featured_products as $product)
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
                                    <span class="inline-block px-2 py-1 bg-primary bg-opacity-10 text-gray-200 font-bold text-xs rounded-full">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>

                                <!-- Product Name -->
                                <h3 class="font-bold text-lg mb-2 truncate group-hover:text-primary transition-colors">
                                    {{ $product->name }}</h3>

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
                                        <button onclick="event.preventDefault();"
                                            class="p-2 bg-primary text-white rounded-lg hover:bg-opacity-90 transition-colors add-to-cart"
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
            </div>
        </section>
    </div>
@endsection

@push("scripts")
<script>
    function openProductWithCategory(category){
        window.location.href = "/products?category=" +category;
    }
</script>
@endpush