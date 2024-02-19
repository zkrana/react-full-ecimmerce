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

    // Step 2: Insert a new wishlist record with the user's unique identifier
    $sqlInsertWishlist = "INSERT IGNORE INTO wishlists (customerId, userIdentifier) VALUES (?, ?)";
    $stmtInsertWishlist = $connection->prepare($sqlInsertWishlist);
    $stmtInsertWishlist->execute([$customerId, $userIdentifier]);

    // Step 3: Get the WishlistID for the customer (or null)
    if ($customerId !== null) {
        $sqlGetWishlistID = "SELECT wishlistId FROM wishlists WHERE customerId = ? AND userIdentifier IS NULL";
    } else {
        $sqlGetWishlistID = "SELECT wishlistId FROM wishlists WHERE userIdentifier = ? AND customerId IS NULL";
    }

    $stmtGetWishlistID = $connection->prepare($sqlGetWishlistID);
    $stmtGetWishlistID->execute([$customerId ?? $userIdentifier]);

    // If the WishlistID exists for the customer, use it; otherwise, insert a new wishlist record
    if ($wishlistId = $stmtGetWishlistID->fetchColumn()) {
        // Step 4: Insert the product into the wishlist_items table
        $sqlInsertWishlistItem = "INSERT INTO wishlist_items (wishlistId, productId, priority, quantity, itemPrice, userIdentifier)
                                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertWishlistItem = $connection->prepare($sqlInsertWishlistItem);
        $stmtInsertWishlistItem->execute([$wishlistId, $productId, $priority, $quantity, $productPrice, $userIdentifier]);

        // If successful, return a JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Product added to wishlist successfully']);
    } else {
        // If the WishlistID is not found, return a JSON response with an error message
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error adding product to wishlist: Wishlist not found']);
    }

    $connection->commit();
} catch (Exception $e) {
    // If there's an exception, return a JSON response with an error message
    $connection->rollBack();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error adding product to wishlist: ' . $e->getMessage()]);
}

// Close the database connection
$connection = null;
?>
