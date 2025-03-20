<?php
// process_checkout.php - Process the checkout and save order to database
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart data from POST request
    $cartData = isset($_POST['cart_data']) ? $_POST['cart_data'] : '';
    $cartItems = json_decode($cartData, true);
    
    // Validate cart data
    if (empty($cartItems) || !is_array($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart data']);
        exit;
    }
    
    // Connect to database
    include 'db_connect.php';
    
    // Get customer information from form
    $customer_name = mysqli_real_escape_string($conn, $_POST['name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['email']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['address']);
    $customer_city = mysqli_real_escape_string($conn, $_POST['city']);
    $customer_zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $customer_country = mysqli_real_escape_string($conn, $_POST['country']);
    
    // Calculate total order amount
    $total_amount = 0;
    foreach ($cartItems as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    
    // Get customer ID if logged in, or set as guest
    $customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Insert order into orders table
    $order_date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO orders (customer_id, order_date, total_amount, shipping_address, shipping_city, 
            shipping_zip, shipping_country, customer_name, customer_email) 
            VALUES ('$customer_id', '$order_date', '$total_amount', '$customer_address', '$customer_city', 
            '$customer_zip', '$customer_country', '$customer_name', '$customer_email')";
    
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        
        // Insert order items into order_items table
        $items_added = true;
        foreach ($cartItems as $item) {
            $product_id = mysqli_real_escape_string($conn, $item['id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            
            if ($conn->query($sql) !== TRUE) {
                $items_added = false;
                break;
            }
        }
        
        if ($items_added) {
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Order placed successfully!',
                'order_id' => $order_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding order items: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating order: ' . $conn->error]);
    }
    
    $conn->close();
} else {
    // Not a POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>