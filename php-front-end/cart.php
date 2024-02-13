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
    <div class="w-[90%] sm:max-w-7xl mx-auto pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Cart.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <span class="text-gray-600">Cart</span>
            </div>
        </div>
        <div class="bg-gray-100 pt-20 pb-14 mt-7">
            <h1 class="mb-10 text-center text-2xl font-bold">Cart Items</h1>
            <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
    <?php
    $stmt = $connection->prepare("SELECT cart_items.*, 
            products.name AS product_name, 
            products.description AS description, 
            products.currency_code AS currency_code, 
            products.product_photo 
    FROM cart_items 
    INNER JOIN products 
    ON cart_items.product_id = products.id 
    WHERE cart_items.cart_id IN 
            (SELECT cart_id FROM cart WHERE ip_address = :ip_address)");

    $stmt->bindParam(':ip_address', $userIP); // Assuming you have $userIP defined somewhere
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPrice = 0; // Initialize total price
    $totalQuantity = 0; // Initialize total quantity
    ?>
                <div class="rounded-lg md:w-2/3" id="cartItemsContainer">
                    <!-- Loop through cart items and display them -->
                    <?php foreach ($cartItems as $item): ?>
                        <?php 
                            // Calculate item subtotal
                            $subtotal = $item['price'] * $item['quantity'];
                            // Add subtotal to total price
                            $totalPrice += $subtotal;

                            // Add quantity to total quantity
    $totalQuantity += $item['quantity'];
                        ?>
                        <div class="cart-item justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start" data-item-id="<?php echo $item['item_id']; ?>">
                            <img src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $item['product_photo']; ?>" alt="<?php echo $item['product_name']; ?>" class="w-full rounded-lg sm:w-40">
                            <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                                <div class="mt-5 sm:mt-0">
                                    <h2 class="text-lg font-bold text-gray-900"><?php echo $item['product_name']; ?></h2>
                                    <p class="mt-1 text-xs text-gray-700"><?php echo $item['description']; ?></p>
                                </div>
                                <div class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
                                    <div class="flex items-center space-x-4">
                                        <p><?php echo $item['currency_code'] ?></p>
                                        <p class="hidden original-price"><?php echo $item['price']; ?></p>
                                        <p class="text-sm"><?php echo $item['price']; ?></p>
                                        <div id="removeCart">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 cursor-pointer duration-150 hover:text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <!-- Subtotal and Total -->
    <!-- Subtotal and Total -->
    <div class="mt-6 h-full md:mt-0 md:w-1/3">
        <div class="rounded-lg border bg-white p-6 shadow-md ">
            <div class="mb-2 flex justify-between">
                <p class="text-gray-700">Subtotal</p>
                <p id="subtotalPrice" class="text-gray-700">$<?php echo number_format($totalPrice, 2); ?></p>
            </div>
            <div class="flex justify-between">
                <p class="text-gray-700">Total Quantity:</p>
                <p class="text-gray-700"><?php echo $totalQuantity; ?></p>
            </div>
            <hr class="my-4" />
            <div class="flex justify-between">
                <p class="text-lg font-bold">Total</p>
                <div class="">
                    <p id="totalPrice" class="mb-1 text-lg font-bold">$<?php echo number_format($totalPrice, 2); ?> USD</p>
                </div>
            </div>
            <button class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600" onclick="checkout()" <?php echo empty($cartItems) ? 'disabled' : ''; ?>>Check out</button>
        </div>
        <div class="py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-center w-full mt-5">
            <a href="index.php" type="button" class="w-full text-center block rounded-md  px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:text-sm">
                Continue Shopping
            </a>
        </div>
    </div>
            </div>
        </div>
    </div>


    <?php include './components/footer/footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./assets/js/cart.js"></script>
    
</body>
</html>