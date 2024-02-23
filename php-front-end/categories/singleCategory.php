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
            <div class="pb-3 border-b border-slate-200">
                <h2 class="text-lg font-semibold"> All Categories</h2>
            </div>
            <div class="mt-10">
                <?php
                // Fetch products from the server (you need to implement this endpoint)
                $productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

                // Fetch category ID from the URL parameter
                $category_id = $_GET['category_id'] ?? null;

                // Fetch categories data using cURL for better error handling
                $categoriesData = [];
                $categoriesEndpoint = "http://localhost/reactcrud/backend/auth/api/categories/categories.php";

                $ch = curl_init($categoriesEndpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $categoriesData = json_decode(curl_exec($ch), true);
                curl_close($ch);

                // Find the clicked category data from the fetched categories
                $clickedCategory = findCategoryById($categoriesData, $category_id);

                // Render subcategories based on the clicked category
                renderCategories($clickedCategory, $clickedCategory['subcategories']);

                // Render products based on the clicked category
                renderProducts($clickedCategory, $productsData);

            
                // Function to render subcategories recursively
                function renderCategories($parentCategory, $subcategories)
                {
                    if ($subcategories && is_array($subcategories) && count($subcategories) > 0) {
                        
                        foreach ($subcategories as $subcategory) {
                            echo '<div class="ecom-cat-item xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-24px)] w-full mb-8 flex flex-col space-y-4 p-3 border border-slate-200">';
                            echo '  <div class="ecom-cat-photo w-full h-32 lg:h-40 rounded-sm border border-slate-200 bg-gray-300 flex justify-center items-center">';
                            // Adjust the path based on your structure
                            echo '    <img src="http://localhost/reactcrud/backend/auth/assets/categories/' . $subcategory['id'] . '/' . $subcategory['photo_name'] . '" alt="Category Image: ' . $subcategory['name'] . '" class="w-full h-full object-fill" />';
                            echo '  </div>';
                            echo '  <div class="ecom-cat-d flex flex-col justify-between">';
                            echo '    <div class="ecom-cat-header flex justify-between">';
                            echo '      <span class="block w-full text-base lg:text-lg font-medium">' . $subcategory['name'] . '</span>';
                            echo '      <span class="block w-full text-gray-400 text-sm lg:text-base text-right">(' . $subcategory['product_count'] . ($subcategory['product_count'] !== 1 ? '' : '') . ')</span>';
                            echo '    </div>';
                            // Adjust the link based on your structure
                            echo '    <a href="/reactcrud/php-front-end/categories/singleCategory.php?category_id=' . $subcategory['id'] . '" class="inline-block text-red-300">Show All</a>';
                            echo '  </div>';
                            echo '</div>';
                            // Recursively render sub-subcategories
                            renderCategories($subcategory, $subcategory['subcategories']);
                        }
                        
                    }
                }

                // Function to render products based on the clicked category
                function renderProducts($category, $products)
                {
                    if ($category) {
                        echo '<div class="pb-3 border-b border-slate-200">';
                        echo '  <h2 class="text-lg font-semibold"> All Products</h2>';
                        echo '</div>';
                        echo '<div class="flex flex-wrap gap-8 mt-10">';
                        foreach ($products as $product) {
                            if ($product['category_id'] == $category['id']) {
                                echo '    <div class="xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-24px)] w-full">';
                                echo '      <div class="bg-white p-4 rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group">';
                                echo '        <!-- Render product card -->';
                                echo '        <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-cover rounded-md mb-4" />';
                                echo '        <h2 class="text-xl font-semibold mb-2">' . $product['name'] . '</h2>';
                                echo '        <div class="flex flex-col gap-3">';
                                echo '          <div class="text-lg font-bold text-blue-600">' . $product['currency_code'] . ' ' . $product['price'] . '</div>';
                                echo '            <button type="submit" class="add-to-cart-btn bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="' . $product['id'] . '">';
                                echo '              Add to Cart';
                                echo '            </button>';
                                echo '        </div>';
                                echo '      </div>';
                                echo '    </div>';
                            }
                        }
                        echo '</div>';
                    }
                }

                // Function to find a category by ID
                function findCategoryById($categories, $categoryId)
                {
                    foreach ($categories as $category) {
                        if ($category['id'] == $categoryId) {
                            return $category;
                        }
                        // Check subcategories recursively
                        if (isset($category['subcategories']) && is_array($category['subcategories']) && count($category['subcategories']) > 0) {
                            $subcategoryResult = findCategoryById($category['subcategories'], $categoryId);
                            if ($subcategoryResult) {
                                return $subcategoryResult;
                            }
                        }
                    }
                    return null;
                }
                ?>
            </div>
        </div>
    </div>

    <?php include '../components/footer/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX function to check if the user is logged in
        $(".add-to-cart-btn").on("click", function() {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "../files/checkLogin.php", // Replace with your actual backend endpoint for checking login status
                data: {productId: productId},
                success: function(response) {
                    if (response === "loggedIn") {
                        // If logged in, proceed with adding to cart
                        addToCart(productId);
                    } else {
                        // If not logged in, handle as needed (e.g., show login modal)
                        window.location.href = "../files/userlogin.php";
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
                url: "../files/addToCart.php",
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
