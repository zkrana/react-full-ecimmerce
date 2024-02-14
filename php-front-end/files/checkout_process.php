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

    // Update billing details in the customers table
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
        $customerId // Move the customer ID to the last parameter
    ]);

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
    $insertOrder->execute([$customerId, count($cartItems), $grandTotal]); // Use count($cartItems) for quantity

    // Get the order ID
    $orderId = $connection->lastInsertId();

    // Iterate through cart items and insert into order_items
    foreach ($cartItems as $item) {
        $subtotal = $item['quantity'] * $item['price'];

        // Insert into order_items table
        $insertOrderItem = $connection->prepare("INSERT INTO `order_items` (order_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $insertOrderItem->execute([$orderId, $item['product_id'], $item['quantity'], $subtotal]);
    }

    // Update the orders table with the correct user_id
    $updateOrders = $connection->prepare("UPDATE `orders` SET user_id = ? WHERE id = ?");
    $updateOrders->execute([$customerId, $orderId]);

    // Commit the transaction
    $connection->commit();

    // Redirect or perform further actions
    header('Location: ../checkout.php');
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
