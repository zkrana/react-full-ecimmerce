<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>

    <?php
// Fetch cart items from the database based on user ID
$stmt = $connection->prepare("SELECT cart_items.*, 
    products.name AS product_name, 
    products.description AS description, 
    products.currency_code AS currency_code, 
    products.product_photo 
FROM cart_items 
INNER JOIN products ON cart_items.product_id = products.id 
WHERE cart_items.cart_id IN (SELECT cart_id FROM cart WHERE customer_id = :customer_id)");

$stmt->bindParam(':customer_id', $userID); 
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    // Redirect to cart.php if the cart is empty
    header('Location: cart.php');
    exit();
}
?>
    <div class="container">
        <div class="min-h-screen w-full sm:max-w-7xl mx-auto pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Checkout.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <a href="cart.php" class="focus:outline-none hover:underline text-gray-600">Cart</a> / <span class="text-gray-600">Checkout</span>
            </div>
        </div>
        <form id="checkoutForm" action="files/checkout_process.php" method="post" class="w-full bg-white border-t border-b border-gray-200 px-5 py-10 text-gray-800">
            <div class="w-full">
                <div class="-mx-3 md:flex items-start">
                    <div class="px-3 md:w-7/12 lg:pr-10">
                        <div class="w-full mx-auto text-gray-800 font-light mb-6 border-b border-gray-200 pb-6">
                            <?php
                            // Fetch cart items from the database based on user IP
                            $stmt = $connection->prepare("SELECT cart_items.*, 
                                products.name AS product_name, 
                                products.description AS description, 
                                products.currency_code AS currency_code, 
                                products.product_photo 
                            FROM cart_items 
                            INNER JOIN products ON cart_items.product_id = products.id 
                            WHERE cart_items.cart_id IN (SELECT cart_id FROM cart WHERE customer_id = :customer_id)");

                           $stmt->bindParam(':customer_id', $userID); 
                            $stmt->execute();
                            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Loop through cart items and display them
                            foreach ($cartItems as $item) {
                                ?>
                                <div data-item-id="<?php echo $item['item_id']; ?>" class="w-full mx-auto text-gray-800 font-light mb-6 border-b border-gray-200 pb-6">
                                    <div class="w-full flex items-center">
                                        <div class="overflow-hidden rounded-lg w-16 h-16 bg-gray-50 border border-gray-200">
                                            <img class="w-16 h-16 object-contain" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $item['product_photo']; ?>" alt="<?php echo $item['product_name']; ?>">
                                        </div>
                                        <div class="flex-grow pl-3">
                                            <h6 class="font-semibold uppercase text-gray-600"><?php echo $item['product_name']; ?></h6>
                                            <p class="text-gray-400 flex gap-2 items-center">
                                                <button class="decrement w-5 h-5 flex justify-center items-center bg-slate-200 rounded-sm" data-item-id="<?php echo $item['item_id']; ?>">-</button>

                                                <span class="quantity w-5 h-5 flex items-center justify-center border border-slate-200" 
                                                    data-item-id="<?php echo $item['item_id']; ?>"
                                                    data-unit-price="<?php echo $item['price']; ?>">
                                                    <?php echo $item['quantity']; ?>
                                                </span>

                                                <button class="increment w-5 h-5 flex justify-center items-center bg-slate-200 rounded-sm" data-item-id="<?php echo $item['item_id']; ?>">+</button>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-600 text-xl price" data-item-id="<?php echo $item['item_id']; ?>"><?php echo $item['currency_code'] . ' ' . number_format($item['price'] * $item['quantity'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>

                        <?php
                        // Calculate Subtotal and Total
                        $subTotal = 0;
                        foreach ($cartItems as $item) {
                            // Assuming each item has a 'price' and 'quantity' field
                            $subTotal += $item['price'] * $item['quantity'];
                        }

                        // Assuming you have a VAT rate and discount value (replace with your logic)
                        $vatRate = 0.1; // 10% VAT
                        $discount = 20; // $20 discount

                        // Calculate VAT and Discount
                        $vat = $subTotal * $vatRate;
                        $totalDiscount = $discount;
                        $grandTotal = $subTotal + $vat - $totalDiscount;
                        ?>
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <div class="-mx-2 flex items-end justify-end">
                                <div class="flex-grow px-2 lg:max-w-xs">
                                    <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Discount code</label>
                                    <div>
                                        <input class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="XXXXXX" type="text"/>
                                    </div>
                                </div>
                                <div class="px-2">
                                    <button class="block w-full max-w-xs mx-auto border border-transparent bg-gray-400 hover:bg-gray-500 focus:bg-gray-500 text-white rounded-md px-5 py-2 font-semibold">APPLY</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 pb-6 border-b border-gray-200 text-gray-800">
                            <div class="w-full flex mb-3 items-center subtotal-container">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Subtotal</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold"> <span class="subtotal"><?php echo $item['currency_code'] . number_format($subTotal, 2) . '</span>'; ?></span></span>
                                </div>
                            </div>
                            

                            <div class="w-full flex items-center">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Vat</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold"><?php echo $item['currency_code'] . ' ' . number_format($vat, 2); ?></span>
                                </div>
                            </div>

                            <div class="w-full flex items-center mt-2">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Discount</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold"><?php echo $item['currency_code'] . ' ' . number_format($totalDiscount, 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 pb-6 border-b border-gray-200 md:border-none text-gray-800 text-xl">
                        <div class="w-full flex items-center total-container">
                            <div class="flex-grow">
                                <span class="text-gray-600">Total</span>
                            </div>
                            <div class="pl-3">
                                <span class="font-semibold text-gray-400 text-sm"><?php echo $item['currency_code']; ?></span>
                                <span class="font-semibold total"><?php echo number_format($grandTotal, 2); ?></span>
                            </div>
                        </div>
                        </div>
                        
                    </div>
                    <div class="px-3 md:w-5/12">
                        <div class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-3 text-gray-800 font-light mb-6">
                            <h2 class="text-2xl font-semibold mb-6">Shipping Details</h2>
                            <div id="error-message" class="text-red-500 text-sm mb-4"></div>
                            <div>
                                <!-- First Name -->
                                <div class="mb-4">
                                    <label for="first_name" class="block text-sm font-medium text-gray-600">First Name</label>
                                    <input type="text" id="first_name" name="billing_details[first_name]" class="mt-1 p-2 w-full border rounded-md">
                                </div>

                                <!-- Last Name -->
                                <div class="mb-4">
                                    <label for="last_name" class="block text-sm font-medium text-gray-600">Last Name</label>
                                    <input type="text" id="last_name" name="billing_details[last_name]" class="mt-1 p-2 w-full border rounded-md">
                                </div>

                                <!-- Billing Address -->
                                <div class="mb-4">
                                    <label for="billing_address" class="block text-sm font-medium text-gray-600">Billing Address</label>
                                    <textarea id="billing_address" name="billing_details[billing_address]" class="mt-1 p-2 w-full border rounded-md"></textarea>
                                </div>

                                <!-- City -->
                                <div class="mb-4">
                                    <label for="city" class="block text-sm font-medium text-gray-600">City</label>
                                    <input type="text" id="city" name="billing_details[city]" class="mt-1 p-2 w-full border rounded-md">
                                </div>

                                <!-- State -->
                                <div class="mb-4">
                                    <label for="state" class="block text-sm font-medium text-gray-600">State</label>
                                    <input type="text" id="state" name="billing_details[state]" class="mt-1 p-2 w-full border rounded-md">
                                </div>

                                <!-- Postal Code -->
                                <div class="mb-4">
                                    <label for="postal_code" class="block text-sm font-medium text-gray-600">Postal Code</label>
                                    <input type="text" id="postal_code" name="billing_details[postal_code]" class="mt-1 p-2 w-full border rounded-md">
                                </div>

                                <!-- Country -->
                                <div class="mb-4">
                                    <label for="country" class="block text-sm font-medium text-gray-600">Country</label>
                                    <select id="country" name="billing_details[country]" class="mt-1 p-2 w-full border rounded-md">
                                        <option value="Bangladesh" selected>Bangladesh</option>
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="UK">UK</option>
                                    </select>
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
                                    <input type="tel" id="phone_number" name="billing_details[phone_number]" class="mt-1 p-2 w-full border rounded-md">
                                </div>
                            </div>
                        </div>
                        <div class="w-full mx-auto rounded-lg bg-white border border-gray-200 text-gray-800 font-light mb-6">
                            <!-- <div class="w-full p-3 border-b border-gray-200">
                                <div class="mb-5">
                                    <label for="type1" class="flex items-center cursor-pointer">
                                        <input type="radio" class="form-radio h-5 w-5 text-indigo-500" name="type" id="type1" checked>
                                        <img src="https://leadershipmemphis.org/wp-content/uploads/2020/08/780370.png" class="h-6 ml-3">
                                    </label>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Name on card</label>
                                        <div>
                                            <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="John Smith" type="text"/>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Card number</label>
                                        <div>
                                            <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="0000 0000 0000 0000" type="text"/>
                                        </div>
                                    </div>
                                    <div class="mb-3 -mx-2 flex items-end">
                                        <div class="px-2 w-1/4">
                                            <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Expiration date</label>
                                            <div>
                                                <select class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                                                    <option value="01">01 - January</option>
                                                    <option value="02">02 - February</option>
                                                    <option value="03">03 - March</option>
                                                    <option value="04">04 - April</option>
                                                    <option value="05">05 - May</option>
                                                    <option value="06">06 - June</option>
                                                    <option value="07">07 - July</option>
                                                    <option value="08">08 - August</option>
                                                    <option value="09">09 - September</option>
                                                    <option value="10">10 - October</option>
                                                    <option value="11">11 - November</option>
                                                    <option value="12">12 - December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="px-2 w-1/4">
                                            <select class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                            </select>
                                        </div>
                                        <div class="px-2 w-1/4">
                                            <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Security code</label>
                                            <div>
                                                <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="000" type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="w-full p-3">
                                <label for="paymentMethod" class="block text-2xl font-semibold mb-6 text-gray-700">Payment Method</label>
                                <div class="mt-1">
                                    <select id="paymentMethod" name="paymentMethod" class="form-select shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="bkash">bKash</option>
                                    </select>
                                </div>
                                <span class="text-sm text-gray-500">Please provide total amount from your bKash account and then enter your transaction code below.</span>
                            </div>

                            <div class="w-full p-3">
                                <label for="transactionCode" class="block text-sm font-medium text-gray-700">Transaction Code</label>
                                <input type="text" id="transactionCode" name="transactionCode" class="h-11 form-input shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-mdmt-1 p-2 border rounded-md">
                            </div>

                        </div>
                        <div>
                            <button type="submit" class="block w-full max-w-xs mx-auto bg-indigo-500 hover:bg-indigo-700 focus:bg-indigo-700 text-white rounded-lg px-3 py-2 font-semibold"><i class="mdi mdi-lock-outline mr-1"></i> PAY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
     </div>
    </div>

    <?php include './components/footer/footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".increment").on("click", function () {
                updateQuantity($(this).data("item-id"), 1);
            });

            $(".decrement").on("click", function () {
                updateQuantity($(this).data("item-id"), -1);
            });

            function updateQuantity(itemId, change) {
                var quantityElement = $(".quantity[data-item-id=" + itemId + "]");
                var priceElement = $(".price[data-item-id=" + itemId + "]");
                var unitPrice = parseFloat(quantityElement.data("unit-price"));

                if (isNaN(unitPrice)) {
                    console.error("Invalid unit price");
                    return;
                }

                var currentQuantity = parseInt(quantityElement.text());
                var newQuantity = currentQuantity + change;

                if (newQuantity < 1) {
                    return; // Prevent negative quantities
                }

                // Update quantity on the page
                quantityElement.text(newQuantity);

                // Calculate and update price on the page
                var newPrice = newQuantity * unitPrice;
                priceElement.text("$" + newPrice.toFixed(2));
            }
        });

            // Check if there is an error message parameter in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get('message');

        // Display the error message if it exists
        if (errorMessage) {
            const errorMessageElement = document.getElementById('error-message');
            errorMessageElement.textContent = errorMessage;
            // Set a timeout to hide the error message after 4 seconds
            setTimeout(() => {
                errorMessageElement.textContent = '';
            }, 4000);
        }
    </script>


</body>
</html>