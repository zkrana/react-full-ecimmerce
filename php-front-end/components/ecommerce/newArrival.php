<!-- Add this code to the desired section in your HTML file -->
<div class="mt-14 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.3333%-21.3333px)]">
    <h4 class="text-lg font-semibold pb-3 border-b border-slate-200 capitalize">
        New arrival
    </h4>

    <?php
    // Function to fetch the last added products
    function getLastAddedProducts($connection, $limit = 3) {
        $query = "SELECT * FROM `products` ORDER BY `created_at` DESC LIMIT :limit";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the query was successful and if there is a result
        if ($stmt && $stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return array(); // Return an empty array if no result is found
    }

    // Fetch the last added products
    $lastAddedProducts = getLastAddedProducts($connection);

    // Display the last added products
    if (!empty($lastAddedProducts)) {
        echo '<div id="bestSellingProductsContainer" class="flex flex-col gap-5 mt-4">';
        foreach ($lastAddedProducts as $product) {
            echo '<div class="product-item w-full group flex justify-between pt-2 bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="' . $product['id'] . '">';
            echo '<a href="products/singleProduct.php?id=' . $product['id'] . '">';
            echo '<div class="w-[90%]">';
            echo '<div class=" w-16 h-16 overflow-hidden flex justify-center items-center">';
            echo '  <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-contain rounded-md mb-4" />';
            echo '</div>';
            echo '  <h2 class="text-sm font-semibold mb-2">' . $product['name'] . '</h2>';
            echo '</div>';
            echo '  <div class="flex flex-col gap-3">';
            echo '    <div class="lg:text-lg text-sm text-right font-bold text-blue-600">' . $product['currency_code'] . ' ' . $product['price'] . '</div>';
            echo '</a>';
            echo '    <div class="text-sm text-gray-500 absolute bottom-3 right-3 p-1 rounded-sm">Stock: ' . $product['stock_quantity'] . ' left</div>';
            echo (isset($product['stock_quantity']) && $product['stock_quantity'] > 0)
                ? '    <button type="button" class="add-to-cart-btn inline-flex justify-center items-center text-sm bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">
                <svg class=" w-3.5 h-3.5 me-2 mt-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                </svg>
                Add to Cart</button>'
                : '    <div class="text-red-500">Out of stock</div>';
            echo '  </div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="text-slate-400">No new arrivals at the moment.</p>';
    }
    ?>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Event delegation for wishlist icon on hover
        $("#productContainer").on("mouseenter", ".product-item", function() {
            var wishlistIcon = $(this).find(".wishlist-icon");
            if (wishlistIcon) {
                wishlistIcon.removeClass("hidden");
            }
        });

        $("#productContainer").on("mouseleave", ".product-item", function() {
            var wishlistIcon = $(this).find(".wishlist-icon");
            if (wishlistIcon) {
                wishlistIcon.addClass("hidden");
            }
        });

        // Event delegation for add-to-cart button click
        $("#productContainer").on("click", ".add-to-cart-btn", function() {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "./files/checkLogin.php",
                data: {productId: productId},
                success: function(response) {
                    if (response === "loggedIn") {
                        addToCart(productId);
                    } else {
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
                    location.reload();
                },
                error: function() {
                    console.error("Error adding product to cart");
                }
            });
        }
    });
</script>