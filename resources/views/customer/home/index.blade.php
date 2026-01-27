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
                data-category="electronics">
                <div
                    class="h-16 w-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-laptop text-2xl text-primary"></i>
                </div>
                <h3 class="font-bold text-lg">Electronics</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Phones, Laptops & more</p>
            </div>

            <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                data-category="fashion">
                <div
                    class="h-16 w-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tshirt text-2xl text-secondary"></i>
                </div>
                <h3 class="font-bold text-lg">Fashion</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Clothing, Shoes & Accessories</p>
            </div>

            <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                data-category="home">
                <div
                    class="h-16 w-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-2xl text-accent"></i>
                </div>
                <h3 class="font-bold text-lg">Home & Kitchen</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Furniture, Appliances & Decor</p>
            </div>

            <div class="category-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all-300 cursor-pointer"
                data-category="sports">
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
            <a href="#" class="text-primary font-medium hover:underline" data-page="products">View All</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="featuredProducts">
            <!-- Products will be dynamically loaded here -->
        </div>
    </section>
</div>
@endsection
