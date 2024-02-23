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
                <div class="product w-[calc(50%-16px)]">
<div id="mainProductImageContainer" class="h-[460px] rounded-lg bg-gray-300 dark:bg-gray-700 mb-4">
    <img id="mainProductImage" class="w-full h-full object-cover" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $product['product_photo']; ?>" alt="Product Image">
</div>
                    <div class="variation-images">
                        <ul class="flex gap-4 items-center">
                            <?php foreach ($variations as $variation) : ?>
                                <li class="w-48 h-48 overflow-auto cursor-pointer" onclick="changeMainProductImage('<?php echo $variation['image_path']; ?>')">
                                    <img class="w-full h-full object-cover" src="http://localhost/reactcrud/backend/auth/assets/products/<?php echo $variation['image_path']; ?>" alt="Variation Image">
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="p-description w-[calc(50%-16px)]">
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
<?php if (!empty($variations)) : ?>
    <!-- Display the color variation as a clickable element -->
    <div class="mt-4">
        <label class="font-bold text-gray-700 dark:text-gray-300">Select Color:</label>
        <div class="flex mt-2">
            <?php foreach ($variations as $variation) : ?>
                <div class="mr-3">
                    <span class="w-8 h-8 block rounded-full cursor-pointer" style="background-color: <?php echo $variation['color']; ?>" onclick="changeVariationImage('<?php echo $variation['image_path']; ?>', '<?php echo $variation['color']; ?>')"></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Select dropdown for SIM -->
    <div class="mt-4">
        <label class="font-bold text-gray-700 dark:text-gray-300">Select SIM:</label>
        <select id="simSelect" class="ml-2 border rounded-md">
            <?php foreach ($variations as $variation) : ?>
                <option value="<?php echo $variation['sim']; ?>"><?php echo $variation['sim']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Select dropdown for Storage -->
    <div class="mt-4">
        <label class="font-bold text-gray-700 dark:text-gray-300">Select Storage:</label>
        <select id="storageSelect" class="ml-2 border rounded-md">
            <?php foreach ($variations as $variation) : ?>
                <option value="<?php echo $variation['storage']; ?>"><?php echo $variation['storage']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Select dropdown for Type -->
    <div class="mt-4">
        <label class="font-bold text-gray-700 dark:text-gray-300">Select Type:</label>
        <select id="typeSelect" class="ml-2 border rounded-md">
            <?php foreach ($variations as $variation) : ?>
                <option value="<?php echo $variation['type']; ?>"><?php echo $variation['type']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

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
                        <div class="w-1/2 px-2">
                            <button class="w-full bg-gray-900 dark:bg-gray-600 text-white py-2 px-4 rounded-full font-bold hover:bg-gray-800 dark:hover:bg-gray-700">Add to Cart</button>
                        </div>
                        <div class="w-1/2 px-2">
                            <button class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white py-2 px-4 rounded-full font-bold hover:bg-gray-300 dark:hover:bg-gray-600">Add to Wishlist</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../components/footer/footer.php'; ?>
<script>
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
