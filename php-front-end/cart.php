<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
</head>
<body>
    <?php include './components/header/header.php'; ?>

    <div class="min-h-screen bg-gray-100 pt-20 pb-14">
        <h1 class="mb-10 text-center text-2xl font-bold">Cart Items</h1>
        <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">


            <?php
            // Fetch cart items from the database
            $stmt = $connection->prepare("SELECT cart_items.*, products.name AS product_name, products.description AS description, products.product_photo FROM cart_items INNER JOIN products ON cart_items.product_id = products.id");
            $stmt->execute();
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalPrice = 0; // Initialize total price
            ?>
            <div class="rounded-lg md:w-2/3" id="cartItemsContainer">
                <!-- Loop through cart items and display them -->
                <?php foreach ($cartItems as $item): ?>
                    <?php 
                        // Calculate item subtotal
                        $subtotal = $item['price'] * $item['quantity'];
                        // Add subtotal to total price
                        $totalPrice += $subtotal;
                    ?>
                    <div class="cart-item justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start" data-item-id="<?php echo $item['item_id']; ?>">
                        <img src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $item['product_photo']; ?>" alt="<?php echo $item['product_name']; ?>" class="w-full rounded-lg sm:w-40">
                        <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                            <div class="mt-5 sm:mt-0">
                                <h2 class="text-lg font-bold text-gray-900"><?php echo $item['product_name']; ?></h2>
                                <p class="mt-1 text-xs text-gray-700"><?php echo $item['description']; ?></p>
                            </div>
                            <div class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
                                <div class="flex items-center border-gray-100">
                                    <span class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50"> - </span>
                                    <input class="quantity-input h-8 w-8 border bg-white text-center text-xs outline-none" type="number" value="<?php echo $item['quantity']; ?>" min="1" />
                                    <span class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50"> + </span>
                                </div>

                                <div class="flex items-center space-x-4">
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
                    <!-- Modal for cart item removed successfully -->
                    <div id="cartRemovedModal" class="hidden z-10 inset-0 overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pb-8">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                                            Item Removed
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                The item has been successfully removed from your cart.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <a href="index.php" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sub total -->
            <div class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
                <div class="mb-2 flex justify-between">
                    <p class="text-gray-700">Subtotal</p>
                    <p id="subtotalPrice" class="text-gray-700">$<?php echo number_format($totalPrice, 2); ?></p>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-700">Shipping</p>
                    <p class="text-gray-700">$4.99</p>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between">
                    <p class="text-lg font-bold">Total</p>
                    <div class="">
                        <?php 
                            // Calculate total price including shipping
                            $totalPrice += 4.99; // Add shipping cost
                        ?>
                        <p id="totalPrice" class="mb-1 text-lg font-bold">$<?php echo number_format($totalPrice, 2); ?> USD</p>
                        <p class="text-sm text-gray-700">including VAT</p>
                    </div>
                </div>
                <button class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600">Check out</button>
            </div>
        </div>

        
    </div>

    <?php include './components/footer/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to remove cart item
    function removeCartItem(itemId) {
        // Send AJAX request to remove cart item
        $.ajax({
            url: 'files/removeCartItem.php',
            type: 'POST',
            data: { itemId: itemId },
            success: function(response) {
                // Parse the JSON response
                var data = JSON.parse(response);
                
                // If removal is successful, remove the cart item from the UI
                if (data.success) {
                    $('.cart-item[data-item-id="' + itemId + '"]').remove();
                    // Show success modal
                    showCartRemovedModal();
                } else {
                    // Display error message if removal was not successful
                    alert('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Event listener for clicking the remove button
    $(document).ready(function() {
        $(document).on('click', '#removeCart', function() {
            // Get the item ID of the cart item to remove
            var itemId = $(this).closest('.cart-item').data('item-id');
            
            // Call the removeCartItem function
            removeCartItem(itemId);
        });
    });

    // Function to close the modal
    function closeCartRemovedModal() {
        document.getElementById('cartRemovedModal').classList.add('hidden');
    }

    // Function to show the modal
    function showCartRemovedModal() {
        document.getElementById('cartRemovedModal').classList.remove('hidden');
    }
    $(document).on('click', '.cursor-pointer.rounded-r', function() {
    // Increment the quantity
    var input = $(this).siblings('input.quantity-input');
    var currentValue = parseInt(input.val());
    if (!isNaN(currentValue)) {
        input.val(currentValue + 1);
        input.trigger('change'); // Trigger change event to update price
    }
});

$(document).on('click', '.cursor-pointer.rounded-l', function() {
    // Decrement the quantity
    var input = $(this).siblings('input.quantity-input');
    var currentValue = parseInt(input.val());
    if (!isNaN(currentValue) && currentValue > 1) {
        input.val(currentValue - 1);
        input.trigger('change'); // Trigger change event to update price
    }
});

// Function to update subtotal and total price
function updatePrice(itemId, newQuantity) {
    // Find the cart item with the given itemId
    var cartItem = $('.cart-item[data-item-id="' + itemId + '"]');
    
    // Get the price and calculate the new subtotal
    var price = parseFloat(cartItem.find('.text-sm').text().replace('$', '')); // Adjust this selector as per your HTML structure
    var subtotal = price * newQuantity;
    
    // Update the subtotal in the UI
    cartItem.find('.text-sm').text('$' + subtotal.toFixed(2)); // Adjust this selector as per your HTML structure
    
    // Recalculate the subtotal of all items
    var newSubtotal = 0;
    $('.text-sm').each(function() { // Adjust this selector as per your HTML structure
        var subtotalValue = parseFloat($(this).text().replace('$', ''));
        if (!isNaN(subtotalValue)) {
            newSubtotal += subtotalValue;
        }
    });

    // Update the subtotal price in the UI
    $('#subtotalPrice').text('$' + newSubtotal.toFixed(2));
    
    // Calculate the total price including shipping
    var shippingCost = 4.99;
    var total = newSubtotal + shippingCost;
    
    // Update the total price in the UI
    $('#totalPrice').text('$' + total.toFixed(2) + ' USD');
}



// Event listener for changing quantity
$(document).ready(function() {
    $(document).on('change', '.quantity-input', function() {
        // Get the new quantity
        var newQuantity = parseInt($(this).val());
        if (isNaN(newQuantity) || newQuantity < 1) {
            newQuantity = 1;
            $(this).val(1);
        }
        
        // Get the item ID
        var itemId = $(this).closest('.cart-item').data('item-id');
        
        // Update the price
        updatePrice(itemId, newQuantity);
    });
});


</script>


</body>
</html>
