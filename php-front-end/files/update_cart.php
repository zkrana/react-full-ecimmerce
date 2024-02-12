<?php
// Assuming the request method is POST and the required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itemId"]) && isset($_POST["newQuantity"]) && isset($_POST["cartId"])) {
    try {
        // Retrieve item ID, new quantity, and cart ID from the POST request
        $itemId = $_POST["itemId"];
        $newQuantity = $_POST["newQuantity"];
        $cartId = $_POST["cartId"];

        // Define the maximum allowed quantity
        $maxAllowedQuantity = 5;

        // Connection to the database
        require_once '../auth/connection/config.php';

        // Define the shipping cost
        $shippingCost = 4.99;

        // Check if the new quantity exceeds the maximum allowed quantity
        if ($newQuantity > $maxAllowedQuantity) {
            echo json_encode(['success' => false, 'message' => 'You can add a maximum of ' . $maxAllowedQuantity . ' items to your cart. If you need to order more, please contact sales@ecommerce.com.']);
            exit(); // Stop further execution
        }

        // Check if the item exists in the cart
        $stmtCheckItem = $connection->prepare("SELECT ci.quantity, p.price FROM cart_items ci INNER JOIN products p ON ci.product_id = p.id WHERE ci.item_id = ? AND ci.cart_id = ?");
        $stmtCheckItem->execute([$itemId, $cartId]);

        if ($stmtCheckItem->rowCount() > 0) {
            // Item exists, proceed with the update
            $row = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);
            $quantity = $row['quantity'];
            $price = $row['price'];

            // Perform the update query on cart_items table
            $sqlUpdateCartItem = "UPDATE cart_items SET quantity = ?, subtotal = ?, total = ? WHERE item_id = ? AND cart_id = ?";
            $stmtUpdateCartItem = $connection->prepare($sqlUpdateCartItem);
            $stmtUpdateCartItem->execute([$newQuantity, $newQuantity * $price, ($newQuantity * $price) + $shippingCost, $itemId, $cartId]);

            // Debugging: Rows updated by the update statement
            echo "Rows updated: " . $stmtUpdateCartItem->rowCount() . "<br>";

            // Check if the update was successful
            if ($stmtUpdateCartItem->rowCount() > 0) {
                // Return success response
                echo json_encode(['success' => true]);
            } else {
                // Return error response with SQL error details
                $errorInfo = $stmtUpdateCartItem->errorInfo();
                error_log('Update failed. SQL Error: ' . $errorInfo[2]);
                echo json_encode(['success' => false, 'message' => 'Update failed. SQL Error: ' . $errorInfo[2]]);
            }
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
    // Return error response if invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
