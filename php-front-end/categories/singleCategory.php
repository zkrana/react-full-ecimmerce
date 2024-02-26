<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Category</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>

<body>
    <?php include '../components/header/header.php'; ?>
    <div class="container">
        <div class="sm:max-w-7xl w-[90%] mx-auto my-10">
            <div class="mt-10">
            <?php

            // Fetch categoryId from the URL parameter
            $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

            // Function to fetch category information based on categoryId
            function getCategoryInfo($categoryId, $connection) {
                $query = "SELECT * FROM `categories` WHERE `id` = :categoryId";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                $stmt->execute();

                // Check if the query was successful and if there is a result
                if ($stmt && $stmt->rowCount() > 0) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }

                return false; // Return false if no result is found
            }

            // Function to fetch products based on categoryId
            function getProductsByCategory($categoryId, $connection) {
                $query = "SELECT * FROM `products` WHERE `category_id` = :categoryId";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                $stmt->execute();

                // Check if the query was successful and if there is a result
                if ($stmt && $stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                return array(); // Return an empty array if no result is found
            }

            // Fetch category information
            $categoryInfo = getCategoryInfo($categoryId, $connection);

            // Check if category information is found
            if ($categoryInfo !== false) {
                // Your custom header code
                echo '<div class="pb-3 border-b border-slate-200">';
                echo '  <h2 class="text-lg font-semibold">' . $categoryInfo['name'] . '</h2>';
                echo '</div>';
                echo '<div class="mt-10">';

                // Fetch products based on categoryId
                $products = getProductsByCategory($categoryId, $connection);

                if (!empty($products)) {
                    // Display products in the body
                    echo '<div id="productContainer" class="flex flex-wrap gap-8 mt-10">';
                    foreach ($products as $product) {
                        echo '<div class="product-item xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-10px)] w-full group bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="' . $product['id'] . '">';
                        echo '  <div class="wishlist-icon hidden absolute right-3 top-3 w-6 h-6 flex justify-center items-center text-lg cursor-pointer hover:text-base hover:bg-slate-400 hover:text-white hover:rounded-full">';
                        echo '    <i class="fa-regular fa-heart"></i>';
                        echo '  </div>';
                        echo '<a href="../products/singleProduct.php?id=' . $product['id'] . '">';

                        echo '  <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-contain rounded-md mb-4" />';
                        echo '  <h2 class="text-xl font-semibold mb-2 h-28">' . $product['name'] . '</h2>';
                        echo '  <div class="flex flex-col gap-3">';
                        echo '    <div class="text-lg font-bold text-blue-600">' . $product['currency_code'] . ' ' . $product['price'] . '</div>';
                        echo '</a>';
                        echo '    <div class="text-sm text-gray-500 absolute top-3 left-3 p-1 rounded-sm bg-green-300">Stock: ' . $product['stock_quantity'] . ' left</div>';
                        echo (isset($product['stock_quantity']) && $product['stock_quantity'] > 0)
                            ? '    <button type="button" class="add-to-cart-btn bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">Add to Cart</button>'
                            : '    <div class="text-red-500">Out of stock</div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    // Display a message if there are no products
                    echo '<p class="text-slate-400">No products in this category.</p>';
                }
            } else {
                // Display an error message with debug information
                echo '<p>Error: Category not found. Debug: categoryId=' . $categoryId . '</p>';
            }
            ?>

            </div>
    </div>
</div>

<?php include '../components/footer/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Event delegation for wishlist icon click
        $("#productContainer").on("click", ".wishlist-icon", function () {
            var wishlistIcon = $(this);
            var productId = wishlistIcon.closest(".product-item").data("product-id");

            // Perform AJAX request to add to wishlist
            $.ajax({
                method: "POST",
                url: "../files/add-to-wishlist.php",
                contentType: "application/json",
                data: JSON.stringify({ productId: productId }),
                success: function (data) {
                    // Handle the response, e.g., show a success message
                    console.log(data);
                    if (data.success) {
                        window.location.reload();
                    }
                },
                error: function (error) {
                    // Handle errors
                    console.error("Error:", error);
                }
            });
        });

        // Event delegation for wishlist icon on hover
        $("#productContainer").on("mouseenter", ".product-item", function () {
            var wishlistIcon = $(this).find(".wishlist-icon");
            if (wishlistIcon) {
                wishlistIcon.removeClass("hidden");
            }
        });

        $("#productContainer").on("mouseleave", ".product-item", function () {
            var wishlistIcon = $(this).find(".wishlist-icon");
            if (wishlistIcon) {
                wishlistIcon.addClass("hidden");
            }
        });
    });


    $(document).ready(function () {
        // AJAX function to check if the user is logged in
        $(".add-to-cart-btn").on("click", function () {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "../files/checkLogin.php", // Replace with your actual backend endpoint for checking login status
                data: { productId: productId },
                success: function (response) {
                    if (response === "loggedIn") {
                        // If logged in, proceed with adding to cart
                        addToCart(productId);
                    } else {
                        // If not logged in, handle as needed (e.g., show login modal)
                        window.location.href = "../files/userlogin.php";
                    }
                },
                error: function () {
                    console.error("Error checking login status");
                }
            });
        });

        // Function to add product to cart and refresh the page
        function addToCart(productId) {
            $.ajax({
                type: "POST",
                url: "../files/addToCart.php",
                data: { productId: productId },
                success: function (response) {
                    // Handle success (e.g., update cart count)
                    // No need to show the popup, directly refresh the page
                    location.reload();
                },
                error: function () {
                    console.error("Error adding product to cart");
                }
            });
        }
    });
</script>

</body>

</html>
