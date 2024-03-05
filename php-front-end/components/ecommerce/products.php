<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<?php
// Fetch products from the server (you need to implement this endpoint)
$productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

// Function to display products with fadeIn effect
function displayProducts($products, $fadeIn = false)
{
    foreach ($products as $index => $product) {
        $fadeInClass = $fadeIn ? 'fade-in' : ''; // Add fade-in class if required
        echo '<div class="product-item xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-10px)] w-full group ' . $fadeInClass . ' bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="' . $product['id'] . '">';
        echo '  <div class="wishlist-icon hidden absolute right-3 top-3 w-6 h-6 flex justify-center items-center text-lg cursor-pointer hover:text-base hover:bg-slate-400 hover:text-white hover:rounded-full">';
        echo '    <i class="fa-regular fa-heart"></i>';
        echo '  </div>';
        echo '<a href="products/singleProduct.php?id=' . $product['id'] . '">';

        echo '  <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-contain rounded-md mb-4" />';
        echo '  <h2 class="text-xl font-semibold mb-2 h-16">' . $product['name'] . '</h2>';
        echo '  <div class="flex flex-col gap-3">';
        echo '    <div class="text-lg font-bold text-blue-600">' . $product['currency_code'] . ' ' . $product['price'] . '</div>';
        echo '</a>';
        echo '    <div class="text-sm text-gray-500 absolute top-3 left-3 p-1 rounded-sm bg-green-300">Stock: ' . $product['stock_quantity'] . ' left</div>';
        echo (isset($product['stock_quantity']) && $product['stock_quantity'] > 0)
            ? '    <button type="button" class="add-to-cart-btn inline-flex justify-center items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">
            <svg class=" w-3.5 h-3.5 me-2 mt-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
            <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
            </svg>
            Add to Cart</button>'
            : '    <div class="text-red-500">Out of stock</div>';
        echo '  </div>';
        echo '</div>';
    }
}

// Display product information
echo '<div class="mt-7">';
echo '  <div class="pb-3 border-b border-slate-200">';
echo '    <h2 class="text-lg font-semibold">Running Products</h2>';
echo '  </div>';
echo '  <div class="product-wrapper flex flex-col justify-between gap-6">';
echo '    <div class="w-full mt-10 flex flex-wrap lg:gap-8 gap-5" id="productContainer">';

// Adjust the count to the available products
$initialDisplayCount = min(12, count($productsData));
displayProducts(array_slice($productsData, 0, $initialDisplayCount), true);

echo '    </div>';
if ($initialDisplayCount < count($productsData)) {
    echo '    <button id="loadMoreBtn" class="mt-4 inline-block mx-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">Load More</button>';
}
echo '  </div>';
echo '</div>';
// JavaScript or jQuery code to show the wishlist icon on hover and handle click events
echo '<script>
    $(document).ready(function() {
        // Event delegation for wishlist icon click
        $("#productContainer").on("click", ".wishlist-icon", function() {
            var wishlistIcon = $(this);
            var productId = wishlistIcon.closest(".product-item").data("product-id");

            // Perform AJAX request to add to wishlist
            $.ajax({
                method: "POST",
                url: "./files/add-to-wishlist.php",
                contentType: "application/json",
                data: JSON.stringify({ productId: productId }),
                success: function(data) {
                    // Handle the response, e.g., show a success message
                    console.log(data);
                    if (data.success) {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    // Handle errors
                    console.error("Error:", error);
                }
            });
        });

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
    });
</script>';


echo '<script>
    $(document).ready(function() {
        var productsData = ' . json_encode($productsData) . ';
        var currentDisplayIndex = ' . $initialDisplayCount . ';

        function displayProducts(products, fadeIn) {
            var container = $("#productContainer");

            products.forEach(function (product, index) {
                var fadeInClass = fadeIn ? "fade-in" : "";
                var productHtml = \'<div class="product-item xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-10px)] w-full group \' + fadeInClass + \' bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="\' + product.id + \'">\'+
                    \'<div class="wishlist-icon hidden absolute right-3 top-3 w-6 h-6 flex justify-center items-center text-lg cursor-pointer hover:text-base hover:bg-slate-400 hover:text-white hover:rounded-full">\'+
                    \'<i class="fa-regular fa-heart"></i>\'+
                    \'</div>\'+
                    \'<a href="products/singleProduct.php?id=\' + product.id + \'">\'+
                    \'<img src="http://localhost/reactcrud/backend/auth/assets/products/\' + product.product_photo + \'" alt="\' + product.name + \'" class="w-full h-40 object-contain rounded-md mb-4" />\'+
                    \'<h2 class="text-xl font-semibold mb-2 h-28">\' + product.name + \'</h2>\'+
                    \'<div class="flex flex-col gap-3">\'+
                    \'<div class="text-lg font-bold text-blue-600">\' + product.currency_code + \' \' + product.price + \'</div>\'+
                    \'<div class="text-sm text-gray-500 absolute top-3 left-3 p-1 rounded-sm bg-green-300">Stock: \' + product.stock_quantity + \' left</div>\' +
                    \'</a>\'+
                    (product.stock_quantity > 0 ?
                    \'<button type="button" class="add-to-cart-btn bg-blue-600 text-white px-4 py-2 rounded-md \'+
                    \'hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="\' + product.id + \'">\' +
                    \'Add to Cart\' +
                    \'</button>\' :
                    \'<div class="text-red-500">Out of stock</div>\') +
                    \'</div>\'+
                    \'</div>\';

                container.append(productHtml);

                // Apply fadeIn effect
                if (fadeIn) {
                    $(".fade-in").last().hide().fadeIn(300 * (index + 1));
                }
            });
        }

        function loadMoreProducts() {
            var nextProducts = productsData.slice(currentDisplayIndex, currentDisplayIndex + 4);

            // Check if there are more products to display
            if (nextProducts.length > 0) {
                displayProducts(nextProducts, true);
                currentDisplayIndex += 4;
            } else {
                // Hide the Load More button if no more products
                $("#loadMoreBtn").hide();
            }
        }

        $("#loadMoreBtn").click(function() {
            loadMoreProducts();
        });
    });
</script>';

?>


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



