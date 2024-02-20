<?php
// Include your database connection file
include_once("../auth/connection/config.php");
session_start();

// Retrieve product ID from the AJAX request
$data = json_decode(file_get_contents("php://input"));
$productId = $data->productId;

// Replace the following line with your actual mechanism to get the user ID
$customerId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

// Get the user's unique identifier (using a cookie)
$userIdentifier = isset($_COOKIE['user_identifier']) ? $_COOKIE['user_identifier'] : null;

if (!$userIdentifier) {
    // If the user doesn't have a cookie, generate a unique identifier and set the cookie
    $userIdentifier = uniqid();
    setcookie('user_identifier', $userIdentifier, time() + (365 * 24 * 60 * 60)); // Set the cookie to last for a year
}

try {
    $connection->beginTransaction();

    // Debugging: Add debugging statements
    error_log('Debug: $customerId - ' . $customerId);
    error_log('Debug: $userIdentifier - ' . $userIdentifier);

    // Fetch product details based on $productId
    $sqlGetProductDetails = "SELECT price FROM products WHERE id = ?";
    $stmtGetProductDetails = $connection->prepare($sqlGetProductDetails);
    $stmtGetProductDetails->execute([$productId]);
    $productDetails = $stmtGetProductDetails->fetch(PDO::FETCH_ASSOC);

    if (!$productDetails) {
        throw new Exception('Product not found');
    }

    // Extract product details
    $productPrice = $productDetails['price'];
    $quantity = 1; // Assuming a constant quantity of 1

    // Step 2: Check if a wishlist already exists for the customer and user identifier
    $sqlCheckWishlist = "SELECT wishlistId FROM wishlists WHERE customerId = COALESCE(?, customerId) AND userIdentifier = COALESCE(?, userIdentifier)";
    $stmtCheckWishlist = $connection->prepare($sqlCheckWishlist);
    $stmtCheckWishlist->execute([$customerId, $userIdentifier]);
    $wishlistId = $stmtCheckWishlist->fetchColumn();

    // If the WishlistID does not exist, insert a new wishlist record
    if (!$wishlistId) {
        $sqlInsertNewWishlist = "INSERT INTO wishlists (customerId, userIdentifier) VALUES (?, ?)";
        $stmtInsertNewWishlist = $connection->prepare($sqlInsertNewWishlist);
        $stmtInsertNewWishlist->execute([$customerId, $userIdentifier]);
        $wishlistId = $connection->lastInsertId();
    }

    // Set a default priority value or define $priority as needed
    $priority = 1; // Adjust as necessary

    // Step 4: Insert the product into the wishlist_items table
    $sqlInsertWishlistItem = "INSERT INTO wishlist_items (wishlistId, productId, priority, quantity, itemPrice, userIdentifier)
                              VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsertWishlistItem = $connection->prepare($sqlInsertWishlistItem);
    $stmtInsertWishlistItem->execute([$wishlistId, $productId, $priority, $quantity, $productPrice, $userIdentifier]);

    // Debugging: Add debugging statement
    error_log('Debug: Product added to wishlist successfully');

    // If successful, return a JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Product added to wishlist successfully']);

    $connection->commit();
} catch (Exception $e) {
    // Debugging: Add debugging statement
    error_log('Debug: Error adding product to wishlist: ' . $e->getMessage());

    // If there's an exception, return a JSON response with an error message
    $connection->rollBack();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error adding product to wishlist: ' . $e->getMessage()]);
} finally {
    // Close the database connection
    $connection = null;
}
?>
