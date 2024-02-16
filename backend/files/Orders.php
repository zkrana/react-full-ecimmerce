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
    <title>Orders</title>
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
                        <li class="active">
                            <a href="orders.php">
                                  <i class="fa-solid fa-cart-shopping"></i>
                                <span class="block">Order</span>
                            </a>
                        </li>
                        <li class="">
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

                <?php
                // Function to get order status name
                function getOrderStatusName($statusId) {
                    // You can replace this with a database query to get the status name based on the status ID
                    $statusNames = [
                        1 => 'Pending',
                        2 => 'Payment Received',
                        3 => 'Processing',
                        4 => 'Shipped',
                        5 => 'Cancel',
                    ];

                    return isset($statusNames[$statusId]) ? $statusNames[$statusId] : 'Unknown';
                }

                // Function to decrypt email
                function xorDecrypt($input, $key) {
                    // Decode the base64-encoded input
                    $decodedInput = base64_decode($input);

                    // XOR decryption
                    $decrypted = '';
                    $keyLength = strlen($key);

                    for ($i = 0; $i < strlen($decodedInput); $i++) {
                        $decrypted .= $decodedInput[$i] ^ $key[$i % $keyLength];
                    }

                    return $decrypted;
                }


                // Determine whether the select box should be disabled
                // $disabledAttribute = ($orderStatus == 5) ? 'disabled' : '';
                // Fetch orders along with customer details from the database based on date filter
                if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                    $start_date = $_GET['start_date'];
                    $end_date = $_GET['end_date'];

                    // Validate and sanitize the input dates (you should enhance this based on your requirements)
                    $start_date = filter_var($start_date, FILTER_SANITIZE_STRING);
                    $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);

                    // Debugging: Print start_date, end_date, and the modified SQL query
                    // echo "Debug: Start Date: $start_date, End Date: $end_date<br>";

                    // Adjust the SQL query to fetch orders within the specified date range
                    $sql = "SELECT
                                orders.id AS order_id,
                                customers.id AS customer_id,
                                customers.ip_address,
                                customers.username,
                                customers.email,
                                customers.photo,
                                customers.request_time,
                                orders.quantity AS quantity,
                                orders.order_status_id AS order_status,
                                payments.payment_amount,
                                payments.payment_method
                            FROM customers
                            LEFT JOIN orders ON customers.id = orders.user_id
                            LEFT JOIN payments ON orders.id = payments.order_id
                            WHERE orders.order_date BETWEEN :start_date AND DATE_ADD(:end_date, INTERVAL 1 DAY)
                            ORDER BY orders.id DESC";

                    // Debugging: Print the modified SQL query
                    // echo "Debug: SQL Query: $sql<br>";

                    $stmt = $connection->prepare($sql);
                    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
                    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
                    $stmt->execute();
                    // After executing the query
                // echo "Debug: Number of Rows: " . $stmt->rowCount() . "<br>";

                } else {
                    // If no date filter is applied, fetch all orders
                    $sql = "SELECT
                                orders.id AS order_id,
                                customers.id AS customer_id,
                                customers.ip_address,
                                customers.username,
                                customers.email,
                                customers.photo,
                                customers.request_time,
                                orders.quantity AS quantity,
                                orders.order_status_id AS order_status,
                                payments.payment_amount,
                                payments.payment_method
                            FROM customers
                            LEFT JOIN orders ON customers.id = orders.user_id
                            LEFT JOIN payments ON orders.id = payments.order_id
                            ORDER BY orders.id DESC";

                    // Debugging: Print the SQL query
                    // echo "Debug: SQL Query: $sql<br>";

                    $stmt = $connection->query($sql);
                }

                // Display the orders
                ?>


                <div class="h-container">
                    <?php
                    // Check for success query parameter
                    if (isset($_GET['success'])) {
                        $successMsg = $_GET['success'];
                        echo '<div id="error" class="max-w-400px alert alert-success mt-2" role="alert">' . $successMsg . '</div>';
                    }

                    // Display error message if available
                    if (isset($_GET['error'])) {
                        $errorMsg = $_GET['error']; // You should set an appropriate error message here
                        echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
                    }
                    ?>

                    <div class="main">
                        <div class="order-filter">
                            <div class="heading">
                                <h1 class="page-heading">Order's</h1>
                                <p>All orders data</p>
                            </div>
                            <div class="filter-wrapper">
                                <form method="get" action="" class="row g-3">
                                    <div class="col-md-5">
                                        <label for="start_date" class="form-label">Start Date:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="end_date" class="form-label">End Date:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <div class="order">
                         <?php
                        if ($stmt->rowCount() > 0) {
                        echo '<table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#Order ID</th>
                                        <th scope="col">IP Address</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Product Count</th>
                                        <th scope="col">Request Time</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Payment Amount</th>
                                        <th scope="col">Payment Method</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $decryptedEmail = xorDecrypt($row["email"], 'shTYTS,os(**0te455432%3sgks$#SG');

                            // Set the order status based on the database result
                            $orderStatus = $row["order_status"];

                            // Determine the background color based on the order status
                            switch ($orderStatus) {
                                case 1:
                                    $bgColor = 'background-color: #ffffcc;'; // Light yellow
                                    break;
                                case 2:
                                    $bgColor = 'background-color: #ccffcc;'; // Light green
                                    break;
                                case 3:
                                    $bgColor = 'background-color: #c2f0c2;'; // Light pastel green
                                    break;
                                case 4:
                                    $bgColor = 'background-color: #d9ffd9;'; // Very light green
                                    $statusText = 'Complete';
                                    break;
                                case 5:
                                    $bgColor = 'background-color: #ffcccc;'; // Light red for Cancel
                                    $statusText = 'Cancel';
                                    break;
                                default:
                                    $bgColor = '';
                                    $statusText = 'Unknown';
                            }

                            // If the order status is "Shipped," set statusText to "Complete"
                            if ($orderStatus == 4) {
                                $statusText = 'Complete';
                            }

                            $disabledAttribute = ($orderStatus == 4 || $orderStatus == 5) ? 'disabled' : '';

                            echo '<tr style="' . $bgColor . '">
                                    <th scope="row">' . $row["order_id"] . '</th>
                                    <td>' . $row["ip_address"] . '</td>
                                    <td>' . $row["username"] . '</td>
                                    <td>' . $decryptedEmail . '</td>
                                    <td>' . (isset($row['quantity']) ? $row['quantity'] : 'N/A') . '</td>
                                    <td>' . $row["request_time"] . '</td>
                                    <td>
                                        <select class="form-select order-status-dropdown" aria-label="Order Status" data-order-id="' . $row["order_id"] . '" ' . $disabledAttribute . ' style="' . $bgColor . ';">
                                            <option value="1" ' . ($orderStatus == 1 ? 'selected' : '') . '>Pending</option>
                                            <option value="2" ' . ($orderStatus == 2 ? 'selected' : '') . '>Payment Received</option>
                                            <option value="3" ' . ($orderStatus == 3 ? 'selected' : '') . '>Processing</option>
                                            <option value="4" ' . ($orderStatus == 4 ? 'selected' : '') . '>' . $statusText . '</option>
                                            <option value="5" ' . ($orderStatus == 5 ? 'selected' : '') . '>Cancel</option>
                                        </select>
                                    </td>
                                    <td>' . $row["payment_amount"] . '</td>
                                    <td>' . $row["payment_method"] . '</td>
                                    <td>
                                        <a type="button" href="../files/customer/view_orders.php?order_id=' . $row["order_id"] . '" class="btn btn-primary view-orders-btn">View Orders</a>
                                    </td>
                                </tr>';
                        }
                            echo '</tbody></table>';
                        } else {
                            echo "No orders found.";
                        }
                        ?>
                    
                    </div>
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
                    var orderId = this.dataset.orderId; // Change 'userId' to 'orderId' for consistency
                    var newOrderStatus = this.value; // Get the selected order status

                    console.log('Order ID:', orderId);
                    console.log('New Order Status:', newOrderStatus);

                    // Send an AJAX request to update_order_status.php
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../auth/backend-assets/update_order_status.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            console.log('Response:', xhr.responseText);
                            if (xhr.status === 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    // Update successful, you can handle success feedback if needed
                                    console.log('Order status updated successfully.');
                                } else {
                                    // Handle error or display error message
                                    console.error('Error updating order status: ' + response.message);
                                }
                            } else {
                                console.error('HTTP request failed with status ' + xhr.status);
                            }
                        }
                    };
                    xhr.send('order_id=' + orderId + '&new_order_status=' + newOrderStatus);
                });
            });
        });
    </script>
</body>
</html>
