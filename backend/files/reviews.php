<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../../index.php");
    exit;
}

// Include config file
require_once "../auth/db-connection/config.php";

// Fetch additional user information from the database using the user ID
$userId = $_SESSION["id"];
$sql = "SELECT profile_photo FROM admin_users WHERE id = :userId";

if ($stmt = $connection->prepare($sql)) {
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $stmt->bindColumn("profile_photo", $profilePhoto);
        if ($stmt->fetch()) {
            // User profile photo found, update the session
            $_SESSION["profile_photo"] = $profilePhoto;
        } else {
            // User not found or profile photo not set, you can handle this case
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    unset($stmt); // Close statement
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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

                        <li>
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

                        <li class="active">
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

                         <li class="devided-nav ">
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
                            <h1 class="page-heading"> Reviews </h1>
                           <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
                        </div>
                        <?php
                        // Define the number of reviews per page
                        $reviewsPerPage = 20;
                        // SQL query to get the total number of rows
                        $totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM product_reviews";
                        $totalRowsResult = $connection->query($totalRowsQuery);

                        if ($totalRowsResult !== false) {
                            $totalRows = $totalRowsResult->fetch(PDO::FETCH_ASSOC)['total_rows'];

                            // Get the current page number from the URL, default to page 1
                            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                            // Calculate the offset based on the current page
                            $offset = ($page - 1) * $reviewsPerPage;

                            // SQL query to select reviews with pagination and join with products table
                            $sql = "SELECT pr.*, p.name AS product_name, p.product_photo
                                    FROM product_reviews pr
                                    JOIN products p ON pr.product_id = p.id
                                    LIMIT $offset, $reviewsPerPage";
                            $result = $connection->query($sql);

                            // Fetch and display reviews
                            if ($result !== false) {
                                // Check the number of rows returned by the query
                                $rowCount = $result->rowCount();

                                if ($rowCount > 0) {
                                    echo '<div class="mt-4">';
                                    echo '<table class="reviewTable min-w-full bg-white border border-gray-300">';
                                    echo '<thead>';
                                    echo '<tr>';
                                    echo '<th class="py-2 px-4 border-b">ID</th>';
                                    echo '<th class="py-2 px-4 border-b">Product ID</th>';
                                    echo '<th class="py-2 px-4 border-b">Product Name</th>';
                                    echo '<th class="py-2 px-4 border-b">Product Photo</th>';
                                    echo '<th class="py-2 px-4 border-b">Customer ID</th>';
                                    echo '<th class="py-2 px-4 border-b">Rating</th>';
                                    echo '<th class="py-2 px-4 border-b">Review Text</th>';
                                    echo '<th class="py-2 px-4 border-b">Created At</th>';
                                    echo '<th class="py-2 px-4 border-b">Action</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';

                                    // Loop through the results and display each row
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["id"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["product_id"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["product_name"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b ' style='width: 120px; height:90px;'><img src='../auth/assets/products/" . $row["product_photo"] . "' alt='Product Photo' class='w-12 h-12' style=' object-fit: contain; width: 100%; height: 100%'></td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["customer_id"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["rating"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["review_text"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b'>" . $row["created_at"] . "</td>";
                                        echo "<td class='py-2 px-4 border-b flex gap-2'>";
                                        echo "<select class='reviewTableoptions' data-review-id='" . $row["id"] . "'>";
                                        echo "<option value='pending' " . ($row["reviewStatus"] == "pending" ? "selected" : "") . ">pending</option>";
                                        echo "<option value='approved' " . ($row["reviewStatus"] == "approved" ? "selected" : "") . ">approved</option>";
                                        echo "<option value='spam' " . ($row["reviewStatus"] == "spam" ? "selected" : "") . ">spam</option>";
                                        echo "</select>";
                                        echo "|";
                                        echo "<button class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>";
                                        echo "Delete";
                                        echo "</button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '</div>';

                                    // Pagination links
                                    $totalPages = ceil($totalRows / $reviewsPerPage);

                                    echo "<div class='mt-4 flex-center justify-center'>";
                                    echo "<ul class='pagination'>";
                                    
                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        $activeClass = $i === $page ? 'bg-blue-500 text-black' : 'bg-white text-blue-500';
                                        echo "<li class='page-item mx-1'><a class='page-link py-2 px-4 rounded-full $activeClass' href='?page=$i'>$i</a></li>";
                                    }

                                    echo "</ul>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='py-2 px-4 text-center'>No reviews found</div>";
                                }
                            } else {
                                // Handle the case where the query fails
                                echo "Error: " . $connection->errorInfo()[2];
                            }
                        } else {
                            // Handle the case where the total rows query fails
                            echo "Error: " . $connection->errorInfo()[2];
                        }

                        // Close the connection by setting it to null
                        $connection = null;
                        ?>

                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap JS (you can use the CDN or download the file and host it locally) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleUserOptions() {
            var options = document.getElementById("userOptions");
            options.style.display = (options.style.display === 'flex') ? 'none' : 'flex';
        }
                document.addEventListener('DOMContentLoaded', function () {
            const wrapperIcon = document.querySelector('.app-sidebar-mb');
            const appWrapperS = document.querySelector('.app-wrapper');
            const deskNav =  document.getElementById("des-nav");

        wrapperIcon.addEventListener('click', function () {
                appWrapperS.classList.toggle('show-sidebar');
            });
        deskNav.addEventListener('click', function () {
                appWrapperS.classList.remove('show-sidebar');
            });
        });
    </script>
    <script src="js/main.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Attach event listener to all reviewTableoptions elements
        var reviewOptions = document.querySelectorAll(".reviewTableoptions");
        reviewOptions.forEach(function (option) {
            option.addEventListener("change", function () {
                // Get the review id and selected value
                var reviewId = this.getAttribute("data-review-id");
                var newStatus = this.value;

                // Send asynchronous request to update the review status
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Handle response if needed
                        console.log(xhr.responseText);
                    }
                };
                xhr.open("POST", "../auth/backend-assets/update_review_status.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("id=" + reviewId + "&status=" + newStatus);
            });
        });
    });
</script>
</body>
</html>
