<?php
// Start or resume the session
session_start();

// Connection to the database
require_once '../auth/connection/config.php';

// Function to get user's IP address
function getIpAddress() {
    // Check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Define the addtocart page URL
$addToCartPage = "addtocart.php";

// Handle adding product to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productId"])) {
    try {
        // Retrieve product ID from the POST request
        $productId = $_POST["productId"];

        // Initialize customerId
        $customerId = null;

        // Check if the user is logged in
        if (isset($_SESSION['userId'])) {
            // Use the existing customer ID
            $customerId = $_SESSION['userId'];
        }

        // Get user's IP address
        $userIpAddress = getIpAddress();

        // Fetch the price of the product from the database
        $stmtPrice = $connection->prepare("SELECT price FROM products WHERE id = ?");
        $stmtPrice->execute([$productId]);
        $productPrice = $stmtPrice->fetchColumn();

        // Set the default quantity (modify as needed)
        $quantity = 1;

        // Insert data into the cart table
        $stmtCart = $connection->prepare("INSERT INTO cart (customer_id, ip_address) VALUES (?, ?)");
        $stmtCart->execute([$customerId, $userIpAddress]);
        $cartId = $connection->lastInsertId();

        // Insert data into the cart_items table
        $stmtItems = $connection->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtItems->execute([$cartId, $productId, $quantity, $productPrice]);

        // Check if the insertion was successful
        if ($stmtItems->rowCount() > 0) {
            // Product added to cart successfully
            header("Location: ../cart.php");
            exit();
        } else {
            // If no rows were affected, there might be an issue with the insertion
            header("Location: ./cart.php?error=Error%20adding%20product%20to%20cart:%20No%20rows%20affected");
            exit();
        }
    } catch (PDOException $e) {
        // Send error response
        header("Location: ./cart.php?error=Error%20adding%20product%20to%20cart:%20" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Invalid request
    header("Location: ./cart.php?error=Invalid%20request");
    exit();
}
?>
