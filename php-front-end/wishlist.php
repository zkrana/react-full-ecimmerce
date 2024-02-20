<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <div class="w-[90%] sm:max-w-7xl mx-auto pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Wishlist.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="<?php echo $baseUrl; ?>" class="focus:outline-none hover:underline text-gray-500">Home</a> / <span class="text-gray-600">Wishlist</span>
            </div>
        </div>
        <div class="bg-gray-100 pt-20 pb-14 mt-7">

            <?php
            try {
                $connection->beginTransaction();

                // Fetch wishlist items from the database with product details
                $sql = "SELECT wi.*, p.name as product_name, p.product_photo, p.description, p.price, p.currency_code
                        FROM wishlist_items wi
                        JOIN products p ON wi.productId = p.id";
                
                $stmt = $connection->prepare($sql);
                $stmt->execute();

                // Fetch all wishlist items as an associative array
                $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $connection->commit();
            } catch (Exception $e) {
                // Handle exceptions (e.g., log, display an error message)
                $connection->rollBack();
                echo "Error fetching wishlist items: " . $e->getMessage() . "<br>";
                $wishlistItems = [];
            }
            ?>
            <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
                <?php if (!empty($wishlistItems)): ?>
                    <div class="rounded-lg md:w-2/3" id="wishlistItemsContainer">
                        <!-- Loop through wishlist items and display them -->
                        <?php foreach ($wishlistItems as $item): ?>
                            <?php
                            // Display wishlist item similarly to cart item
                            // You can customize the UI as needed
                            ?>
                            <div class="wishlist-item justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start" data-wishlist-id="<?php echo $item['wishlistItemId']; ?>">
                                <img src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $item['product_photo']; ?>"
                                    alt="<?php echo $item['product_name']; ?>"
                                    class="w-full rounded-lg sm:w-40">
                                <div class="sm:ml-4 sm:flex sm:flex-col sm:w-full sm:justify-between">
                                    <div class="mt-5 sm:mt-0">
                                        <h2 class="text-lg font-bold text-gray-900"><?php echo $item['product_name']; ?></h2>
                                        <p class="mt-1 text-xs text-gray-700"><?php echo $item['description']; ?></p>
                                    </div>
                                    <div class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
                                        <div class="flex items-center justify-between space-x-4">
                                            <div class="flex items-center gap-2">
                                                <p><?php echo $item['currency_code'] ?></p>
                                                <p class="hidden original-price"><?php echo $item['itemPrice']; ?></p>
                                                <p class="text-sm"><?php echo $item['itemPrice']; ?></p>
                                            </div>
                                            <div class="remove-wishlist" data-wishlist-id="<?php echo $item['wishlistItemId']; ?>" data-product-id="<?php echo $item['productId']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 cursor-pointer duration-150 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>

                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" class="add-to-cart-btn bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="<?php echo $item['productId']; ?>">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col gap-3">
                        <p>Your wishlist is empty. Add items to proceed.</p>
                        <a href="index.php" type="button"
                            class="w-full text-center mt-4 block rounded-md  px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                Continue Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php include './components/footer/footer.php'; ?>
<script src="./assets/js/wishlist.js"></script>

<script>
    $(document).ready(function() {
        // AJAX function to check if the user is logged in
        $(".add-to-cart-btn").on("click", function() {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "./files/checkLogin.php", // Replace with your actual backend endpoint for checking login status
                data: {productId: productId},
                success: function(response) {
                    if (response === "loggedIn") {
                        // If logged in, proceed with adding to cart
                        addToCart(productId);
                    } else {
                        // If not logged in, handle as needed (e.g., show login modal)
                        window.location.href = "./files/userlogin.php";
                    }
                },
                error: function() {
                    console.error("Error checking login status");
                }
            });
        });

        // Function to add product to cart and refresh the page
        function addToCart(productId) {
            $.ajax({
                type: "POST",
                url: "./files/addToCart.php",
                data: {productId: productId},
                success: function(response) {
                    // Handle success (e.g., update cart count)
                    // No need to show the popup, directly refresh the page
                    location.reload();
                },
                error: function() {
                    console.error("Error adding product to cart");
                }
            });
        }
    });
</script>

</body>
</html>