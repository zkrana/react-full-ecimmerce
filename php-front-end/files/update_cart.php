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
        $stmtCheckItem = $connection->prepare("SELECT ci.quantity, p.price FROM cart_items ci INNER JOIN products p ON ci.product_id = p.id WHERE ci.item_id = ? AND ci.cart_id = ?");
        $stmtCheckItem->execute([$itemId, $cartId]);

        if ($stmtCheckItem->rowCount() > 0) {
            // Item exists, proceed with the update
            $row = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);
            $quantity = $row['quantity'];
            $price = $row['price'];

            // Perform the update query on cart_items table
            $stmtUpdateCartItem = $connection->prepare("UPDATE cart_items SET quantity = ?, subtotal = ? WHERE item_id = ? AND cart_id = ?");
            $stmtUpdateCartItem->execute([$newQuantity, $newQuantity * $price, $itemId, $cartId]);

            // Check if the update was successful
            if ($stmtUpdateCartItem->rowCount() > 0) {
                // Recalculate the subtotal and total
                $stmtRecalculate = $connection->prepare("UPDATE cart SET subtotal = (SELECT SUM(subtotal) FROM cart_items WHERE cart_id = ?), total = (SELECT SUM(subtotal) FROM cart_items WHERE cart_id = ?) WHERE cart_id = ?");
                $stmtRecalculate->execute([$cartId, $cartId, $cartId]);

                // Return success response
                echo json_encode(['success' => true]);
            } else {
                // Return error response with SQL error details
                $errorInfo = $stmtUpdateCartItem->errorInfo();
                echo json_encode(['success' => false, 'message' => 'Update failed. SQL Error: ' . $errorInfo[2]]);
            }
        } else {
            // Item does not exist in the cart
            echo json_encode(['success' => false, 'message' => 'Item does not exist in the cart.']);
        }
    } catch (PDOException $e) {
        // Send error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Return error response if invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
