@extends('layouts.template')
@section('content')
    <!-- Products Page -->
    <div id="productsPage" class="page">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Filters</h3>
                    
                    <!-- Search Form -->
                    <form id="searchForm" class="mb-6">
                        <div class="relative">
                            <input type="text" id="searchInput" 
                                   class="w-full px-4 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                   placeholder="Search products...">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </form>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3">Categories</h4>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       class="category-filter mr-2" 
                                       value="{{ $category->slug }}" {{ request('category') == $category->name ? 'checked' : '' }} />
                                <span>{{ $category->name }}</span>
                            </label>
                            @endforeach
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
                    
                    <!-- Clear Filters Button -->
                    <button id="clearFilters" class="w-full mt-6 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        Clear All Filters
                    </button>
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
                    @include('customer.products.partials.products_grid', ['products' => $products])
                </div>

                <!-- No Products Message -->
                <div id="noProductsMessage" class="hidden text-center py-12">
                    <i class="fas fa-search text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">No products found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or search term</p>
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let filters = {
        categories: [],
        price: null,
        sort: 'default',
        search: ''
    };
    
    // Category filter change
    document.querySelectorAll('.category-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            filters.categories = Array.from(document.querySelectorAll('.category-filter:checked'))
                .map(cb => cb.value);
            applyFilters();
        });
    });
    
    // Price filter change
    document.querySelectorAll('.price-filter').forEach(radio => {
        radio.addEventListener('change', function() {
            filters.price = this.value;
            applyFilters();
        });
    });
    
    // Sort select change
    document.getElementById('sortSelect').addEventListener('change', function() {
        filters.sort = this.value;
        applyFilters();
    });
    
    // Search input with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filters.search = this.value;
            applyFilters();
        }, 500);
    });
    
    // Clear filters button
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Reset checkboxes
        document.querySelectorAll('.category-filter').forEach(cb => cb.checked = true);
        document.querySelectorAll('.price-filter').forEach(rb => rb.checked = false);
        document.querySelector('.price-filter[value="0-50"]').checked = true;
        document.getElementById('sortSelect').value = 'default';
        document.getElementById('searchInput').value = '';
        
        // Reset filter object
        filters = {
            categories: Array.from(document.querySelectorAll('.category-filter:checked'))
                .map(cb => cb.value),
            price: '0-50',
            sort: 'default',
            search: ''
        };
        
        applyFilters();
    });
    
    function applyFilters() {
        // Show loading state
        document.getElementById('productsGrid').innerHTML = 
            '<div class="col-span-full text-center py-12"><i class="fas fa-spinner fa-spin text-2xl"></i></div>';
        
        // Build query string
        const params = new URLSearchParams();
        if (filters.categories.length > 0) {
            filters.categories.forEach(cat => params.append('categories[]', cat));
        }
        if (filters.price) params.append('price', filters.price);
        if (filters.sort) params.append('sort', filters.sort);
        if (filters.search) params.append('search', filters.search);
        
        // Make AJAX request
        fetch(`/products/filter?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('productsGrid').innerHTML = data.html;
            
            // Show/hide no products message
            const noProductsMsg = document.getElementById('noProductsMessage');
            if (data.count === 0) {
                noProductsMsg.classList.remove('hidden');
            } else {
                noProductsMsg.classList.add('hidden');
            }
            
            // Update search results info
            const searchInfo = document.getElementById('searchResultsInfo');
            const searchQuery = document.getElementById('searchQuery');
            if (filters.search) {
                searchQuery.textContent = filters.search;
                searchInfo.classList.remove('hidden');
            } else {
                searchInfo.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
</script>
@endpush