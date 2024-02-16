<?php
// Include your database configuration file
require_once 'auth/connection/config.php';

// Include header (you can create a header.php file)
include './components/header/header.php';

// Initialize variables
$orderDetails = null;
$error = null;

// Check if the form is submitted
if (isset($_POST['trackOrder'])) {
    // Retrieve order details from the provided order ID
    $inputOrderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

    if ($inputOrderId !== false && $inputOrderId > 0) {
        // Retrieve order details and customer information from the database based on order ID
        $orderQuery = $connection->prepare("SELECT orders.id AS order_id, orders.order_date, orders.order_status_id, 
                                                 order_status.status_name, COUNT(order_items.product_id) AS total_products, 
                                                 customers.first_name, customers.last_name
                                          FROM `orders`
                                          JOIN `order_status` ON orders.order_status_id = order_status.id
                                          LEFT JOIN `order_items` ON orders.id = order_items.order_id
                                          LEFT JOIN `customers` ON orders.user_id = customers.id
                                          WHERE orders.id = ?
                                          GROUP BY orders.id");
        $result = $orderQuery->execute([$inputOrderId]);
        if ($result) {
            $orderDetails = $orderQuery->fetchAll(PDO::FETCH_ASSOC);

            if (!$orderDetails) {
                $error = "No orders found for the provided Order ID.";
            }
        } else {
            $error = "Error executing the query: " . print_r($orderQuery->errorInfo(), true);
        }
    } else {
        $error = "Invalid Order ID. Please enter a valid numeric value.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <div class="w-[90%] sm:max-w-7xl mx-auto pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Track Order</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <a href="cart.php" class="focus:outline-none hover:underline text-gray-500">Cart</a> / <a href="checkout.php" class="focus:outline-none hover:underline text-gray-500">Checkout</a> / <span class="text-gray-600">Track Order</span>
            </div>
        </div>
        <div class="max-w-md mx-auto mt-10">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="order_id" class="block text-sm font-medium text-gray-700">Enter Order ID:</label>
            <input type="text" name="order_id" id="order_id" class="w-full mt-1 p-2 border rounded-md">
            <button type="submit" name="trackOrder" value="trackOrder" class="mt-4 w-full text-center block rounded-md px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                Track Order
            </button>
        </form>


 <?php
        // Display order details or error message
        if ($orderDetails) {
            foreach ($orderDetails as $order) {
                ?>
                <h1 class="text-3xl font-bold mb-4 text-green-700">Order Details</h1>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                <p><strong>Total Products:</strong> <?php echo htmlspecialchars($order['total_products']); ?></p>
                <p><strong>Order Status:</strong> <button class="text-[tomato]"><?php echo htmlspecialchars($order['status_name']); ?></button></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>

                <div class="mt-6">
                    <a href="index.php" type="button"
                        class="w-full text-center block rounded-md  px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Continue Shopping
                    </a>
                </div>
            <?php
            }
        } elseif ($error) {
            echo "<p class='text-red-500'>$error</p>";
        }
        ?>
        </div>
    </div>

    <?php
    // Include footer (you can create a footer.php file)
    include './components/footer/footer.php';
    ?>
</body>
</html>
