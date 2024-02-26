<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="../assets/styling/style.css">
    <script src="../assets/js/main.js"></script>
</head>

<body>
    <?php
    include '../auth/connection/config.php';

    // Get product ID from the URL
    $product_id = isset($_GET['id']) ? $_GET['id'] : null;

    // Validate product ID
    if (!$product_id) {
        die("Product ID is required.");
    }

    // Build the SQL query to fetch the product details based on ID
    $productSql = "SELECT * FROM products WHERE id = :product_id";
    $stmt = $connection->prepare($productSql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the product exists
    if (!$product) {
        die("Product not found.");
    }

    // Build the SQL query to fetch variations related to the product
    $variationSql = "SELECT * FROM variations WHERE product_id = :product_id";
    $variationStmt = $connection->prepare($variationSql);
    $variationStmt->bindParam(':product_id', $product_id);
    $variationStmt->execute();

    $variations = $variationStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php include '../components/header/header.php'; ?>
    <div class="container">
        <div class="sm:max-w-7xl w-[90%] mx-auto my-10">
            <div class="product-component flex flex-wrap gap-8">
                <div class="product sm:w-[calc(50%-16px)] w-full">
                    <div class="image-container ">
                        <div id="mainProductImageContainer" class="max-h-[500px] overflow-hidden rounded-lg bg-gray-300 dark:bg-gray-700 mb-4">
                            <img id="mainProductImage" class="w-full h-full object-cover image-preview image-preview-js" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $product['product_photo']; ?>" alt="Product Image">
                        </div>
                        <div class="magnifier">
                            <img id="magnifierImage" class="magnifier__img" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $product['product_photo']; ?>" alt="Magnified Image">
                        </div>
                    </div>
                    <div class="variation-images">
                        <ul class="flex lg:gap-4 gap-2 items-center">
                            <?php foreach ($variations as $variation) : ?>
                                <li class="lg:w-48 w-auto lg:h-48 h-auto overflow-auto cursor-pointer" onclick="changeMainProductImage('<?php echo $variation['image_path']; ?>')">
                                    <img class="w-full h-full lg:object-cover object-contain" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $variation['image_path']; ?>" alt="Variation Image">
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="p-description sm:w-[calc(50%-16px)] w-full pt-10">
                    <div class="md:flex-1 px-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2"><?php echo $product['name']; ?></h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                            <?php echo $product['description']; ?>
                        </p>
                        <div class="flex mb-4">
                            <div class="mr-4">
                                <span class="font-bold text-gray-700 dark:text-gray-300">Price:</span>
                                <span class="text-gray-600 dark:text-gray-300"><?php echo '$' . $product['price']; ?></span>
                            </div>
                            <div>
                                <span class="font-bold text-gray-700 dark:text-gray-300">Availability:</span>
                                <span class="text-gray-600 dark:text-gray-300"><?php echo $product['stock_quantity']; ?></span>
                            </div>
                        </div>
                        <?php if (!empty($variations) && array_filter($variations[0])) : ?>
                            <!-- Display the color variation as a clickable element -->
                            <div class="mt-4">
                                <label class="font-bold text-gray-700 dark:text-gray-300">Select Color:</label>
                                <div class="flex mt-2">
                                    <?php foreach ($variations as $variation) : ?>
                                        <?php if (!empty(array_filter($variation))) : ?>
                                            <div class="mr-3">
                                                <span class="w-8 h-8 block rounded-full cursor-pointer" style="background-color: <?php echo $variation['color']; ?>" onclick="changeVariationImage('<?php echo $variation['image_path']; ?>', '<?php echo $variation['color']; ?>')"></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php
                            // Check if there are non-empty options for SIM
                            $simOptions = array_filter(array_column($variations, 'sim'));
                            if (!empty($simOptions)) : ?>
                                <!-- Select dropdown for SIM -->
                                <div class="mt-4">
                                    <label class="font-bold text-gray-700 dark:text-gray-300">Select SIM:</label>
                                    <select id="simSelect" class="ml-2 border rounded-md">
                                        <?php foreach ($simOptions as $simOption) : ?>
                                            <option value="<?php echo $simOption; ?>"><?php echo $simOption; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Check if there are non-empty options for Storage
                            $storageOptions = array_filter(array_column($variations, 'storage'));
                            if (!empty($storageOptions)) : ?>
                                <!-- Select dropdown for Storage -->
                                <div class="mt-4">
                                    <label class="font-bold text-gray-700 dark:text-gray-300">Select Storage:</label>
                                    <select id="storageSelect" class="ml-2 border rounded-md">
                                        <?php foreach ($storageOptions as $storageOption) : ?>
                                            <option value="<?php echo $storageOption; ?>"><?php echo $storageOption; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Check if there are non-empty options for Type
                            $typeOptions = array_filter(array_column($variations, 'type'));
                            if (!empty($typeOptions)) : ?>
                                <!-- Select dropdown for Type -->
                                <div class="mt-4">
                                    <label class="font-bold text-gray-700 dark:text-gray-300">Select Type:</label>
                                    <select id="typeSelect" class="ml-2 border rounded-md">
                                        <?php foreach ($typeOptions as $typeOption) : ?>
                                            <option value="<?php echo $typeOption; ?>"><?php echo $typeOption; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <!-- Display the selected color variation as a rounded div -->
                            <div id="selectedVariation" class="mt-2">
                                <span id="selectedColor" class="w-8 h-8 rounded-full"></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <span class="font-bold text-gray-700 dark:text-gray-300">Product Description:</span>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">
                                <?php echo $product['description']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex -mx-2 mb-4 mt-10">
                        <div class="sm:w-1/2 w-full px-2">
                            <button class="w-full bg-gray-900 lg:text-base text-sm dark:bg-gray-600 text-white py-2 px-4 rounded-full font-bold hover:bg-gray-800 dark:hover:bg-gray-700">Add to Cart</button>
                        </div>
                        <div class="sm:w-1/2 w-full px-2">
                            <button class="w-full lg:text-base text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white py-2 px-4 rounded-full font-bold hover:bg-gray-300 dark:hover:bg-gray-600">Add to Wishlist</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php
            // Fetch the category ID of the current product
            $category_id_query = "SELECT category_id FROM products WHERE id = :product_id";
            $category_id_stmt = $connection->prepare($category_id_query);
            $category_id_stmt->bindParam(':product_id', $product_id);
            $category_id_stmt->execute();
            $category_id_result = $category_id_stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = $category_id_result['category_id'];

            // Fetch related products from the same category excluding the current product
            $relatedProductsSql = "SELECT * FROM products WHERE category_id = :category_id AND id != :product_id LIMIT 10";
            $relatedProductsStmt = $connection->prepare($relatedProductsSql);
            $relatedProductsStmt->bindParam(':category_id', $category_id);
            $relatedProductsStmt->bindParam(':product_id', $product_id);
            $relatedProductsStmt->execute();
            $relatedProducts = $relatedProductsStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>


            <div class="related-products-slider mt-24 relative overflow-hidden">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Related Products</h2>
                <div class="swiper-container mt-8">
                    <div class="swiper-wrapper">
                        <?php foreach ($relatedProducts as $relatedProduct) : ?>
                            <div class="swiper-slide">
                                <div class="related-product-item bg-white dark:bg-gray-800 shadow-md rounded-md p-4 transition-transform transform hover:scale-105">
                                    <div class="">
                                        <img src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $relatedProduct['product_photo']; ?>" alt="<?php echo $relatedProduct['name']; ?>" class="w-full h-48 object-cover mb-4 rounded-md">
                                    </div>

                                    <!-- Product Details -->
                                    <h3 class="text-lg h-20 font-semibold text-gray-800 dark:text-white mb-2"><?php echo $relatedProduct['name']; ?></h3>
                                    <!-- <p class="text-gray-600 dark:text-gray-300 mb-4"><?php echo $relatedProduct['description']; ?></p> -->

                                    <!-- Add to Cart Button (customize as needed) -->
                                    <button class="bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">Add to Cart</button>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../components/footer/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../assets/js/relatedSwiper.js"></script>
    <script>
        const mainProductImageContainer = document.getElementById('mainProductImageContainer');
        const mainProductImage = document.getElementById('mainProductImage');
        const magnifier = document.querySelector('.magnifier');
        let magnifierImage = document.getElementById('magnifierImage');

        mainProductImageContainer.addEventListener('mousemove', handleMouseMove);
        mainProductImageContainer.addEventListener('mouseleave', hideMagnifier);

        function handleMouseMove(event) {
            if (!magnifierImage) {
                // If magnifierImage is not initialized, find it within the magnifier element
                magnifierImage = magnifier.querySelector('.magnifier__img');
            }

            const { left, top, width, height } = mainProductImageContainer.getBoundingClientRect();
            const mouseX = event.clientX - left;
            const mouseY = event.clientY - top;

            const scaleX = mainProductImage.width / width;
            const scaleY = mainProductImage.height / height;

            const magnifierWidth = 150; // Adjust as needed
            const magnifierHeight = 150; // Adjust as needed

            const backgroundPositionX = -mouseX * scaleX + magnifierWidth / 2;
            const backgroundPositionY = -mouseY * scaleY + magnifierHeight / 2;

            magnifier.style.backgroundPosition = `${backgroundPositionX}px ${backgroundPositionY}px`;
            magnifier.style.display = 'block';

            magnifierImage.src = mainProductImage.src;
        }

        function hideMagnifier() {
            magnifier.style.display = 'none';
        }



        function changeMainProductImage(imagePath) {
            // Set the main product image source to the selected variation image
            document.getElementById('mainProductImage').src = 'http://localhost/reactcrud/backend/auth/assets/products/' + imagePath;

            // Show the main product image container
            document.getElementById('mainProductImageContainer').classList.remove('hidden');
        }
        
      function changeVariationImage(imagePath, color) {
        // Update the main product image based on the selected variation
        var mainProductImage = document.getElementById('mainProductImage');
        mainProductImage.src = 'http://localhost/reactcrud/backend/auth/assets/products/' + imagePath;

        // Update the selected color variation display
        var selectedColor = document.getElementById('selectedColor');
        selectedColor.style.backgroundColor = color;
    }
    </script>

</body>

</html>
