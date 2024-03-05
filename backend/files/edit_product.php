<?php
// Include config file
require_once "../auth/db-connection/config.php";

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../../index.php");
    exit;
}

// Check if product ID is provided in the URL
if (!isset($_GET['up_id']) || empty($_GET['up_id'])) {
    header("location: products.php"); // Redirect to the products page if no ID is provided
    exit;
}

// Fetch product details from the database based on the provided ID
$productId = $_GET['up_id'];
$sql = "SELECT * FROM products WHERE id = :productId";
$stmt = $connection->prepare($sql);
$stmt->bindParam(":productId", $productId, PDO::PARAM_INT);

if ($stmt->execute()) {
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        // Redirect to the products page if the product is not found
        header("location: products.php");
        exit;
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
    exit;
}

// Fetch categories from the database
$sql = "SELECT * FROM categories";
$stmt = $connection->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to get category name based on category ID
function getCategoryName($categoryId) {
    global $connection;
    $sql = "SELECT name FROM categories WHERE id = :category_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(":category_id", $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['name'] : 'Unknown Category';
}


// Function to format the price
function formatPrice($price, $currencyCode) {
    return $currencyCode . ' ' . number_format($price, 2);
}

// Fetch existing variations for the product
$sql = "SELECT * FROM variations WHERE product_id = :productId";
$stmt = $connection->prepare($sql);
$stmt->bindParam(":productId", $productId, PDO::PARAM_INT);

if ($stmt->execute()) {
    $existingVariations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Oops! Something went wrong while fetching variations. Please try again later.";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body style="background:#f7f7f7;">
    <main>
        <div class="app-wrapper">
            <div class="app-sidebar">
                <div class="side-header flex pr-3">
                    <div class="logo flex">
                        <img src="images/logo.webp" alt="logo">
                    </div>
                    <div id="des-nav" class="wrapper-n-icon">
                        <i class="fa-solid fa-bars"></i>
                        <i class="fa-solid fa-xmark close"></i>
                    </div>
                </div>
                <div class="sidebard-nav">
                    <ul>
                        <li class="">
                            <a href="dashboard.php">
                                <i class="fa-solid fa-table-columns"></i>
                                <span class="block">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="">
                            <a href="categories.php">
                                <i class="fa-solid fa-list"></i>
                                <span class="block">Categories</span>
                            </a>
                        </li>

                        <li class="active">
                            <a href="products.php">
                               <i class="fa-solid fa-cart-flatbed-suitcase"></i>
                                <span class="block">Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="orders.php">
                                  <i class="fa-solid fa-cart-shopping"></i>
                                <span class="block">Order</span>
                            </a>
                        </li>
                        <li>
                            <a href="customers.php">
                                <i class="fa-solid fa-user-group"></i>
                                <span class="block">Customers</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="fa-solid fa-chart-simple"></i>
                                <span class="block">Statistics</span>
                            </a>
                        </li>

                        <li>
                            <a href="reviews.php">
                                <i class="fa-solid fa-comments"></i>
                                <span class="block">Reviews</span>
                            </a>
                        </li>

                        <li>
                            <a href="">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                                <span class="block">Transanctions</span>
                            </a>
                        </li>

                        <li>
                            <a href="">
                                <i class="fa-solid fa-briefcase"></i>
                                <span class="block">Hot Offers</span>
                            </a>
                        </li>

                         <li class="devided-nav">
                            <a href="appearance.php">
                                <i class="fa-solid fa-tag"></i>
                                <span class="block">Appearances</span>
                            </a>
                        </li>

                         <li>
                            <a href="settings.php">
                                <i class="fa-solid fa-gear"></i>
                                <span class="block">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-body">
                <div class="app-sidebar-mb">
                    <div class="nav-mb-icon">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                </div>
                <div class="user flex-end">
                    <div class="search">
                        <form class="d-flex gap-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="account">
                        <!-- Notifications -->
                        <div class="notifications">
                            <i class="fa-regular fa-bell"></i>
                        </div>
                        <!-- User  -->
                        <div class="wrap-u" onclick="toggleUserOptions()">
                            <div class="user-pro flex">
                                <?php if (isset($_SESSION["profile_photo"])) : ?>
                                    <img src="<?php echo $_SESSION["profile_photo"]; ?>" alt="Profile Photo">
                                <?php else : ?>
                                    <!-- Provide a default image or alternative content -->
                                    <img src="default_profile_photo.jpg" alt="Default Profile Photo">
                                <?php endif; ?>
                            </div>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <!-- User Dropdown -->
                        <div id="userOptions" class="u-pro-options">
                            <div class="flex-col w-full">
                                <div class="u-name">
                                    <div class="user-pro flex">
                                        <?php if (isset($_SESSION["profile_photo"])) : ?>
                                            <img src="<?php echo $_SESSION["profile_photo"]; ?>" alt="Profile Photo">
                                        <?php else : ?>
                                            <!-- Provide a default image or alternative content -->
                                            <img src="default_profile_photo.jpg" alt="Default Profile Photo">
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex-col">
                                        <span class="block"><?php echo strtoupper(htmlspecialchars($_SESSION["username"])); ?></span>
                                        <span class="block"> Super Admin</span>
                                    </div>
                                </div>

                                <ul class="pro-menu">
                                    <li><a href="">Profile</a></li>
                                    <li><a href="admin-settings.php">Admin Settings</a></li>
                                    <li><a href="../auth/backend-assets/logout.php" class="">Log out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-container">
                    <div class="main">
                    <div class="flex">
                        <h1 class="page-heading"> Edit Product </h1>
                    </div>

                    <!-- Display the product form for editing -->
                    <form id="editProductForm" action="../auth/backend-assets/product/update_product.php" method="post" enctype="multipart/form-data">
                        <!-- Hidden field for product ID -->
                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'] ?? ''); ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editProductName" class="form-label">Product Name:</label>
                                    <input type="text" class="form-control" id="editProductName" name="editProductName" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editProductDescription" class="form-label">Product Description:</label>
                                    <input type="text" class="form-control" id="editProductDescription" name="editProductDescription" value="<?php echo htmlspecialchars($product['description'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editProductPrice" class="form-label">Product Price:</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </span>
                                        <input type="text" class="form-control" id="editProductPrice" name="editProductPrice" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editCurrency" class="form-label">Currency:</label>
                                    <select class="form-select" id="editCurrency" name="editProductCurrency" required>
                                        <option value="BDT" <?php echo ($product['currency_code'] == 'BDT') ? 'selected' : ''; ?>>BDT (Bangladeshi Taka)</option>
                                        <option value="USD" <?php echo ($product['currency_code'] == 'USD') ? 'selected' : ''; ?>>USD (United States Dollar)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editProductCategory" class="form-label">Product Category:</label>
                                    <select class="form-select" id="editProductCategory" name="editProductCategory" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editProductStock" class="form-label">Stock Quantity:</label>
                                    <input type="text" class="form-control" id="editProductStock" name="editProductStock" value="<?php echo htmlspecialchars($product['stock_quantity'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="editProductPhoto" class="form-label">Product Photo:</label>
                                <input type="file" class="form-control-file form-control" id="editProductPhoto" name="editProductPhoto" accept="image/*">
                                <small class="form-text text-muted">Upload a new photo or leave it blank to keep the current photo.</small>
                            </div>
                        </div>

                        <!-- Variations Section -->
                        <hr class="my-4">
                        <h3>Variations</h3>

                        <!-- Fetch existing variations for the product -->
                        <?php if (!empty($existingVariations)): ?>
                            <div class="exis-var mb-4">
                                <h4>Existing Variations</h4>
                                <ul class="list-group">
                                    <?php foreach ($existingVariations as $variation): ?>
                                        <li class="list-group-item">
                                            Variation ID: <?php echo $variation['id']; ?>
                                            - Sim:
                                            <select name="edit_sim[<?php echo $variation['id']; ?>]">
                                                <option value="">Select</option>
                                                <option value="dual">Dual </option>
                                                <option value="eSim" <?php if ($variation['sim'] === 'eSim') echo 'selected'; ?>>eSim</option>
                                                <!-- Add other SIM options as needed -->
                                            </select>
                                            - Storage:
                                            <select name="edit_storage[<?php echo $variation['id']; ?>]">
                                                <option value="">Select</option>
                                                <option value="128mb" <?php if ($variation['storage'] === '128mb') echo 'selected'; ?>>128mb</option>
                                                <option value="512mb"> 512MB </option>
                                                <option value="1gb"> 1 GB </option>
                                                <option value="128gb"> 128 GB </option>
                                            </select>
                                            - Color(s): <?php echo htmlspecialchars($variation['color']); ?>
                                            - Image Path(s): <?php echo htmlspecialchars($variation['image_path']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Allow user to input new variations -->
                        <div class="variation-form">
                            <h4>New Variation</h4>

                            <div class="form-group">
                                <label for="sim">Sim:</label>
                                <select class="form-control" name="sim[]">
                                    <option value="">Select</option>
                                    <option value="dual">Dual</option>
                                    <option value="eSim">eSim</option>
                                    <!-- Add other SIM options as needed -->
                                </select>
                            </div>

                            <div class="form-group mt-2">
                                <label for="storage">Storage:</label>
                                <select class="form-control" name="storage[]">
                                    <option value="">Select</option>
                                    <option value="128mb">128mb</option>
                                    <option value="512mb"> 512MB </option>
                                    <option value="1gb"> 1 GB </option>
                                    <option value="128gb"> 128 GB </option>
                                </select>
                            </div>

                            <!-- Color Fields -->
                            <div class="color-fields mt-2">
                                <label for="colors">Color(s):</label>
                                <input type="text" class="form-control" name="colors[0][]">
                            </div>

                            <!-- Image Fields -->
                            <div class="image-fields mb-3">
                                <div class="form-group">
                                    <label for="images" class="form-label">Product Photo:</label>
                                    <input type="file" name="images[0][]" class="form-control-file form-control" accept="image/*">
                                </div>
                            </div>

                            <!-- Additional Color and Image Fields (JavaScript will dynamically add more) -->
                            <div id="additionalFieldsContainer"></div>

                            <button type="button" class="btn btn-primary mt-3" onclick="addColorAndImageFields()">
                                Add Multiple Color and Image
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-5">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap JS (load Bootstrap 5 first) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-rqzjrxH3CSuZ5Ff2LPz1Lh8Qf5OUd9lZAe73FN19SYBWy4MEEI2Ml4hj8Iva5Q8" crossorigin="anonymous"></script>

<!-- jQuery (load jQuery before Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBn" crossorigin="anonymous"></script>

<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-oj2zXrMpOq2mgXXsL0l5f1d0gfn5vi3e5P9GoaF2f7Q/cAsqNquCDZg2VYNJfjlz" crossorigin="anonymous"></script>

<!-- Your custom JavaScript -->
<script>
    var fieldIndex = 1;

    function addColorAndImageFields() {
        var container = document.getElementById('additionalFieldsContainer');

        // Color Fields
        var colorFields = document.createElement('div');
        colorFields.className = 'color-fields mt-2';
        colorFields.innerHTML = '<label for="colors">Color(s):</label>' +
            '<input type="text" class="form-control" name="colors[' + fieldIndex + '][]">';

        // Image Fields
        var imageFields = document.createElement('div');
        imageFields.className = 'image-fields mb-3';
        imageFields.innerHTML = '<div class="form-group">' +
            '<label for="images" class="form-label">Product Photo:</label>' +
            '<input type="file" name="images[' + fieldIndex + '][]" class="form-control-file form-control" accept="image/*">' +
            '</div>';

        // Append new fields to the container
        container.appendChild(colorFields);
        container.appendChild(imageFields);

        fieldIndex++;
    }

    // Add an event listener to the form to submit it when any field is edited
    document.getElementById('editProductForm').addEventListener('input', function () {
        // Set a flag to indicate that the form has been edited
        this.dataset.edited = true;
    });

    // Override the form submission to check if it's edited before submitting
    document.getElementById('editProductForm').addEventListener('submit', function (event) {
        // If the form is not edited, prevent submission
        if (!this.dataset.edited) {
            alert('Please edit at least one field before submitting.');
            event.preventDefault();
        }
    });


    function toggleUserOptions() {
        var options = document.getElementById("userOptions");
        options.style.display = (options.style.display === 'flex') ? 'none' : 'flex';
    }

    function updateCurrencySymbol() {
    console.log("Updating currency symbol...");
    var currencySelect = document.getElementById("editCurrency");
    var selectedCurrency = currencySelect.value;

    console.log("Selected Currency:", selectedCurrency);

    // Update the currency symbol based on the selected currency
    // You can customize this part based on your currency symbols
    var currencySymbolSpan = document.querySelector('.input-group-prepend span');

    switch (selectedCurrency) {
        case "BDT":
            currencySymbolSpan.textContent = "৳";
            break;
        case "USD":
            currencySymbolSpan.textContent = "$";
            break;
        // Add more cases for other currencies as needed
        default:
            currencySymbolSpan.textContent = "";
            break;
    }
}

    // Attach the updateCurrencySymbol function to the change event of the currency select
    document.getElementById("editCurrency").addEventListener("change", updateCurrencySymbol);

    // Call the function initially to set the default currency symbol
    updateCurrencySymbol();


    document.addEventListener('DOMContentLoaded', function () {
        const wrapperIcon = document.querySelector('.app-sidebar-mb');
        const appWrapperS = document.querySelector('.app-wrapper');
        const deskNav = document.getElementById("des-nav");

        wrapperIcon.addEventListener('click', function () {
            appWrapperS.classList.toggle('show-sidebar');
        });
        deskNav.addEventListener('click', function () {
            appWrapperS.classList.remove('show-sidebar');
        });
    });
</script>

</body>
</html>