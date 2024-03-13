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
    <title>Dashbaord</title>
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
                        <li class="active">
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
                    <?php
                    // Assuming you have already executed a query to fetch the count of new users
                    $new_users_count = 0; // Initialize the variable
                    // Fetch data for new customers using 'request_time' column
                    $sql_new_customers = "SELECT * FROM customers WHERE request_time >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
                    $stmt_new_customers = $connection->prepare($sql_new_customers);
                    $stmt_new_customers->execute();
                    $new_customers = $stmt_new_customers->fetchAll(PDO::FETCH_ASSOC);

                    // Example: New orders
                    $sql_new_orders = "SELECT COUNT(*) AS count FROM orders WHERE order_status_id = 1"; // Assuming 1 is the status for new orders
                    $stmt_new_orders = $connection->prepare($sql_new_orders);
                    $stmt_new_orders->execute();
                    $new_orders_count = $stmt_new_orders->fetch(PDO::FETCH_ASSOC)['count'];

                    // Example: New payments
                    // Example: New payments
                    $sql_new_payments = "SELECT COUNT(*) AS count FROM payments";

                    $stmt_new_payments = $connection->prepare($sql_new_payments);
                    $stmt_new_payments->execute();
                    $new_payments_count = $stmt_new_payments->fetch(PDO::FETCH_ASSOC)['count'];

                    // Example: New reviews
                    $sql_new_reviews = "SELECT COUNT(*) AS count FROM product_reviews WHERE reviewStatus IS NULL";
                    $stmt_new_reviews = $connection->prepare($sql_new_reviews);
                    $stmt_new_reviews->execute();
                    $new_reviews_count = $stmt_new_reviews->fetch(PDO::FETCH_ASSOC)['count'];

                    // Example: New subscriptions
                    $sql_new_subscriptions = "SELECT COUNT(*) AS count FROM subscribers";
                    $stmt_new_subscriptions = $connection->prepare($sql_new_subscriptions);
                    $stmt_new_subscriptions->execute();
                    $new_subscriptions_count = $stmt_new_subscriptions->fetch(PDO::FETCH_ASSOC)['count'];



                    // Calculate total notifications count
                    $total_notifications = $new_users_count + $new_orders_count + $new_payments_count + $new_reviews_count + $new_subscriptions_count;

                    // Determine if there are any new notifications to display red dot
                    $has_new_notifications = $total_notifications > 0;
                    ?>


                    <!-- Notifications -->
                    <div class="notifications" id="notificationsDropdown">
                        <i class="far fa-bell"></i>
                        <?php if ($has_new_notifications): ?>
                            <span class="notification-dot"></span>
                        <?php endif; ?>
                    </div>

                    <!-- Notifications Menu -->
                    <div class="notifications-menu" id="notificationsMenu" style="display: none;">
                        <div class="notification-item">
                            <a href="#">New Users Sign Up <span class="badge badge-primary"><?php echo $new_users_count; ?></span></a>
                        </div>
                        <div class="notification-item">
                            <a href="#">New Orders <span class="badge badge-primary"><?php echo $new_orders_count; ?></span></a>
                        </div>
                        <div class="notification-item">
                            <a href="#">New Customers</a>
                            <!-- Display new customer details -->
                            <ul>
                                <?php foreach ($new_customers as $customer): ?>
                                    <li><?php echo $customer['id']; ?>: <?php echo $customer['customer_name']; ?></li>
                                    <!-- Add more customer details as needed -->
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <!-- Add more notification items here -->
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
                        <h1 class="page-heading"> Dashboard </h1>
                        <p>
                            <a href="../auth/backend-assets/password-reset.php" class="btn btn-warning">Reset Your Password</a>
                        </p>

                        <!-- Statistics -->
                        <div class="sales-small-stats flex">
                            <div class="sales-small-stats-inner">
                                <div class="icon">
                                    <div class="doller">
                                        <i class="fa-solid fa-dollar-sign"></i>
                                    </div>
                                </div>
                                <?php
                                // Assuming you have a PDO connection named $connection
                                $query = "
                                    SELECT SUM(total_price) as total_sales
                                    FROM orders
                                    WHERE order_status_id = 4
                                ";
                                $stmt = $connection->prepare($query);
                                $stmt->execute();

                                // Check if the query was successful
                                if ($stmt && $stmt->rowCount() > 0) {
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalSales = $result['total_sales'];
                                } else {
                                    $totalSales = 0;
                                }
                                ?>

                                <div class="stats-d">
                                    <span class="block sub-title">Total Sales</span>
                                    <span class="block satts-number">BDT<?php echo number_format($totalSales, 2); ?></span>
                                </div>
                            </div>   
                            
                              <div class="sales-small-stats-inner">
                                <div class="icon color-g">
                                    <div class="doller">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </div>
                                </div>
                                <?php
                                // Assuming you have a PDO connection named $connection
                                $query = "
                                    SELECT COUNT(*) as total_orders
                                    FROM orders
                                    WHERE order_status_id = 4
                                ";
                                $stmt = $connection->prepare($query);
                                $stmt->execute();

                                // Check if the query was successful
                                if ($stmt && $stmt->rowCount() > 0) {
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalOrders = $result['total_orders'];
                                } else {
                                    $totalOrders = 0;
                                }
                                ?>
                                <div class="stats-d">
                                    <span class="block sub-title">Total Orders</span>
                                    <span class="block satts-number"><?php echo $totalOrders; ?></span>
                                </div>
                            </div>   
                             <div class="sales-small-stats-inner">
                                <div class="icon color-g-2">
                                    <div class="doller">
                                        <i class="fa-solid fa-cart-flatbed-suitcase"></i>
                                    </div>
                                </div>
                                <?php
                                // Assuming you have a PDO connection named $connection
                                $query = "
                                    SELECT COUNT(*) as total_products
                                    FROM products
                                ";
                                $stmt = $connection->prepare($query);
                                $stmt->execute();

                                // Check if the query was successful
                                if ($stmt && $stmt->rowCount() > 0) {
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalProducts = $result['total_products'];
                                } else {
                                    $totalProducts = 0;
                                }
                                ?>

                                <div class="stats-d">
                                    <span class="block sub-title">Total Products</span>
                                    <span class="block satts-number"><?php echo $totalProducts; ?></span>
                                </div>
                            </div>   

                            <div class="sales-small-stats-inner">
                                <div class="icon color-g-2">
                                    <div class="doller">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                </div>
                                <?php
                                // Assuming you have a PDO connection named $connection
                                $query = "SELECT COUNT(*) as total_subscribers FROM subscribers";
                                $stmt = $connection->prepare($query);
                                $stmt->execute();

                                // Check if the query was successful
                                if ($stmt && $stmt->rowCount() > 0) {
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalSubscribers = $result['total_subscribers'];
                                } else {
                                    $totalSubscribers = 0;
                                }
                                ?>

                                <div class="stats-d">
                                    <span class="block sub-title">Total Subscriber</span>
                                    <span class="block satts-number"><?php echo $totalSubscribers; ?></span>
                                </div>

                            </div>   
                        </div>

                        <!-- Display total sales, complete orders, canceled orders, and canceled sales in a table using Bootstrap -->
                        <div class=" mt-5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Total Sales</th>
                                        <th>Total Complete Orders</th>
                                        <th>Total Canceled Orders</th>
                                        <th>Total Canceled Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <?php
                                    include "../auth/db-connection/config.php";

                                    // Fetch data for complete orders
                                    $sql_complete = "SELECT COUNT(*) AS total_complete_orders, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4";
                                    $stmt_complete = $connection->prepare($sql_complete);
                                    $stmt_complete->execute();
                                    $complete_data = $stmt_complete->fetch(PDO::FETCH_ASSOC);

                                    // Fetch data for canceled orders
                                    $sql_canceled = "SELECT COUNT(*) AS total_canceled_orders, SUM(total_price) AS total_canceled_sales FROM orders WHERE order_status_id = 5";
                                    $stmt_canceled = $connection->prepare($sql_canceled);
                                    $stmt_canceled->execute();
                                    $canceled_data = $stmt_canceled->fetch(PDO::FETCH_ASSOC);

                                    // Get currency symbol from products table
                                    $currency_code_sql = "SELECT currency_code FROM products LIMIT 1"; // Assuming there's only one currency used in the products table
                                    $stmt_currency = $connection->prepare($currency_code_sql);
                                    $stmt_currency->execute();
                                    $currency_data = $stmt_currency->fetch(PDO::FETCH_ASSOC);
                                    $currency_symbol = ''; // Initialize currency symbol variable
                                    if ($currency_data) {
                                        // Fetch currency symbol based on currency code
                                        $currency_symbol = getCurrencySymbol($currency_data['currency_code']);
                                    }

                                    // Display total sales, complete orders, canceled orders, and canceled sales in a table row
                                    echo "<td>" . $currency_symbol . $complete_data['total_sales'] . "</td>";
                                    echo "<td>" . $complete_data['total_complete_orders'] . "</td>";
                                    echo "<td>" . $canceled_data['total_canceled_orders'] . "</td>";
                                    echo "<td>" . $currency_symbol . $canceled_data['total_canceled_sales'] . "</td>";

                                    // Function to get currency symbol based on currency code
                                    function getCurrencySymbol($currencyCode)
                                    {
                                        // Define currency symbols mapping
                                        $currencySymbols = [
                                            'USD' => '$', // Example: US Dollar
                                            'EUR' => '€', // Example: Euro
                                            // Add more currency symbols as needed
                                        ];

                                        // Return currency symbol if found, otherwise return the currency code
                                        return isset($currencySymbols[$currencyCode]) ? $currencySymbols[$currencyCode] : $currencyCode;
                                    }
                                    ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Create a canvas element to render the chart -->
                        <div class="chart-stats-wrapper">
                            <div class="stars-chart">
                                <h3>Sales Report</h3>
                                <div class="chart-tabs btn-group" role="group" aria-label="Chart Tabs">
                                    <button type="button" class="btn btn-primary" onclick="showDailyChart()">Daily</button>
                                    <button type="button" class="btn btn-primary" onclick="showWeeklyChart()">Weekly</button>
                                    <button type="button" class="btn btn-primary" onclick="showMonthlyChart()">Monthly</button>
                                    <button type="button" class="btn btn-primary" onclick="showYearlyChart()">Yearly</button>
                                </div>
                                <canvas id="salesChart"></canvas>
                            </div>
                            <div class="new-order-stats">
                                <h3>New Orders</h3>
                                <div class="new-order-ticker">
                                    <ul>
                                        <?php
                                        include "../auth/db-connection/config.php";

                                        // Assuming '1' is the order status ID for new orders (you should replace this with the actual ID)
                                        $new_order_status_id = 1;

                                        $sql_new_orders = "SELECT * FROM orders WHERE order_status_id = :status_id ORDER BY order_date DESC";
                                        $stmt_new_orders = $connection->prepare($sql_new_orders);
                                        $stmt_new_orders->bindParam(':status_id', $new_order_status_id);
                                        $stmt_new_orders->execute();

                                        // Fetch new orders and display them
                                        while ($row = $stmt_new_orders->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<li>";
                                            echo "<a href='./customer/view_orders.php?order_id=" . $row['id'] . "' class='new-order'>";
                                            echo "<p>ID: " . $row['id'] . "</p>";
                                            echo "<p>Qty.: " . $row['quantity'] . "</p>";
                                            echo "<p>Date: " . $row['order_date'] . "</p>";
                                            echo "</a>";
                                            echo "</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <?php
                        // Fetch data with the specified order status (e.g., order_status_id = 4 for complete orders)
                        $sql_orders = "SELECT * FROM orders WHERE order_status_id = 4";
                        $stmt_orders = $connection->prepare($sql_orders);
                        $stmt_orders->execute();

                        // Process the data to extract necessary information
                        $data = [];
                        while ($row = $stmt_orders->fetch(PDO::FETCH_ASSOC)) {
                            $data[] = [
                                'order_date' => $row['order_date'],
                                'total_price' => $row['total_price'],
                            ];
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Get references to the notifications icon and menu
        const notificationsIcon = document.getElementById('notificationsDropdown');
        const notificationsMenu = document.getElementById('notificationsMenu');

        // Add a click event listener to the notifications icon
        notificationsIcon.addEventListener('click', function() {
            // Toggle the display of the notifications menu
            if (notificationsMenu.style.display === 'none') {
                notificationsMenu.style.display = 'block';
            } else {
                notificationsMenu.style.display = 'none';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Your PHP data
    var ordersData = <?php echo json_encode($data); ?>;
    // Extracting date and sales data from PHP data
    var labels = ordersData.map(function(order) {
        return order.order_date;
    });
    var salesData = ordersData.map(function(order) {
        return order.total_price;
    });
    </script>
    <script src="../files/js/chart.js"></script>
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
    </script>
    <script src="js/main.js"></script>
</body>
</html>
