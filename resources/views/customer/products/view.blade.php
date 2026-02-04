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
                    <!-- Product Image Gallery -->
                    <div class="md:w-1/2 p-8">
                        <!-- Main Image -->
                        <div class="relative h-96 overflow-hidden rounded-lg mb-4">
                            <img id="mainProductImage" 
                                 src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-all duration-300 cursor-zoom-in"
                                 data-zoom-src="{{ $product->image_url }}">
                            
                            @if($product->stock === 0)
                                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                    Out of Stock
                                </div>
                            @elseif($product->stock < 10)
                                <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                    Low Stock
                                </div>
                            @endif
                            
                            @if($product->is_featured)
                                <div class="absolute top-4 left-4 bg-purple-500 text-white px-3 py-1 rounded-md text-sm font-semibold">
                                    <i class="fas fa-star mr-1"></i> Featured
                                </div>
                            @endif
                        </div>

                        <!-- Gallery Thumbnails -->
                        @if($product->hasGalleryImages())
                            <div class="mt-6">
                                <h4 class="font-semibold mb-3 text-lg">Gallery</h4>
                                <div class="flex flex-wrap gap-3">
                                    <!-- Main image thumbnail -->
                                    <div class="w-20 h-20 border-2 border-primary rounded-lg overflow-hidden cursor-pointer gallery-thumbnail active"
                                         data-image-src="{{ $product->image_url }}">
                                        <img src="{{ $product->image_url }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- Gallery image thumbnails -->
                                    @foreach($product->gallery_images as $index => $galleryImage)
                                        <div class="w-20 h-20 border-2 border-gray-200 rounded-lg overflow-hidden cursor-pointer gallery-thumbnail hover:border-primary transition-colors"
                                             data-image-src="{{ $galleryImage['url'] }}">
                                            <img src="{{ $galleryImage['url'] }}" 
                                                 alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Image Zoom Modal -->
                        <div id="imageZoomModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
                            <div class="relative max-w-4xl max-h-full">
                                <button id="closeZoomModal" class="absolute top-4 right-4 text-white text-3xl z-10 hover:text-gray-300">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-10 hover:text-gray-300">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl z-10 hover:text-gray-300">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                <img id="zoomedImage" class="max-w-full max-h-screen" src="" alt="">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="md:w-1/2 p-8">
                        <!-- Category Badge -->
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 bg-primary bg-opacity-10 text-gray-100 text-sm rounded-full">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        <!-- Product Name -->
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>

                        <!-- Availability Status -->
                        @if(!$product->is_available)
                            <div class="mb-3">
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">
                                    <i class="fas fa-ban mr-1"></i> Not Available
                                </span>
                            </div>
                        @endif

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
                            <div class="text-gray-600 dark:text-gray-300 leading-relaxed prose max-w-none">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- Product Specifications -->
                        <div class="mb-8 bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                            <h3 class="text-lg font-bold mb-4">Product Specifications</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Category</p>
                                    <p class="font-medium">{{ $product->category->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">SKU</p>
                                    <p class="font-medium">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                @if($product->weight)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Weight</p>
                                        <p class="font-medium">{{ $product->weight }} kg</p>
                                    </div>
                                @endif
                                @if($product->dimensions)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Dimensions</p>
                                        <p class="font-medium">{{ $product->dimensions }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Availability</p>
                                    <p class="font-medium {{ $product->is_available ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $product->is_available ? 'Available' : 'Not Available' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Status</p>
                                    <p class="font-medium {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            @if($product->is_available && $product->stock > 0)
                                <!-- Quantity Selector -->
                                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-primary decrease-qty">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="w-16 text-center border-0 focus:ring-0 bg-transparent">
                                    <button type="button" class="px-4 py-2 text-gray-500 hover:text-primary increase-qty">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Add to Cart Button -->
                                <button id="addToCart"
                                    class="flex-1 bg-primary text-white py-3 rounded-lg hover:bg-opacity-90 transition-colors font-semibold flex items-center justify-center add-to-cart"
                                    data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                </button>

                                <!-- Wishlist Button -->
                                <button type="button"
                                    class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary hover:text-primary transition-colors">
                                    <i class="far fa-heart"></i>
                                </button>
                            @else
                                <!-- Disabled state -->
                                <div class="w-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 py-3 rounded-lg text-center font-semibold">
                                    <i class="fas fa-ban mr-2"></i>
                                    {{ $product->stock === 0 ? 'Out of Stock' : 'Not Available for Purchase' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Section -->
            @if($product->category)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6">You May Also Like</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="relatedProducts">
                        <!-- Related products will be loaded here via AJAX -->
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Loading related products...</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .gallery-thumbnail.active {
        border-color: #3B82F6;
    }
    
    .gallery-thumbnail img {
        transition: transform 0.3s ease;
    }
    
    .gallery-thumbnail:hover img {
        transform: scale(1.1);
    }
    
    #imageZoomModal img {
        animation: zoomIn 0.3s ease;
    }
    
    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gallery functionality
        const mainImage = document.getElementById('mainProductImage');
        const thumbnails = document.querySelectorAll('.gallery-thumbnail');
        const zoomModal = document.getElementById('imageZoomModal');
        const zoomedImage = document.getElementById('zoomedImage');
        const closeZoomModal = document.getElementById('closeZoomModal');
        const prevImage = document.getElementById('prevImage');
        const nextImage = document.getElementById('nextImage');
        
        let currentImageIndex = 0;
        let galleryImages = [];

        // Collect all gallery images
        thumbnails.forEach((thumb, index) => {
            galleryImages.push(thumb.dataset.imageSrc);
            thumb.addEventListener('click', function() {
                // Update main image
                const newSrc = this.dataset.imageSrc;
                mainImage.src = newSrc;
                mainImage.dataset.zoomSrc = newSrc;
                
                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Update current index
                currentImageIndex = index;
            });
        });

        // Image zoom functionality
        if (mainImage) {
            mainImage.addEventListener('click', function() {
                if (this.dataset.zoomSrc) {
                    zoomedImage.src = this.dataset.zoomSrc;
                    zoomModal.classList.remove('hidden');
                    zoomModal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }
            });
        }

        // Close zoom modal
        if (closeZoomModal) {
            closeZoomModal.addEventListener('click', function() {
                zoomModal.classList.add('hidden');
                zoomModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            });
            
            // Close on background click
            zoomModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    zoomModal.classList.add('hidden');
                    zoomModal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !zoomModal.classList.contains('hidden')) {
                    zoomModal.classList.add('hidden');
                    zoomModal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }

        // Navigation in zoom modal
        if (prevImage && nextImage && galleryImages.length > 1) {
            prevImage.addEventListener('click', function(e) {
                e.stopPropagation();
                currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                zoomedImage.src = galleryImages[currentImageIndex];
                updateActiveThumbnail();
            });
            
            nextImage.addEventListener('click', function(e) {
                e.stopPropagation();
                currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                zoomedImage.src = galleryImages[currentImageIndex];
                updateActiveThumbnail();
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (!zoomModal.classList.contains('hidden')) {
                    if (e.key === 'ArrowLeft') {
                        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                        zoomedImage.src = galleryImages[currentImageIndex];
                        updateActiveThumbnail();
                    } else if (e.key === 'ArrowRight') {
                        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                        zoomedImage.src = galleryImages[currentImageIndex];
                        updateActiveThumbnail();
                    }
                }
            });
        }
        
        function updateActiveThumbnail() {
            thumbnails.forEach((thumb, index) => {
                if (index === currentImageIndex) {
                    thumb.classList.add('active');
                    mainImage.src = galleryImages[index];
                    mainImage.dataset.zoomSrc = galleryImages[index];
                } else {
                    thumb.classList.remove('active');
                }
            });
        }

        // Quantity selector functionality
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.querySelector('.decrease-qty');
        const increaseBtn = document.querySelector('.increase-qty');
        const addToCartBtn = document.getElementById('addToCart');

        if (decreaseBtn && quantityInput) {
            decreaseBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
        }

        if (increaseBtn && quantityInput) {
            increaseBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                let maxStock = parseInt(quantityInput.max);
                if (currentValue < maxStock) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }

        // Add to cart functionality
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = quantityInput ? quantityInput.value : 1;
                console.log(quantity);
                // Add your cart logic here
                console.log('Adding to cart:', { productId, quantity });
                // Implement your cart AJAX call here
            });
        }

        // Load related products
        function loadRelatedProducts() {
            fetch(`/products/{{ $product->id }}/related`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const container = document.getElementById('relatedProducts');
                    if (!container) return;

                    if (data.products && data.products.length > 0) {
                        let html = '';
                        data.products.forEach(product => {
                            const imageUrl = product.image_url || (product.image ? `{{ asset('storage', null) }}${product.image}` : '/images/placeholder.jpg');
                            
                            html += `
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                                <a href="/products/${product.id}" class="block">
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="${imageUrl}" 
                                             alt="${product.name}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        ${product.stock === 0 ? 
                                            '<div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Out of Stock</div>' : 
                                            ''}
                                        ${product.is_featured ? 
                                            '<div class="absolute top-2 left-2 bg-purple-500 text-white text-xs px-2 py-1 rounded"><i class="fas fa-star mr-1"></i> Featured</div>' : 
                                            ''}
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold mb-2 truncate">${product.name}</h3>
                                        <p class="text-primary font-bold text-lg">$${parseFloat(product.price).toFixed(2)}</p>
                                        ${product.stock > 0 ? 
                                            `<p class="text-green-500 text-sm mt-1"><i class="fas fa-check-circle mr-1"></i>In Stock</p>` : 
                                            ''}
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
                    const container = document.getElementById('relatedProducts');
                    if (container) {
                        container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">Failed to load related products.</p>';
                    }
                });
        }

        // Only load related products if the section exists
        if (document.getElementById('relatedProducts')) {
            loadRelatedProducts();
        }
    });
</script>
@endpush