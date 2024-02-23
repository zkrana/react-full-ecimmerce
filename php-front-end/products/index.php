
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assets/styling/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
    <?php include '../components/header/header.php'; ?>
    <div class="w-[90%] sm:max-w-7xl mx-auto sm:pt-20 pb-14 mt-7">
        <div class="shop-wrapper flex flex-wrap">

            <!-- Sidebar -->
            <div class="w-1/4  text-slate-800 p-6 pt-0">

                <!-- Category Filter -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Categories</h3>
                   <?php include "../components/ecommerce/catSidebar.php"; ?>
                </div>

                <!-- Price Range Filter -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Price Range</h3>
                    <input type="range" min="0" max="100" step="10" class="w-full" />
                </div>

                <!-- Brand Filter -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Brands</h3>
                    <ul>
                        <li class="mb-1"><a href="#" class="hover:underline">Brand 1</a></li>
                        <li class="mb-1"><a href="#" class="hover:underline">Brand 2</a></li>
                        <li class="mb-1"><a href="#" class="hover:underline">Brand 3</a></li>
                        <!-- Add more brands as needed -->
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-3/4 p-8 !pt-0">
                <div>
                    <div class="shop-up-filter flex items-center flex-wrap justify-between">
                        <h1 class="text-base text-slate-400">Showing 1–12 of 57 results</h1>
                        <!-- Sort Filter -->
                        <div>
                            <select id="sortFilter" name="orderby" class="p-2 rounded-sm" aria-label="Shop order">
                                <option value="menu_order" selected="selected">Default sorting</option>
                                <option value="popularity">Sort by popularity</option>
                                <option value="rating">Sort by average rating</option>
                                <option value="date">Sort by latest</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Cards -->
                    <div id="productContainer" class="flex flex-wrap">
                        <!-- Product cards will be dynamically loaded here -->
                    </div>
                </div>
                <div class="pagination flex gap-1 justify-center list-none p-4">
                    <?php
                    $total_results = 57;
                    $items_per_page = 12; // Add this line to define $items_per_page
                    $total_pages = ceil($total_results / $items_per_page);

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<a href="#" class="page-link inline-block px-4 py-2 mx-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 ease-in-out" data-page="' . $i . '">' . $i . '</a>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
    <?php include '../components/footer/footer.php'; ?>
    <script>
   $(document).ready(function () {
    // Function to fetch and display products based on filters and page
    function fetchProducts(page) {
        var category_id = 1; // Get this dynamically based on user selection
        var min_price = 0; // Get this dynamically based on user selection
        var max_price = 100; // Get this dynamically based on user selection
        var sort = $("#sortFilter").val();

        $.ajax({
            type: "GET",
            url: "../auth/api/products_api.php",
            data: {
                category_id: category_id,
                min_price: min_price,
                max_price: max_price,
                sort: sort,
                page: page
            },
            dataType: "json",
            success: function (response) {
                console.log("Success:", response);
                // Handle the response and update the product container
                updateProductContainer(response);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching products", error);
                console.log("XHR status:", xhr.status);
                console.log("XHR responseText:", xhr.responseText);
            }
        });
    }


    // Function to update the product container with fetched products
    function updateProductContainer(response) {
        console.log("Response:", response); // Add this line to log the response
        var productContainer = $("#productContainer");
        productContainer.empty();

        if (response.success) {
            // Loop through the products and append them to the container
            $.each(response.data, function (index, product) {
                var productCard = '<div class="w-1/2 sm:w-1/3 p-4">' +
                    '<div class="bg-white p-4 rounded-md shadow">' +
                    '<img src="' + product.product_photo + '" alt="Product Image" class="w-full mb-4">' +
                    '<h2 class="text-lg font-semibold mb-2">' + product.name + '</h2>' +
                    '<p class="text-gray-600 mb-2">' + product.description + '</p>' +
                    '<p class="text-green-600 font-semibold">$' + product.price + '</p>' +
                    '<button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Add to Cart</button>' +
                    '</div></div>';

                productContainer.append(productCard);
            });
        } else {
            console.error("Error fetching products: " + response.message);
        }
    }

    // Initial fetch when the page loads
    fetchProducts(1);

    // Event listener for changes in the sort filter
    $("#sortFilter").on("change", function () {
        fetchProducts(1);
    });

    // Event listener for pagination links
    $(".pagination").on("click", ".page-link", function (e) {
        e.preventDefault();
        var page = $(this).data("page");
        fetchProducts(page);
    });
});

    </script>
</body>
</html>