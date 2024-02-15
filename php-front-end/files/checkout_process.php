<?php
// Include your database configuration file
require_once '../auth/connection/config.php';

// Start the session
session_start();

try {
    // Start a database transaction
    $connection->beginTransaction();

    // Retrieve the cart items
    $cartItemsQuery = $connection->prepare("SELECT * FROM `cart_items`");
    $cartItemsQuery->execute();
    $cartItems = $cartItemsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Get customerId (you may need to adapt this to your logic)
    $customerId = $_SESSION['userId']; // Assuming customerId is stored in the session

    // Get billing details from the POST request
    $billingDetails = $_POST['billing_details'];

    // Check if billing details are already filled for the customer
    $checkBillingDetails = $connection->prepare("SELECT id FROM customers WHERE id = ?");
    $checkBillingDetails->execute([$customerId]);
    $billingDetailsExist = $checkBillingDetails->rowCount() > 0;

    // If billing details are not already filled, update them
    if (!$billingDetailsExist) {
        $updateBillingDetails = $connection->prepare("UPDATE customers SET first_name = ?, last_name = ?, billing_address = ?, city = ?, state = ?, postal_code = ?, country = ?, phone_number = ? WHERE id = ?");
        $updateBillingDetails->execute([
            $billingDetails['first_name'],
            $billingDetails['last_name'],
            $billingDetails['billing_address'],
            $billingDetails['city'],
            $billingDetails['state'],
            $billingDetails['postal_code'],
            $billingDetails['country'],
            $billingDetails['phone_number'],
            $customerId
        ]);
    }

    // Calculate totals
    $subTotal = $vat = $totalDiscount = $grandTotal = 0;
    foreach ($cartItems as $item) {
        $subTotal += $item['quantity'] * $item['price'];
    }

    // Assuming you have a VAT rate and discount value (replace with your logic)
    $vatRate = 0.1; // 10% VAT
    $discount = 20; // $20 discount

    $vat = $subTotal * $vatRate;
    $totalDiscount = $discount;
    $grandTotal = $subTotal + $vat - $totalDiscount;

    // Insert into orders table
    $insertOrder = $connection->prepare("INSERT INTO `orders` (user_id, quantity, total_price, order_date, order_status_id) VALUES (?, ?, ?, NOW(), 1)");
    $insertOrder->execute([$customerId, count($cartItems), $grandTotal]);

    // Get the order ID
    $orderId = $connection->lastInsertId();

    // Iterate through cart items and insert into order_items
    foreach ($cartItems as $item) {
        $subtotal = $item['quantity'] * $item['price'];

        // Insert into order_items table
        $insertOrderItem = $connection->prepare("INSERT INTO `order_items` (order_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $insertOrderItem->execute([$orderId, $item['product_id'], $item['quantity'], $subtotal]);
    }

    // Process payment
    $paymentMethod = $_POST['paymentMethod'];
    $transactionCode = $_POST['transactionCode'];

    $insertPayment = $connection->prepare("INSERT INTO `payments` (order_id, payment_amount, payment_date, payment_method, status, user_id) VALUES (?, ?, NOW(), ?, 'Pending', ?)");
    $insertPayment->execute([$orderId, $grandTotal, $paymentMethod, $customerId]);

    if ($insertPayment->rowCount() === 0) {
        // Handle the case where the payment insertion was not successful
        // Redirect or log an error message
        header("Location: ../checkout.php?error=Payment insertion failed");
        exit();
    }

    // Delete cart items associated with the user ID
    $deleteCartItems = $connection->prepare("DELETE FROM `cart_items` WHERE cart_id IN (SELECT cart_id FROM `cart` WHERE customer_id = ?)");
    $deleteCartItems->execute([$customerId]);

    // Delete the cart
    $deleteCart = $connection->prepare("DELETE FROM `cart` WHERE customer_id = ?");
    $deleteCart->execute([$customerId]);

    // Commit the transaction
    $connection->commit();

    // Redirect to the order confirmation page with order information
    header('Location: ../orderConfirmed.php?order_id=' . $orderId);
    exit();
} catch (PDOException $e) {
    // Rollback the transaction in case of an error
    $connection->rollBack();

    // Handle the exception, e.g., log the error or redirect with an error message
    header("Location: ../checkout.php?error=" . urlencode($e->getMessage()));
    exit();
} finally {
    // Close the database connection
    $connection = null;
}
?>
