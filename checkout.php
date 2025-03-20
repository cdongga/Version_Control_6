<?php
// checkout.php - Checkout page
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Shopaholics</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <!-- HEADER & NAVIGATION (Same as other pages) -->
    <header>
        <h3 class="promo"> USE CODE [NEW2025] FOR EXTRA UP TO 20% SKINCARE PRODUCTS </h3>
    </header>
    
    <nav>
        <div class="logo">
            <h1><a href="index.php">SHOPAHOLICS</a></h1>
        </div>
        
        <ul class="nav-menu">
            <?php
            $menu_items = [
                "Home Page  " => "index.php",
                "Home" => "homeStore.php",
                "Technology" => "technology.php",
                "Skincare" => "skincare.php",
                "Makeup" => "makeup.php"
            ];
            foreach ($menu_items as $name => $link) {
                echo "<li><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($name) . "</a></li>";
            }
            ?>
        </ul>

        <div class="nav-right">
            <div class="header-icons">
                <div class="country-selector">
                    <img src="IMG/eu-flag.png" alt="EU Flag">
                </div>
                <a href="signup_login.php">
                    <i class="fas fa-user"></i>
                </a>
                <i class="fas fa-heart"></i>
                <div class="cart-dropdown">
                    <div class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- CHECKOUT CONTENT -->
    <div class="checkout-container">
        <h1>Checkout</h1>
        
        <div class="checkout-content">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div id="checkout-items">
                    <!-- Cart items will be loaded here by JavaScript -->
                </div>
                <div id="checkout-total">
                    <!-- Total will be displayed here -->
                </div>
            </div>
            
            <div class="checkout-form">
                <h2>Shipping Information</h2>
                <form id="checkout-form" action="process_checkout.php" method="post">
                    <input type="hidden" name="cart_data" id="cart-data">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="zip">Postal Code</label>
                            <input type="text" id="zip" name="zip" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" required>
                    </div>
                    
                    <h2>Payment Information</h2>
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="checkout-button">Place Order</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Shopaholics - Your go-to store for tech, skincare, and more.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="technology.php">Technology</a></li>
                    <li><a href="skincare.php">Skincare</a></li>
                    <li><a href="makeup.php">Makeup</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Meet your Owners</h3>
                <p>Cheryl Donga</p>
                <p>Mireille Aka</p>
                <p>Vivien Obi</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        <p class="footer-bottom">&copy; 2025 Shopaholics. All rights reserved.</p>
    </footer>
    
    <!-- JavaScript for checkout page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart data from localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const checkoutItems = document.getElementById('checkout-items');
            const checkoutTotal = document.getElementById('checkout-total');
            const cartDataInput = document.getElementById('cart-data');
            
            // Populate the hidden form field with cart data
            cartDataInput.value = JSON.stringify(cart);
            
            // Display cart items in checkout
            if (cart.length === 0) {
                checkoutItems.innerHTML = '<p>Your cart is empty. Please add some products before checkout.</p>';
            } else {
                let itemsHTML = '';
                let totalPrice = 0;
                
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    totalPrice += itemTotal;
                    
                    itemsHTML += `
                        <div class="checkout-item">
                            <div class="item-image">
                                <img src="${item.image}" alt="${item.name}">
                            </div>
                            <div class="item-details">
                                <h4>${item.name}</h4>
                                <p>€${item.price.toFixed(2)} x ${item.quantity}</p>
                            </div>
                            <div class="item-total">
                                €${itemTotal.toFixed(2)}
                            </div>
                        </div>
                    `;
                });
                
                checkoutItems.innerHTML = itemsHTML;
                checkoutTotal.innerHTML = `
                    <div class="subtotal">
                        <span>Subtotal:</span>
                        <span>€${totalPrice.toFixed(2)}</span>
                    </div>
                    <div class="shipping">
                        <span>Shipping:</span>
                        <span>€5.00</span>
                    </div>
                    <div class="total">
                        <span>Total:</span>
                        <span>€${(totalPrice + 5).toFixed(2)}</span>
                    </div>
                `;
            }
            
            // Handle form submission
            const checkoutForm = document.getElementById('checkout-form');
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate cart is not empty
                if (cart.length === 0) {
                    alert('Your cart is empty. Please add some products before checkout.');
                    return;
                }
                
                // Submit form using fetch API
                fetch('process_checkout.php', {
                    method: 'POST',
                    body: new FormData(checkoutForm)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear cart
                        localStorage.removeItem('cart');
                        
                        // Redirect to order confirmation
                        window.location.href = `order_confirmation.php?order_id=${data.order_id}`;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during checkout. Please try again.');
                });
            });
        });
    </script>
</body>
</html>