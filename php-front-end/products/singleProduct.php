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
                               <div class="flex flex-wrap gap-4">
                                    <button type="button" class="px-2.5 py-1.5 bg-pink-100 text-xs text-pink-600 rounded-md flex items-center">
                                    <svg class="w-3 mr-1" fill="currentColor" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z"></path>
                                    </svg>
                                    4.8
                                    </button>
                                    <button type="button" class="px-2.5 py-1.5 bg-gray-100 text-xs text-gray-800 rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 mr-1" fill="currentColor" viewBox="0 0 32 32">
                                        <path d="M14.236 21.954h-3.6c-.91 0-1.65-.74-1.65-1.65v-7.201c0-.91.74-1.65 1.65-1.65h3.6a.75.75 0 0 1 .75.75v9.001a.75.75 0 0 1-.75.75zm-3.6-9.001a.15.15 0 0 0-.15.15v7.2a.15.15 0 0 0 .15.151h2.85v-7.501z" data-original="#000000"></path>
                                        <path d="M20.52 21.954h-6.284a.75.75 0 0 1-.75-.75v-9.001c0-.257.132-.495.348-.633.017-.011 1.717-1.118 2.037-3.25.18-1.184 1.118-2.089 2.28-2.201a2.557 2.557 0 0 1 2.17.868c.489.56.71 1.305.609 2.042a9.468 9.468 0 0 1-.678 2.424h.943a2.56 2.56 0 0 1 1.918.862c.483.547.708 1.279.617 2.006l-.675 5.401a2.565 2.565 0 0 1-2.535 2.232zm-5.534-1.5h5.533a1.06 1.06 0 0 0 1.048-.922l.675-5.397a1.046 1.046 0 0 0-1.047-1.182h-2.16a.751.751 0 0 1-.648-1.13 8.147 8.147 0 0 0 1.057-3 1.059 1.059 0 0 0-.254-.852 1.057 1.057 0 0 0-.795-.365c-.577.052-.964.435-1.04.938-.326 2.163-1.71 3.507-2.369 4.036v7.874z" data-original="#000000"></path>
                                        <path d="M4 31.75a.75.75 0 0 1-.612-1.184c1.014-1.428 1.643-2.999 1.869-4.667.032-.241.055-.485.07-.719A14.701 14.701 0 0 1 1.25 15C1.25 6.867 7.867.25 16 .25S30.75 6.867 30.75 15 24.133 29.75 16 29.75a14.57 14.57 0 0 1-5.594-1.101c-2.179 2.045-4.61 2.81-6.281 3.09A.774.774 0 0 1 4 31.75zm12-30C8.694 1.75 2.75 7.694 2.75 15c0 3.52 1.375 6.845 3.872 9.362a.75.75 0 0 1 .217.55c-.01.373-.042.78-.095 1.186A11.715 11.715 0 0 1 5.58 29.83a10.387 10.387 0 0 0 3.898-2.37l.231-.23a.75.75 0 0 1 .84-.153A13.072 13.072 0 0 0 16 28.25c7.306 0 13.25-5.944 13.25-13.25S23.306 1.75 16 1.75z" data-original="#000000"></path>
                                    </svg>
                                    87 Reviews
                                    </button>
                                </div>
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

                <div class="mt-24 max-w-4xl">
                    <ul class="flex gap-3 border-b">
                        <li class="tab-item active" data-tab="description">
                            Description
                        </li>
                        <li class="tab-item" data-tab="reviews">
                            Reviews
                        </li>
                    </ul>
                    <div id="description-tab" class="mt-8 tab-content">
                        <h3 class="text-lg font-bold text-gray-800">Product Description</h3>
                        <p class="text-sm text-gray-400 mt-4">
                            <?php echo $product['description']; ?>
                        </p>
                    </div>
                    <div id="reviews-tab" class="mt-8 tab-content hidden">
                       <div class="max-w-4xl mx-auto mt-8 p-6 border rounded-lg shadow-md">
                            <h2 class="text-2xl font-bold mb-4">Product Reviews</h2>

                            <!-- Single Review -->
                            <div class="flex items-center mb-4">
                                <div class="mr-4">
                                    <!-- Star Rating Component -->
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-500 fill-current mr-1" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"></path>
                                        </svg>
                                        <span class="text-lg font-bold">4.5</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-700">
                                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget quam ac ligula vehicula rhoncus."
                                    </p>
                                    <p class="text-gray-500 text-sm">- John Doe</p>
                                </div>
                            </div>

                            <!-- Another Review (Copy as needed) -->
                            <div class="flex items-center mb-4">
                                <!-- ... (Copy from the above review) ... -->
                            </div>

                            <!-- Add your own reviews as needed -->

                            <!-- Review Form -->
                            <div class="mt-8">
                                <h3 class="text-xl font-bold mb-4">Write a Review</h3>
                                <form action="../files/reviews.php?id=<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
                                    <div class="mb-4">
                                        <!-- Star Rating Input -->
                                        <div class="flex items-center">
                                            <label class="mr-2">Your Rating:</label>
                                            <div class="flex items-center">
                                                <input type="radio" id="star5" name="rating" value="5" class="hidden">
                                                <label for="star1" class="cursor-pointer fill-current">
                                                   <svg class="w-5 h-5 text-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>
                                                </label>
                                                <label for="star2" class="cursor-pointer fill-current">
                                                   <svg class="w-5 h-5 text-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>
                                                </label>
                                                <label for="star3" class="cursor-pointer fill-current">
                                                   <svg class="w-5 h-5 text-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>
                                                </label>
                                                <label for="star4" class="cursor-pointer fill-current">
                                                   <svg class="w-5 h-5 text-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>
                                                </label>
                                                <label for="star5" class="cursor-pointer fill-current">
                                                   <svg class="w-5 h-5 text-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/></svg>
                                                </label>
                                                <!-- Add more radio buttons for additional stars -->
                                            </div>
                                        </div>
                                    </div>
                                     <div class="mb-4">
                                        <label for="review_text" class="block text-sm font-medium text-gray-700">Your Review:</label>
                                        <textarea id="review_text" name="review_text" rows="3" class="mt-1 p-2 border rounded-md w-full"></textarea>
                                    </div>
                                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Submit Review</button>
                                </form>
                            </div>
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


    document.addEventListener("DOMContentLoaded", function() {
        const tabItems = document.querySelectorAll(".tab-item");
        const tabContents = document.querySelectorAll(".tab-content");

        tabItems.forEach(function(item) {
            item.addEventListener("click", function() {
                const tabName = this.getAttribute("data-tab");

                // Remove 'active' class from all tab items and hide all tab contents
                tabItems.forEach(function(item) {
                    item.classList.remove("active");
                });
                tabContents.forEach(function(content) {
                    content.classList.add("hidden");
                });

                // Add 'active' class to the clicked tab item and show the corresponding tab content
                this.classList.add("active");
                document.getElementById(tabName + "-tab").classList.remove("hidden");
            });
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        const starLabels = document.querySelectorAll('.cursor-pointer');
        const ratingInput = document.querySelector('input[name="rating"]');

        starLabels.forEach(function(label, index) {
            label.addEventListener("click", function() {
                const ratingValue = index + 1;
                ratingInput.value = ratingValue;
                
                // Reset all stars
                starLabels.forEach(function(starLabel, i) {
                    if (i < ratingValue) {
                        starLabel.classList.add('text-yellow-500');
                    } else {
                        starLabel.classList.remove('text-yellow-500');
                    }
                });
            });
        });
    });
    </script>

</body>

</html>
