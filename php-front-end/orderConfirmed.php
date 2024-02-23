<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <div class="w-[90%] sm:max-w-7xl mx-auto sm:pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Cart.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <a href="cart.php" class="focus:outline-none hover:underline text-gray-500">Cart</a> / <a href="checkout.php" class="focus:outline-none hover:underline text-gray-500">Checkout</a> / <span class="text-gray-600">Order Confirmed</span>
            </div>
        </div>
        <div class="max-w-md mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4 text-green-700">Thank You for Your Order!</h1>
        
        <?php
            // Retrieve order ID from the query parameters
            $orderId = $_GET['order_id'] ?? null;

            // Display order information
            if ($orderId) {
                echo '<p class="text-lg mb-2">Your order ID is: #' . $orderId . '</p>';
                echo '<p class="text-sm text-gray-600">We have received your order. You will receive a confirmation email shortly.</p>';
            } else {
                echo '<p class="text-red-500">Error: Order ID not found.</p>';
            }
        ?>

        <div class="mt-6">
            <a href="index.php" type="button"
                class="w-full text-center block rounded-md  px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Continue Shopping
            </a>
        </div>
    </div>


    <?php include './components/footer/footer.php'; ?>
</body>
</html>