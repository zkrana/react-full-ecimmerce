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

    <?php
    // Set the timezone to Dhaka
    date_default_timezone_set('Asia/Dhaka');

    // Retrieve product ID from the URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Validate product ID (you might want to add more validation)
    if ($productId <= 0) {
        echo "Invalid product ID";
        exit;
    }

    // Fetch reviews with customer names for the specific product using a JOIN
    $query = "SELECT pr.*, CONCAT(c.first_name, ' ', c.last_name) AS customer_name
            FROM `product_reviews` pr
            JOIN `customers` c ON pr.customer_id = c.id
            WHERE pr.product_id = ? AND pr.reviewStatus = 'approved'
            ORDER BY pr.created_at DESC";

    $stmt = $connection->prepare($query);
    $stmt->bindParam(1, $productId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate average rating
    $totalReviews = count($reviews);
    $averageRating = $totalReviews > 0 ? array_sum(array_column($reviews, 'rating')) / $totalReviews : 0;

    // Number of reviews to show per page
    $reviewsPerPage = 2;

    // Get the current offset
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    // Slice the array to get reviews for the current page
    $reviewsForPage = array_slice($reviews, $offset, $reviewsPerPage);

    ?>
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
                <div class="p-description sm:w-[calc(50%-16px)] w-full sm:pt-10 pt-5">
                    <div class="md:flex-1 sm:px-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2"><?php echo $product['name']; ?></h2>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                            <?php echo $product['description']; ?>
                        </p>
                        <div class="flex sm:flex-row flex-col gap-5 mb-4">
                            <div class="mr-4">
                                <span class="font-bold text-gray-700 dark:text-gray-300">Price:</span>
                                <span class="text-gray-600 dark:text-gray-300">
                                    <?php
                                    if ($product['currency_code'] === 'BDT') {
                                        echo "<strong class=' text-2xl'>৳ </strong>". $product['price'];
                                    } elseif ($product['currency_code'] === 'USD') {
                                        echo '$' . $product['price'];
                                    }
                                    ?>
                                </span>

                            </div>
                            <div>
                               <div class="flex flex-wrap gap-4">
                                    <button type="button" class="px-2.5 py-1.5 bg-pink-100 text-xs text-pink-600 rounded-md flex items-center">
                                    <svg class="w-3 mr-1" fill="currentColor" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z"></path>
                                    </svg>
                                    <?php echo $averageRating; ?>
                                    </button>

                                    <button type="button" class="px-2.5 py-1.5 bg-gray-100 text-xs text-gray-800 rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 mr-1" fill="currentColor" viewBox="0 0 32 32">
                                        <path d="M14.236 21.954h-3.6c-.91 0-1.65-.74-1.65-1.65v-7.201c0-.91.74-1.65 1.65-1.65h3.6a.75.75 0 0 1 .75.75v9.001a.75.75 0 0 1-.75.75zm-3.6-9.001a.15.15 0 0 0-.15.15v7.2a.15.15 0 0 0 .15.151h2.85v-7.501z" data-original="#000000"></path>
                                        <path d="M20.52 21.954h-6.284a.75.75 0 0 1-.75-.75v-9.001c0-.257.132-.495.348-.633.017-.011 1.717-1.118 2.037-3.25.18-1.184 1.118-2.089 2.28-2.201a2.557 2.557 0 0 1 2.17.868c.489.56.71 1.305.609 2.042a9.468 9.468 0 0 1-.678 2.424h.943a2.56 2.56 0 0 1 1.918.862c.483.547.708 1.279.617 2.006l-.675 5.401a2.565 2.565 0 0 1-2.535 2.232zm-5.534-1.5h5.533a1.06 1.06 0 0 0 1.048-.922l.675-5.397a1.046 1.046 0 0 0-1.047-1.182h-2.16a.751.751 0 0 1-.648-1.13 8.147 8.147 0 0 0 1.057-3 1.059 1.059 0 0 0-.254-.852 1.057 1.057 0 0 0-.795-.365c-.577.052-.964.435-1.04.938-.326 2.163-1.71 3.507-2.369 4.036v7.874z" data-original="#000000"></path>
                                        <path d="M4 31.75a.75.75 0 0 1-.612-1.184c1.014-1.428 1.643-2.999 1.869-4.667.032-.241.055-.485.07-.719A14.701 14.701 0 0 1 1.25 15C1.25 6.867 7.867.25 16 .25S30.75 6.867 30.75 15 24.133 29.75 16 29.75a14.57 14.57 0 0 1-5.594-1.101c-2.179 2.045-4.61 2.81-6.281 3.09A.774.774 0 0 1 4 31.75zm12-30C8.694 1.75 2.75 7.694 2.75 15c0 3.52 1.375 6.845 3.872 9.362a.75.75 0 0 1 .217.55c-.01.373-.042.78-.095 1.186A11.715 11.715 0 0 1 5.58 29.83a10.387 10.387 0 0 0 3.898-2.37l.231-.23a.75.75 0 0 1 .84-.153A13.072 13.072 0 0 0 16 28.25c7.306 0 13.25-5.944 13.25-13.25S23.306 1.75 16 1.75z" data-original="#000000"></path>
                                    </svg>
                                    <?php echo $totalReviews; ?> Reviews
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

                <div class="xl:mt-24 sm:mt-12 mt-0 max-w-4xl">
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
                       <div class="max-w-4xl mx-auto mt-8">
                            <h2 class="text-2xl font-bold mb-4">Product Reviews</h2>

                           <!-- Single Review -->
                            <div class="flex reviews-p flex-col mb-4">
                                <?php foreach ($reviewsForPage as $review): ?>
                                    <div class="flex items-center mb-4 pb-4 border-b border-slate-300">
                                        <div class="mr-4">
                                            <div class="flex items-center">
                                                <i class="fa-regular fa-star text-yellow-500"></i>
                                                <span class="text-lg font-bold"><?= $review['rating'] ?></span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-3">
                                            <div>
                                                <p class="text-gray-700 sm:text-lg text-base">
                                                    <?= htmlspecialchars($review['review_text']) ?>
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    - <?= htmlspecialchars($review['customer_name']) ?>
                                                </p>
                                            </div>
                                            <div class=" text-slate-700 text-sm">
                                                Posted date: <?= date('F j, Y, g:i a', strtotime($review['created_at'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($reviews) > $reviewsPerPage) : ?>
                                    <button class="load-more-btn bg-[tomato] text-white py-2 px-4 rounded" onclick="loadMoreReviews()">Load More</button>
                                <?php endif; ?>
                            <div class="mt-8">
                                <h3 class="text-xl font-bold mb-4">Write a Review</h3>
                                <?php
                                // Check for the error parameter in the URL
                                $errorMsg = isset($_GET['error']) ? $_GET['error'] : '';

                                // Display the error message if it exists
                                if (!empty($errorMsg)) {
                                    echo '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        <span class="font-medium"> Alert!</span> ' . htmlspecialchars($errorMsg) . '
                                    </div>';
                                }
                                ?>
                                <form action="../files/reviews.php?id=<?php echo htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
                                    <div class="mb-4">
                                        <!-- Star Rating Input -->
                                        <div class="flex items-center">
                                            <label class="mr-2">Your Rating:</label>
                                            <div class="flex items-center">
                                                <input type="radio" id="star5" name="rating" value="5" class="hidden">
                                                <label for="star1" class="cursor-pointer fill-current star">
                                                    <i class="fa-regular fa-star"></i>
                                                </label>
                                                <input type="radio" id="star4" name="rating" value="4" class="hidden">
                                                <label for="star2" class="cursor-pointer fill-current star">
                                                    <i class="fa-regular fa-star"></i>
                                                </label>
                                                <input type="radio" id="star3" name="rating" value="3" class="hidden">
                                                <label for="star3" class="cursor-pointer fill-current star">
                                                    <i class="fa-regular fa-star"></i>
                                                </label>
                                                <input type="radio" id="star2" name="rating" value="2" class="hidden">
                                                <label for="star4" class="cursor-pointer fill-current star">
                                                    <i class="fa-regular fa-star"></i>
                                                </label>
                                                <input type="radio" id="star1" name="rating" value="1" class="hidden">
                                                <label for="star5" class="cursor-pointer fill-current star">
                                                    <i class="fa-regular fa-star"></i>
                                                </label>
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
    document.addEventListener("DOMContentLoaded", function () {
        const starLabels = document.querySelectorAll('.star');
        const ratingInput = document.querySelector('input[name="rating"]');
        const reviewTextInput = document.getElementById('review_text');
        const form = document.querySelector('form');

        starLabels.forEach(function (label, index) {
            label.addEventListener("click", function () {
                const ratingValue = index + 1;
                ratingInput.value = ratingValue;

                // Log the rating value to the console
                console.log('User clicked on star. Rating value:', ratingValue);

                // Reset all stars
                starLabels.forEach(function (starLabel, i) {
                    if (i < ratingValue) {
                        starLabel.classList.add('text-yellow-500');
                    } else {
                        starLabel.classList.remove('text-yellow-500');
                    }
                });
            });
        });

        form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Gather data before submitting
        const rating = ratingInput.value;
        const reviewText = reviewTextInput.value;

        // Create a FormData object
        const formData = new FormData();
        formData.append('rating', rating);
        formData.append('review_text', reviewText);

        // Use Fetch API to submit the form data
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Review submitted successfully, show success popup
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                }).then(() => {
                    // Redirect to the product page after closing the popup
                    window.location.href = '../products/singleProduct.php?id=<?php echo $product_id; ?>';
                });
            } else {
                // Error submitting review, show error popup
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error,
                });
            }
        })
        .catch(error => {
            console.error('Error submitting review:', error);
            // Handle other errors as needed
        });
    });
    });
    </script>
    <script>
        function htmlspecialchars(str) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return str.replace(/[&<>"']/g, function (m) {
                return map[m];
            });
        }

        function loadMoreReviews() {
            var offset = document.querySelectorAll('.reviews-p .flex.items-center.mb-4').length;

            // Make an AJAX request to fetch additional reviews
            var productId = 5; // Replace with your actual product ID
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse the JSON response
                    var reviewsForPage = JSON.parse(xhr.responseText);

                    // Get the container for reviews
                    var reviewsContainer = document.querySelector('.reviews-p');

                    // Append the new reviews to the existing ones
                    reviewsForPage.forEach(function (review) {
                        var newReview = document.createElement('div');
                        newReview.className = 'flex items-center mb-4 pb-4 border-b border-slate-300';

                        // Your existing HTML code for each review
                        newReview.innerHTML = `
                            <div class="mr-4">
                                <div class="flex items-center">
                                    <i class="fa-regular fa-star text-yellow-500"></i>
                                    <span class="text-lg font-bold">${review['rating']}</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <div>
                                    <p class="text-gray-700 sm:text-lg text-base">
                                        ${htmlspecialchars(review['review_text'])}
                                    </p>
                                    <p class="text-gray-500 text-sm">
                                        - ${htmlspecialchars(review['customer_name'])}
                                    </p>
                                </div>
                                <div class=" text-slate-700 text-sm">
                                    Posted date: ${new Date(review['created_at']).toLocaleString()}
                                </div>
                            </div>
                        `;

                        reviewsContainer.appendChild(newReview);
                    });

                    // Hide the "Load More" button if there are no more reviews
                    if (offset + <?php echo $reviewsPerPage; ?> >= <?php echo count($reviews); ?>) {
                        document.querySelector('.load-more-btn').style.display = 'none';
                    }
                }
            };

            // Send the AJAX request with productId
            xhr.open('GET', `../files/loadmorereview.php?offset=${offset}&productId=${productId}`, true);
            xhr.send();
        }

    </script>

</body>

</html>
