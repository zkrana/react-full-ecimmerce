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
    <div class="w-[90%] sm:max-w-7xl mx-auto my-10">
        <div class="pb-3 border-b border-slate-200">
            <h2 class="text-lg font-semibold"> All Products</h2>
        </div>
        <div class="products">
            <?php
            // Fetch products from the server (you need to implement this endpoint)
            $productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

            // Fetch category ID from the URL parameter
            $category_id = $_GET['category_id'] ?? null;

            // Filter products based on the category ID
            $filteredProducts = [];
            if ($category_id !== null) {
                foreach ($productsData as $product) {
                    if ($product['category_id'] == $category_id) {
                        $filteredProducts[] = $product;
                    }
                }
            } else {
                // If no category ID is provided, use all products
                $filteredProducts = $productsData;
            }

            // Display product information
            echo '<div class="mt-7">';
            echo '  <div class="pb-3 border-b border-slate-200">';
            echo '    <h2 class="text-lg font-semibold">New Collections</h2>';
            echo '  </div>';
            echo '  <div class="product-wrapper flex justify-between gap-6">';
            echo '    <div class=" w-full mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';

            foreach ($filteredProducts as $product) {
                echo '    <div class="bg-white p-4 rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group">';
                echo '      <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-cover rounded-md mb-4" />';
                echo '      <h2 class="text-xl font-semibold mb-2">' . $product['name'] . '</h2>';
                echo '      <div class="flex flex-col gap-3">';
                echo '        <div class="text-lg font-bold text-blue-600">'. $product['currency_code'] .' ' . $product['price'] .'</div>';
                echo '        <form method="post" action="../files/addToCart.php">';
                echo '          <input type="hidden" name="productId" value="' . $product['id'] . '">';
                echo '          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md 
                            hover:bg-blue-700 transition duration-300 ease-in-out">';
                echo '            Add to Cart';
                echo '          </button>';
                echo '        </form>';
                echo '      </div>';
                echo '    </div>';
            }

            echo '  </div>';
            echo ' </div>';
            echo '</div>';
            ?>

        </div>
    </div>
    <?php include '../components/footer/footer.php'; ?>
</body>

</html>
