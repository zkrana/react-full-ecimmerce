<!-- Add this code to the desired section in your HTML file -->
<div class="mt-14 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.3333%-21.3333px)]">
    <h4 class="text-lg font-semibold pb-3 border-b border-slate-200 capitalize">
        Trending
    </h4>

    <?php
    // Function to fetch the best-selling products
    function getBestSellingProducts($connection, $limit = 3) {
        $query = "
            SELECT
                product_id,
                SUM(quantity) AS total_quantity
            FROM
                order_items
            GROUP BY
                product_id
            ORDER BY
                total_quantity DESC
            LIMIT
                :limit
        ";

        $stmt = $connection->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the query was successful and if there is a result
        if ($stmt && $stmt->rowCount() > 0) {
            $bestSellingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch product details for the best-selling products
            $products = array();
            foreach ($bestSellingProducts as $item) {
                $productId = $item['product_id'];
                $productDetails = getProductDetails($connection, $productId);

                if ($productDetails !== false) {
                    $products[] = $productDetails;
                }
            }

            return $products;
        }

        return array(); // Return an empty array if no result is found
    }

    // Function to fetch product details based on productId
    function getProductDetails($connection, $productId) {
        $query = "SELECT * FROM `products` WHERE `id` = :productId";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if the query was successful and if there is a result
        if ($stmt && $stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false; // Return false if no result is found
    }

    // Fetch the best-selling products
    $bestSellingProducts = getBestSellingProducts($connection, 3);

    // Display the best-selling products
    if (!empty($bestSellingProducts)) {
        echo '<div id="bestSellingProductsContainer" class="flex flex-col gap-5 mt-4">';
        foreach ($bestSellingProducts as $product) {
            echo '<div class="product-item w-full group flex justify-between pt-2 bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="' . $product['id'] . '">';
            echo '<a href="products/singleProduct.php?id=' . $product['id'] . '">';
            echo '<div class="w-[90%]">';
            echo '<div class=" w-16 h-16 overflow-hidden flex justify-center items-center">';
            echo '  <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-contain rounded-md mb-4" />';
            echo '</div>';
            echo '  <h2 class="text-sm font-semibold mb-2">' . $product['name'] . '</h2>';
            echo '</div>';
            echo '  <div class="flex flex-col gap-3">';
            echo '    <div class="lg:text-lg text-sm font-bold text-blue-600">' . $product['currency_code'] . ' ' . $product['price'] . '</div>';
            echo '</a>';
            echo '    <div class="text-sm text-gray-500 absolute bottom-3 right-3 p-1 rounded-sm">Stock: ' . $product['stock_quantity'] . ' left</div>';
            echo (isset($product['stock_quantity']) && $product['stock_quantity'] > 0)
                ? '    <button type="button" class="add-to-cart-btn text-sm bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">Add to Cart</button>'
                : '    <div class="text-red-500">Out of stock</div>';
            echo '  </div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="text-slate-400">No trending products at the moment.</p>';
    }
    ?>

    <!-- "View More" button -->
    <div class="mt-4">
        <a href="#" class="text-blue-600 hover:underline">View More</a>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Event delegation for add-to-cart button click
        // Event delegation for add-to-cart button click
        $("#bestSellingProductsContainer").on("click", ".add-to-cart-btn", function() {
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