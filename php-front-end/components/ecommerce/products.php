<?php
    // Fetch products from the server (you need to implement this endpoint)
    $productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

    // Display product information
    echo '<div class="mt-7">';
    echo '  <div class="pb-3 border-b border-slate-200">';
    echo '    <h2 class="text-lg font-semibold">New Collections</h2>';
    echo '  </div>';
    echo '  <div class="product-wrapper flex justify-between gap-6">';
    echo '    <div class="w-full mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';
    
    foreach ($productsData as $product) {
       echo '<a href="products/singleProduct.php?id=' . $product['id'] . '" class="product-item bg-white p-4 group rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group" data-product-id="' . $product['id'] . '">';
        // Wishlist icon initially hidden
        echo '        <div class="wishlist-icon hidden absolute right-3 top-3 w-6 h-6 flex justify-center items-center text-lg hover:text-base cursor-pointer hover:bg-slate-400 hover:text-white hover:rounded-full">';
        echo '            <i class="fa-regular fa-heart"></i>';
        echo '        </div>';
        
        echo '      <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-cover rounded-md mb-4" />';
        echo '      <h2 class="text-xl font-semibold mb-2 h-28">' . $product['name'] . '</h2>';
        echo '      <div class="flex flex-col gap-3">';
        echo '        <div class="text-lg font-bold text-blue-600">'. $product['currency_code'] .' ' . $product['price'] .'</div>';
        
        if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0) {
            // Add to Cart button
            echo '<button type="button" class="add-to-cart-btn bg-blue-600 text-white px-4 py-2 rounded-md 
                        hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">';
            echo '            Add to Cart';
            echo '        </button>';
        } else {
            // Out of stock message
            echo '        <div class="text-red-500">Out of stock</div>';
        }
        
        echo '      </div>';
        echo '    </a>';
    }

    // JavaScript or jQuery code to show the wishlist icon on hover
    echo '<script>
        $(document).ready(function() {
            $(".product-item").each(function() {
                var productItem = $(this);
                var wishlistIcon = productItem.find(".wishlist-icon");
                var productId = productItem.data("product-id");

                productItem.hover(
                    function() {
                        if (wishlistIcon) {
                            wishlistIcon.removeClass("hidden");
                        }
                    },
                    function() {
                        if (wishlistIcon) {
                            wishlistIcon.addClass("hidden");
                        }
                    }
                );

                wishlistIcon.click(function() {
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
            });
        });
    </script>';


    echo '  </div>';
    echo ' </div>';
    echo '</div>';
?>



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
