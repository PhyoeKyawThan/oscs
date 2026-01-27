class CartManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCartCount();
    }

    bindEvents() {
        // Cart toggle button
        const cartToggle = document.getElementById('cartToggle');
        if (cartToggle) {
            cartToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.showCart();
            });
        }

        // Add this to bindEvents() method
        document.addEventListener('click', (e) => {
            if (e.target.closest('.add-to-cart')) {
                e.preventDefault();
                const button = e.target.closest('.add-to-cart');
                const productId = button.dataset.productId;
                if (productId && !button.disabled) {
                    this.addToCart(productId);
                }
            }
        });

        // Close cart sidebar
        document.getElementById('closeCart')?.addEventListener('click', () => this.hideCart());
        document.getElementById('cartOverlay')?.addEventListener('click', () => this.hideCart());

        // View cart details button
        document.getElementById('viewCart')?.addEventListener('click', () => {
            window.location.href = '/cart';
        });

        // Checkout button
        document.getElementById('checkoutBtn')?.addEventListener('click', () => {
            this.checkout();
        });

        // Continue shopping
        document.querySelector('.continue-shopping')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.hideCart();
        });
    }

    async loadCartCount() {
        try {
            const response = await fetch('/cart/data', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateCartCountUI(data.cart_count);
                return data.cart_count;
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
        return 0;
    }

    updateCartCountUI(count) {
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            // Show/hide based on count
            if (count > 0) {
                cartCountElement.classList.remove('hidden');
            } else {
                cartCountElement.classList.add('hidden');
            }
        }
    }

    showCart() {
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');

        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.remove('translate-x-full');
            cartOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            this.loadCartItems();
        }
    }

    hideCart() {
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');

        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.add('translate-x-full');
            cartOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    async loadCartItems() {
        try {
            const response = await fetch('/cart/data', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateCartUI(data);
            }
        } catch (error) {
            console.error('Error loading cart items:', error);
        }
    }

    updateCartUI(data) {
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const cartItemCount = document.getElementById('cartItemCount');
        const cartSubtotal = document.getElementById('cartSubtotal');
        const cartTotal = document.getElementById('cartTotal');

        if (!cartItemsContainer || !cartItemCount || !cartSubtotal || !cartTotal) return;

        // Update cart header info
        cartItemCount.textContent = `${data.cart_count} ${data.cart_count === 1 ? 'item' : 'items'}`;

        // Calculate totals
        const subtotal = parseFloat(data.cart_total) || 0;
        const shipping = subtotal > 50 ? 0 : 5.99; // Free shipping over $50
        const total = subtotal + shipping;

        cartSubtotal.textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('cartShipping').textContent = shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`;
        cartTotal.textContent = `$${total.toFixed(2)}`;

        // Update cart items
        if (data.cart_count > 0 && data.cart_items) {
            // Hide empty message
            if (emptyCartMessage) {
                emptyCartMessage.classList.add('hidden');
            }

            let html = '';
            Object.values(data.cart_items).forEach(item => {
                const itemTotal = (item.price * item.quantity).toFixed(2);
                html += `
                <div class="cart-item flex items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700" 
                     data-product-id="${item.id}">
                    <!-- Product Image -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="${item.image}" 
                             alt="${item.name}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Product Info -->
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-sm mb-1">${item.name}</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-2">${item.category}</p>
                        <p class="text-primary font-bold">$${parseFloat(item.price).toFixed(2)} each</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total: $${itemTotal}</p>
                    </div>
                    
                    <!-- Quantity Controls -->
                    <div class="ml-4 flex flex-col items-end">
                        <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg mb-2">
                            <button class="px-3 py-1 text-gray-500 hover:text-primary decrease-qty" 
                                    data-product-id="${item.id}">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="number" 
                                   value="${item.quantity}" 
                                   min="1" max="${item.stock}"
                                   class="w-12 text-center border-0 focus:ring-0 bg-transparent quantity-input"
                                   data-product-id="${item.id}"
                                   readonly>
                            <button class="px-3 py-1 text-gray-500 hover:text-primary increase-qty"
                                    data-product-id="${item.id}">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                        
                        <!-- Remove Button -->
                        <button class="text-red-500 hover:text-red-700 text-sm remove-from-cart"
                                data-product-id="${item.id}">
                            <i class="fas fa-trash-alt mr-1"></i> Remove
                        </button>
                    </div>
                </div>`;
            });

            cartItemsContainer.innerHTML = html;

            // Bind events to dynamic elements
            this.bindCartItemEvents();
        } else {
            // Show empty message
            if (emptyCartMessage) {
                emptyCartMessage.classList.remove('hidden');
            }
            cartItemsContainer.innerHTML = '';
            cartItemsContainer.appendChild(emptyCartMessage);
        }
    }

    bindCartItemEvents() {
        // Quantity decrease
        cartItemsContainer.querySelectorAll('.decrease-qty').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.currentTarget.dataset.productId;
                const input = cartItemsContainer.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                let newQty = parseInt(input.value) - 1;

                if (newQty >= 1) {
                    this.updateCartItem(productId, newQty);
                }
            });
        });

        // Quantity increase
        cartItemsContainer.querySelectorAll('.increase-qty').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.currentTarget.dataset.productId;
                const input = cartItemsContainer.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                let newQty = parseInt(input.value) + 1;

                this.updateCartItem(productId, newQty);
            });
        });

        // Quantity input change
        cartItemsContainer.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const productId = e.target.dataset.productId;
                let newQty = parseInt(e.target.value);

                if (newQty >= 1) {
                    this.updateCartItem(productId, newQty);
                }
            });
        });

        // Remove item
        cartItemsContainer.querySelectorAll('.remove-from-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.currentTarget.dataset.productId;
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    this.removeFromCart(productId);
                }
            });
        });
    }

    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message, 'success');
                this.updateCartCountUI(data.cart_count);

                // Update cart sidebar if open
                const cartSidebar = document.getElementById('cartSidebar');
                if (cartSidebar && !cartSidebar.classList.contains('translate-x-full')) {
                    this.loadCartItems();
                }

                return true;
            } else {
                this.showNotification(data.message, 'error');
                return false;
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
            return false;
        }
    }

    async updateCartItem(productId, quantity) {
        try {
            const response = await fetch('/cart/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCountUI(data.cart_count);
                this.updateCartUI(data);
                this.showNotification('Quantity updated', 'success');
            } else {
                this.showNotification(data.message, 'error');
                // Reload cart to get correct values
                this.loadCartItems();
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        }
    }

    async removeFromCart(productId) {
        try {
            const response = await fetch('/cart/remove', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCountUI(data.cart_count);
                this.updateCartUI(data);
                this.showNotification('Item removed from cart', 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        }
    }

    checkout() {
        // Check if cart has items
        const cartCount = parseInt(document.getElementById('cartCount').textContent) || 0;
        if (cartCount === 0) {
            this.showNotification('Your cart is empty', 'error');
            return;
        }

        window.location.href = '/checkout';
    }

    showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.cart-notification').forEach(el => el.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `cart-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } z-50 transform transition-transform duration-300`;
        notification.textContent = message;
        notification.style.transform = 'translateX(100%)';

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Initialize cart manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    window.cartManager = new CartManager();

    // Make addToCart function globally available for inline onclick
    window.addToCart = function (productId, quantity = 1) {
        if (window.cartManager) {
            return window.cartManager.addToCart(productId, quantity);
        }
        return false;
    };
});