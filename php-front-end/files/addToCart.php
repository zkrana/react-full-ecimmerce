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

        // Get the user's unique identifier (using a cookie)
        $userIdentifier = isset($_COOKIE['userIdentifier']) ? $_COOKIE['userIdentifier'] : null;

        if (!$userIdentifier) {
            // If the user doesn't have a cookie, generate a unique identifier and set the cookie
            $userIdentifier = uniqid();
            setcookie('userIdentifier', $userIdentifier, time() + (365 * 24 * 60 * 60));
        }

        // Now use $userIdentifier for cart operations
        $stmtCart = $connection->prepare("SELECT cart_id FROM cart WHERE customer_id = ? AND ip_address = ?");
        $stmtCart->execute([$customerId, $userIpAddress]);

        $cartData = $stmtCart->fetch();

        // Extract cart_id from the result or initialize if it doesn't exist
        $cartId = $cartData ? $cartData['cart_id'] : null;

        // If cart_id doesn't exist, insert a new record in the cart table
        if (!$cartId) {
            $stmtInsertCart = $connection->prepare("INSERT INTO cart (customer_id, ip_address) VALUES (?, ?)");
            $stmtInsertCart->execute([$customerId, $userIpAddress]);

            // Get the cart ID
            $cartId = $connection->lastInsertId();
        }

        // Now use $userIdentifier for wishlist operations
        $stmtWishlist = $connection->prepare("SELECT wishlist_items.wishlistItemId, wishlists.wishlistId
                                            FROM wishlist_items 
                                            JOIN wishlists ON wishlist_items.wishlistId = wishlists.wishlistId
                                            WHERE (wishlists.customerId = ? OR wishlists.userIdentifier = ? OR wishlists.userIdentifier = 'guest') 
                                            AND wishlist_items.productId = ?");
        $stmtWishlist->execute([$customerId, $userIdentifier, $productId]);

        $wishlistData = $stmtWishlist->fetch();

        // Extract wishlistId from the result
        $wishlistItemId = $wishlistData['wishlistItemId'];
        $wishlistId = $wishlistData['wishlistId'];

        // Debug prints
        echo "customerId: $customerId, userIdentifier: " . session_id() . ", productId: $productId, wishlistItemId: $wishlistItemId";

        if ($wishlistItemId) {
            // Remove the item from the wishlist
            $stmtRemoveWishlistItem = $connection->prepare("DELETE FROM wishlist_items WHERE wishlistItemId = ?");
            $stmtRemoveWishlistItem->execute([$wishlistItemId]);

            // Check if all wishlist items are removed, then delete wishlist
            $stmtCheckWishlist = $connection->prepare("SELECT COUNT(*) FROM wishlist_items WHERE wishlistId = ?");
            $stmtCheckWishlist->execute([$wishlistId]);
            $wishlistItemCount = $stmtCheckWishlist->fetchColumn();

            // Debug prints
            echo "wishlistId: $wishlistId, wishlistItemCount: $wishlistItemCount";

            if ($wishlistItemCount === 0) {
                $stmtDeleteWishlist = $connection->prepare("DELETE FROM wishlists WHERE wishlistId = ?");
                $stmtDeleteWishlist->execute([$wishlistId]);

                // Debug print
                echo "Wishlist deleted successfully.";
            } else {
                // Debug print
                echo "Wishlist items still exist after removal.";
            }
        } else {
            // Debug print
            echo "Product not found in the wishlist. Proceeding with adding to cart.";

            // Fetch the price and quantity of the product from the database
            $stmtProductInfo = $connection->prepare("SELECT price, stock_quantity FROM products WHERE id = ?");
            $stmtProductInfo->execute([$productId]);
            $productInfo = $stmtProductInfo->fetch();

            // Set the default quantity (modify as needed)
            $quantity = 1;

            // Check if the product is already in the cart
            $stmtCheckCart = $connection->prepare("SELECT item_id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
            $stmtCheckCart->execute([$cartId, $productId]);
            $cartItem = $stmtCheckCart->fetch();

            if ($cartItem) {
                // Product already exists in the cart, update the quantity
                $newQuantity = $cartItem['quantity'] + $quantity;

                // Check if the new quantity exceeds the available stock or the maximum quantity
                if ($newQuantity > $productInfo['stock_quantity'] || $newQuantity > 5) {
                    // Handle the case where the quantity exceeds stock or the maximum quantity (e.g., redirect with an error message)
                    header("Location: ./cart.php?error=Quantity%20exceeds%20available%20stock%20or%20maximum%20quantity");
                    exit();
                }

                $stmtUpdateCart = $connection->prepare("UPDATE cart_items SET quantity = ? WHERE item_id = ?");
                $stmtUpdateCart->execute([$newQuantity, $cartItem['id']]);
            } else {
                // Product not in the cart, proceed with adding it
                // Check if the quantity exceeds the available stock or the maximum quantity
                if ($quantity > $productInfo['stock_quantity'] || $quantity > 5) {
                    // Handle the case where the quantity exceeds stock or the maximum quantity (e.g., redirect with an error message)
                    header("Location: ./cart.php?error=Quantity%20exceeds%20available%20stock%20or%20maximum%20quantity");
                    exit();
                }

                // Check if the product is already in the cart
                $stmtCheckCart = $connection->prepare("SELECT item_id FROM cart_items WHERE cart_id = ? AND product_id = ?");
                $stmtCheckCart->execute([$cartId, $productId]);
                $existingCartItem = $stmtCheckCart->fetch();

                if ($existingCartItem) {
                    // Product already in the cart, do not add again (you can redirect with a message if needed)
                    header("Location: ./cart.php?error=Product%20already%20in%20the%20cart");
                    exit();
                }

                // Product not in the cart, proceed with adding it
                $stmtItems = $connection->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmtItems->execute([$cartId, $productId, $quantity, $productInfo['price']]);
            }

            // Check if the insertion or update was successful
            if ($stmtItems->rowCount() > 0 || $stmtUpdateCart->rowCount() > 0) {
                header("Location: ../cart.php");
            } else {
                // If no rows were affected, there might be an issue with the insertion or update
                header("Location: ./cart.php?error=Error%20updating%20or%20adding%20product%20to%20cart");
                exit();
            }
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
