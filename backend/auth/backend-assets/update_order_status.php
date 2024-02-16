<?php
require_once '../db-connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : '';
    $newOrderStatus = isset($_POST['new_order_status']) ? $_POST['new_order_status'] : '';

    if (!empty($orderId) && !empty($newOrderStatus)) {
        // Update the order status in the 'orders' table
        $updateOrderSql = "UPDATE orders SET order_status_id = :newOrderStatus WHERE id = :orderId";
        $updateOrderQuery = $connection->prepare($updateOrderSql);
        $updateOrderQuery->bindParam(':newOrderStatus', $newOrderStatus, PDO::PARAM_INT);
        $updateOrderQuery->bindParam(':orderId', $orderId, PDO::PARAM_INT);

        // Update the payment status in the 'payments' table
        $paymentStatus = ($newOrderStatus == 1) ? 'Pending' : 'Paid';
        $updatePaymentSql = "UPDATE payments SET status = :paymentStatus WHERE order_id = :orderId";
        $updatePaymentQuery = $connection->prepare($updatePaymentSql);
        $updatePaymentQuery->bindParam(':paymentStatus', $paymentStatus, PDO::PARAM_STR);
        $updatePaymentQuery->bindParam(':orderId', $orderId, PDO::PARAM_INT);

        try {
            $connection->beginTransaction();

            $updateOrderQuery->execute();
            $updatePaymentQuery->execute();

            $connection->commit();

            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            $connection->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
