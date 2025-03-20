// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart from localStorage if available
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartUI();

    // Add event listeners to all "ADD TO BAG" buttons
    const addToCartButtons = document.querySelectorAll('.add-to-bag');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', addToCart);
    });

    // Toggle cart dropdown visibility
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function() {
            const cartContent = document.querySelector('.cart-content');
            cartContent.classList.toggle('active');
        });
    }

    // Close cart when clicking outside
    document.addEventListener('click', function(event) {
        const cartDropdown = document.querySelector('.cart-dropdown');
        const cartContent = document.querySelector('.cart-content');
        
        if (cartDropdown && !cartDropdown.contains(event.target) && cartContent.classList.contains('active')) {
            cartContent.classList.remove('active');
        }
    });

    // Function to add a product to cart
    function addToCart(event) {
        const button = event.target;
        const productId = button.getAttribute('data-product-id');
        const productCard = button.closest('.product-card');
        
        // Get product details
        const productName = productCard.querySelector('.product-title').textContent;
        const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace('€', ''));
        const productImage = productCard.querySelector('img').getAttribute('src');
        
        // Check if product is already in cart
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            });
        }
        
        // Save to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update UI
        updateCartUI();
        showNotification('Product added to cart!', 'success');
    }

    // Function to update cart UI (count and dropdown)
    function updateCartUI() {
        const cartCount = document.querySelector('.cart-count');
        const cartItems = document.getElementById('cart-items');
        const emptyCartMsg = document.querySelector('.empty-cart');
        
        // Update count
        let totalItems = 0;
        cart.forEach(item => {
            totalItems += item.quantity;
        });
        cartCount.textContent = totalItems;
        
        // Update dropdown content
        if (cartItems) {
            cartItems.innerHTML = '';
            
            if (cart.length === 0) {
                if (emptyCartMsg) emptyCartMsg.style.display = 'block';
            } else {
                if (emptyCartMsg) emptyCartMsg.style.display = 'none';
                
                // Create cart item list
                let totalPrice = 0;
                
                cart.forEach(item => {
                    const cartItem = document.createElement('li');
                    cartItem.className = 'cart-item';
                    totalPrice += item.price * item.quantity;
                    
                    cartItem.innerHTML = `
                        <div class="cart-item-image">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <p>€${item.price.toFixed(2)}</p>
                            <div class="cart-item-quantity">
                                <span class="quantity-btn decrease" data-id="${item.id}">-</span>
                                <span>${item.quantity}</span>
                                <span class="quantity-btn increase" data-id="${item.id}">+</span>
                            </div>
                        </div>
                        <button class="remove-item" data-id="${item.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    
                    cartItems.appendChild(cartItem);
                });
                
                // Add total price
                const totalElement = document.createElement('div');
                totalElement.className = 'cart-total';
                totalElement.innerHTML = `
                    <span>Total:</span>
                    <span>€${totalPrice.toFixed(2)}</span>
                `;
                cartItems.appendChild(totalElement);
            }
            
            // Add event listeners to quantity buttons
            document.querySelectorAll('.quantity-btn.decrease').forEach(btn => {
                btn.addEventListener('click', decreaseQuantity);
            });
            
            document.querySelectorAll('.quantity-btn.increase').forEach(btn => {
                btn.addEventListener('click', increaseQuantity);
            });
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', removeItem);
            });
        }
    }

    // Function to increase product quantity
    function increaseQuantity(event) {
        const productId = event.target.getAttribute('data-id');
        const item = cart.find(item => item.id === productId);
        
        if (item) {
            item.quantity++;
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
        }
    }

    // Function to decrease product quantity
    function decreaseQuantity(event) {
        const productId = event.target.getAttribute('data-id');
        const item = cart.find(item => item.id === productId);
        
        if (item) {
            if (item.quantity > 1) {
                item.quantity--;
            } else {
                removeItem(event);
                return;
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
        }
    }

    // Enhanced Function to remove item from cart
    function removeItem(event) {
        let productId;
        
        // Handle different event sources (button or trash icon)
        if (event.target.classList.contains('remove-item')) {
            productId = event.target.getAttribute('data-id');
        } else if (event.target.closest('.remove-item')) {
            productId = event.target.closest('.remove-item').getAttribute('data-id');
        } else if (event.target.classList.contains('quantity-btn')) {
            productId = event.target.getAttribute('data-id');
        }
        
        if (productId) {
            // Remove item from cart array
            cart = cart.filter(item => item.id !== productId);
            
            // Update localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update UI
            updateCartUI();
            
            // Show notification
            showNotification('Item removed from cart', 'error');
        }
    }

    // Function to show notification
    function showNotification(message, type) {
        // Check if notification div exists, if not create it
        let notification = document.getElementById('notification');
        
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            document.body.appendChild(notification);
        }
        
        // Set message and type
        notification.textContent = message;
        notification.className = type;
        notification.style.display = 'block';
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
});