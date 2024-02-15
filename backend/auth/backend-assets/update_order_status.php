<?php
require_once '../db-connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $newOrderStatus = isset($_POST['new_order_status']) ? $_POST['new_order_status'] : '';

    if (!empty($userId) && !empty($newOrderStatus)) {
        // Update the order status in the 'orders' table
        $updateSql = "UPDATE orders SET order_status_id = :newOrderStatus WHERE user_id = :userId";
        $updateQuery = $connection->prepare($updateSql);
        $updateQuery->bindParam(':newOrderStatus', $newOrderStatus, PDO::PARAM_INT);
        $updateQuery->bindParam(':userId', $userId, PDO::PARAM_INT);
        $updateQuery->execute();

        // You can add additional logic or error handling here if needed
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
