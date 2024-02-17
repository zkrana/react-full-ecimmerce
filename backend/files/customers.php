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
    <title>Customer</title>
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
                        <li class="active">
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
                            <a href="">
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
                    <h1 class="page-heading">Customers's</h1>
                    <p>All customers data</p>
                    <div class="overflow-auto">
                        <?php
                        // Include config file
                        require_once "../auth/db-connection/config.php";

                        // Fetch all customers from the database
                        $sql = "SELECT * FROM customers";
                        $result = $connection->query($sql);

                        // Check if there are any rows
                        if ($result->rowCount() > 0) {
                            echo '<table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">IP Address</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Photo</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Request Time</th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Billing Address</th>
                                            <th scope="col">City</th>
                                            <th scope="col">State</th>
                                            <th scope="col">Postal Code</th>
                                            <th scope="col">Country</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                            // Output data of each row
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $decryptedEmail = xorDecrypt($row["email"], 'shTYTS,os(**0te455432%3sgks$#SG');

                                echo '<tr>
                                        <th scope="row">' . $row["id"] . '</th>
                                        <td>' . $row["ip_address"] . '</td>
                                        <td>' . $row["username"] . '</td>
                                        <td>' . $decryptedEmail . '</td>
                                        <td><img src="../auth/assets/user-profile/' . $row["username"] . '/' . $row["photo"] . '" alt="' . $row["username"] . '" /></td>
                                        <td>' . $row["last_name"] . '</td>
                                        <td>' . $row["request_time"] . '</td>
                                        <td>' . $row["first_name"] . '</td>
                                        <td>' . $row["billing_address"] . '</td>
                                        <td>' . $row["city"] . '</td>
                                        <td>' . $row["state"] . '</td>
                                        <td>' . $row["postal_code"] . '</td>
                                        <td>' . $row["country"] . '</td>
                                        <td>' . $row["phone_number"] . '</td>
                                        <td><a class="btn btn-danger" href="#?id='. $row["id"].'">Delete</a></td>

                                    </tr>';
                            }

                            echo '</tbody></table>';
                        } else {
                            echo "No customers found.";
                        }

                        // Close the database connection
                        $connection = null;

                        // Decrypt function
                        function xorDecrypt($input, $key)
                        {
                            $decodedInput = base64_decode($input);
                            $decrypted = '';
                            $keyLength = strlen($key);

                            for ($i = 0; $i < strlen($decodedInput); $i++) {
                                $decrypted .= $decodedInput[$i] ^ $key[$i % $keyLength];
                            }

                            return $decrypted;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </main>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function toggleUserOptions() {
            var options = document.getElementById("userOptions");
            options.style.display = (options.style.display === 'flex') ? 'none' : 'flex';
        }
            // script.js
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

         function submitForm() {
        // Trigger form submission
        document.getElementById("bannerUploadForm").submit();
    }

    </script>
    <script src="js/main.js"></script>
        <script>
        // Enable the dropdown on click
        document.querySelectorAll('.order-status-dropdown').forEach(function (dropdown) {
            dropdown.addEventListener('click', function () {
                this.removeAttribute('disabled');
            });
        });

          document.addEventListener('DOMContentLoaded', function () {
        // Enable the dropdown on change
        document.querySelectorAll('.order-status-dropdown').forEach(function (dropdown) {
            dropdown.addEventListener('change', function () {
                var userId = this.dataset.userId; // Get the user ID from the data attribute
                var newOrderStatus = this.value; // Get the selected order status

                // Send an AJAX request to update_order_status.php
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../auth/backend-assets/update_order_status.php ', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Update successful, you can handle success feedback if needed
                            console.log('Order status updated successfully.');
                        } else {
                            // Handle error or display error message
                            console.error('Error updating order status: ' + response.message);
                        }
                    }
                };
                xhr.send('user_id=' + userId + '&new_order_status=' + newOrderStatus);
            });
        });
    });
    </script>
</body>
</html>
