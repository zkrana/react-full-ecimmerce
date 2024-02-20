<?php
// Start or resume the session
session_start();

// Connection to the database
require_once '../auth/connection/config.php';

// Check if the request method is POST and the wishlistItemId is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["wishlistItemId"])) {
    try {
        // Retrieve wishlist item ID from the POST request
        $wishlistItemId = $_POST["wishlistItemId"];

        // Get the wishlist ID before deletion
        $stmtGetWishlistId = $connection->prepare("SELECT wishlistId FROM wishlist_items WHERE wishlistItemId = ?");
        $stmtGetWishlistId->execute([$wishlistItemId]);
        $wishlistId = $stmtGetWishlistId->fetchColumn();

        // Perform the deletion query on wishlist_items table
        $stmtWishlistItems = $connection->prepare("DELETE FROM wishlist_items WHERE wishlistItemId = ?");
        $stmtWishlistItems->execute([$wishlistItemId]);

        // Check if the deletion was successful
        if ($stmtWishlistItems->rowCount() > 0) {
            // Check if the wishlist is empty
            $stmtCheckEmpty = $connection->prepare("SELECT COUNT(*) FROM wishlist_items WHERE wishlistId = ?");
            $stmtCheckEmpty->execute([$wishlistId]);
            $itemCount = $stmtCheckEmpty->fetchColumn();

            if ($itemCount == 0) {
                // Wishlist is empty, delete the wishlist
                $stmtDeleteWishlist = $connection->prepare("DELETE FROM wishlists WHERE wishlistId = ?");
                $stmtDeleteWishlist->execute([$wishlistId]);
            }

            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return error response if no rows were affected
            echo json_encode(['success' => false, 'message' => 'No rows affected.', 'wishlistItemId' => $wishlistItemId]);
        }
    } catch (PDOException $e) {
        // Log the error message for debugging
        error_log('Error: ' . $e->getMessage());

        // Send error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Return error response if invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
