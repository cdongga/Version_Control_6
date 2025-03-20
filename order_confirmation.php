<?php
// order_confirmation.php - Confirmation page after successful checkout
session_start();

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = intval($_GET['order_id']);

// Connect to database
include 'db_connect.php';

// Get order details
$sql = "SELECT o.*, COUNT(oi.order_item_id) as item_count, SUM(oi.quantity) as total_items 
        FROM orders o 
        JOIN order_items oi ON o.order_id = oi.order_id 
        WHERE o.order_id = $order_id 
        GROUP BY o.order_id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit;
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Shopaholics</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .check-icon {
            color: #4CAF50;
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .order-details {
            margin: 30px 0;
            text-align: left;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        
        .order-details div {
            margin-bottom: 10px;
        }
        
        .order-details strong {
            display: inline-block;
            width: 150px;
        }
        
        .continue-shopping {
            display: inline-block;
            background-color: #000;
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .continue-shopping:hover {
            background-color: #333;
        }
    </style>
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
    
    <!-- ORDER CONFIRMATION CONTENT -->
    <div class="confirmation-container">
        <i class="fas fa-check-circle check-icon"></i>
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been placed successfully. We've sent a confirmation email with all the details.</p>
        
        <div class="order-details">
            <h2>Order Summary</h2>
            <div><strong>Order ID:</strong> #<?php echo $order_id; ?></div>
            <div><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></div>
            <div><strong>Items:</strong> <?php echo $order['total_items']; ?></div>
            <div><strong>Total Amount:</strong> €<?php echo number_format($order['total_amount'], 2); ?></div>