<?php
// Assuming the request method is POST and the required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itemId"]) && isset($_POST["newQuantity"]) && isset($_POST["cartId"])) {
    try {
        // Retrieve item ID, new quantity, and cart ID from the POST request
        $itemId = $_POST["itemId"];
        $newQuantity = $_POST["newQuantity"];
        $cartId = $_POST["cartId"];

        // Connection to the database
        require_once '../auth/connection/config.php';

        // Check if the item exists in the cart
        $stmtCheckItem = $connection->prepare("SELECT product_id, quantity, price FROM cart_items WHERE item_id = ? AND cart_id = ?");
        $stmtCheckItem->execute([$itemId, $cartId]);

        if ($stmtCheckItem->rowCount() > 0) {
            // Item exists, proceed with the update
            $row = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);
            $productId = $row['product_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];

            // Perform the update query on cart_items table
            $sqlUpdateCartItem = "UPDATE cart_items SET quantity = ?, subtotal = ?, total = ? WHERE item_id = ? AND cart_id = ?";
            $stmtUpdateCartItem = $connection->prepare($sqlUpdateCartItem);
            $stmtUpdateCartItem->execute([$newQuantity, $newQuantity * $price, ($newQuantity * $price) + 4.99, $itemId, $cartId]);

            // After updating the item quantity, fetch all items in the cart
            $stmtFetchCartItems = $connection->prepare("SELECT quantity, subtotal, total FROM cart_items WHERE cart_id = ? ORDER BY item_id LIMIT 1");
            $stmtFetchCartItems->execute([$cartId]);
            $cartItem = $stmtFetchCartItems->fetch(PDO::FETCH_ASSOC);

            // Return success response along with the updated item's details
            echo json_encode(['success' => true, 'cartItem' => $cartItem]);
        } else {
            // Item does not exist in the cart
            echo json_encode(['success' => false, 'message' => 'Item does not exist in the cart.']);
        }
    } catch (PDOException $e) {
        // Log the error
        error_log('PDOException: ' . $e->getMessage());

        // Send error response
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please check the error log.']);
    }
} else {
    // Return error response if an invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
