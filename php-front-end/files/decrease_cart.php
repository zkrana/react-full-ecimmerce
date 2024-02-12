<?php
// decrease_cart.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itemId"]) && isset($_POST["cartId"])) {
    try {
        $itemId = $_POST["itemId"];
        $cartId = $_POST["cartId"];

        require_once '../auth/connection/config.php';

        $stmtCheckItem = $connection->prepare("SELECT quantity, price FROM cart_items WHERE item_id = ? AND cart_id = ?");
        $stmtCheckItem->execute([$itemId, $cartId]);

        if ($stmtCheckItem->rowCount() > 0) {
            $row = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);
            $quantity = max(1, $row['quantity'] - 1); // Decrease quantity by 1
            $price = $row['price'];

            $stmtUpdateCartItem = $connection->prepare("UPDATE cart_items SET quantity = ?, subtotal = ? WHERE item_id = ? AND cart_id = ?");
            $stmtUpdateCartItem->execute([$quantity, $quantity * $price, $itemId, $cartId]);

            if ($stmtUpdateCartItem->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Item does not exist in the cart.']);
        }
    } catch (PDOException $e) {
        error_log('PDOException: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please check the error log.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
