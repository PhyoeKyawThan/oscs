@extends('layouts.template')
@section('content')
    <!-- Products Page -->
    <div id="productsPage" class="page">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Filters</h3>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3">Categories</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="category-filter mr-2" value="electronics" checked>
                                <span>Electronics</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="category-filter mr-2" value="fashion" checked>
                                <span>Fashion</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="category-filter mr-2" value="home" checked>
                                <span>Home & Kitchen</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="category-filter mr-2" value="sports" checked>
                                <span>Sports & Outdoors</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3">Price Range</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="price" class="price-filter mr-2" value="0-50" checked>
                                <span>Under $50</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="price-filter mr-2" value="50-100">
                                <span>$50 - $100</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="price-filter mr-2" value="100-200">
                                <span>$100 - $200</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="price-filter mr-2" value="200+">
                                <span>Over $200</span>
                            </label>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <h4 class="font-bold mb-3">Sort By</h4>
                        <select id="sortSelect"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="default">Default</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="name">Name A-Z</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="md:w-3/4">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold">All Products</h2>
                    <p class="text-gray-500 dark:text-gray-400">Browse our wide selection of products</p>
                </div>

                <!-- Search Results Info -->
                <div id="searchResultsInfo" class="mb-4 hidden">
                    <p class="text-lg">Search results for: <span id="searchQuery" class="font-bold"></span></p>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="productsGrid">
                    <!-- Products will be dynamically loaded here -->
                </div>

                <!-- No Products Message -->
                <div id="noProductsMessage" class="hidden text-center py-12">
                    <i class="fas fa-search text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">No products found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or search term</p>
                </div>
            </div>
        </div>
    </div>
@endsection