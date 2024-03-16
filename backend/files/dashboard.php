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
                            <ul style="margin-left: 5px; background: unset;">
                                <li style="padding-top: 0; padding-bottom:0;">
                                     <a href="cancelOrdersReq.php">
                                        <span class="block">Order Cancel Request</span>
                                    </a>
                                </li>
                            </ul>
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

                        <!-- Create a canvas element to render the chart -->
                        <div class="chart-stats-wrapper mt-4">
                            <div class="stars-chart">
                                <div class="flex oflex">
                                    <h3>Sales Report</h3>
                                    <a href="./chart-data/orders_overview.php" class="text-center text-muted">
                                        <div class="down-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path id="download" d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"></path></svg>
                                        </div>    
                                        Download Sales Report
                                    </a>
                                </div>
                                <div class="chart-tabs btn-group mt-4" role="group" aria-label="Chart Tabs">
                                    <button type="button" class="btn btn-primary" onclick="showDailyChart()">Daily</button>
                                    <button type="button" class="btn btn-primary" onclick="showWeeklyChart()">Weekly</button>
                                    <button type="button" class="btn btn-primary" onclick="showMonthlyChart()">Monthly</button>
                                    <button type="button" class="btn btn-primary" onclick="showYearlyChart()">Yearly</button>
                                </div>
                                <canvas id="salesChart"></canvas>
                            </div>
                            <div class="new-order-stats">
                                <div class="recapdata-stats">
                                    <div class="flex oflex">
                                        <h3>Orders Overview</h3>
                                        <a href="./chart-data/orders_overview.php" class="text-center text-muted">
                                            <div class="down-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path id="download" d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                            </div>    
                                            Download Report
                                        </a>

                                    </div>
                                   
                                    <?php
                                    // Fetch data for complete orders
                                    $sql_complete = "SELECT COUNT(*) AS total_complete_orders, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4";
                                    $stmt_complete = $connection->prepare($sql_complete);
                                    $stmt_complete->execute();
                                    $complete_data = $stmt_complete->fetch(PDO::FETCH_ASSOC);

                                    // Fetch data for pending orders
                                    $sql_pending = "SELECT COUNT(*) AS total_pending_orders FROM orders WHERE order_status_id = 1";
                                    $stmt_pending = $connection->prepare($sql_pending);
                                    $stmt_pending->execute();
                                    $pending_data = $stmt_pending->fetch(PDO::FETCH_ASSOC);

                                    // Fetch data for payment received orders
                                    $sql_payment_received = "SELECT COUNT(*) AS total_payment_received_orders FROM orders WHERE order_status_id = 2";
                                    $stmt_payment_received = $connection->prepare($sql_payment_received);
                                    $stmt_payment_received->execute();
                                    $payment_received_data = $stmt_payment_received->fetch(PDO::FETCH_ASSOC);

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

                                    <div class="mt-4">
                                        <canvas id="salesPieChart" width="400" height="400"></canvas>
                                        <div class="pie-summary">
                                            <!-- Displaying the total sales and orders -->
                                            <span>Total Sales:</span> <?php echo isset($currency_symbol) ? $currency_symbol . $complete_data['total_sales'] : ''; ?> <span class="separatorLine">|</span>
                                            <span>Total Complete Orders:</span> <?php echo isset($complete_data['total_complete_orders']) ? $complete_data['total_complete_orders'] : ''; ?> <span class="separatorLine">|</span>
                                            <span>Total Pending Orders:</span> <?php echo isset($pending_data['total_pending_orders']) ? $pending_data['total_pending_orders'] : ''; ?> <span class="separatorLine">|</span>
                                            <span>Total Payment Received Orders:</span> <?php echo isset($payment_received_data['total_payment_received_orders']) ? $payment_received_data['total_payment_received_orders'] : ''; ?> <span class="separatorLine">|</span>
                                            <span>Total Canceled Orders:</span> <?php echo isset($canceled_data['total_canceled_orders']) ? $canceled_data['total_canceled_orders'] : ''; ?> <span class="separatorLine">|</span>
                                            <span>Total Canceled Sales:</span> <?php echo isset($currency_symbol) ? $currency_symbol . $canceled_data['total_canceled_sales'] : ''; ?>
                                        </div>
                                    </div>
                                </div>


                                <!-- <div class="new-order-wrapper">
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

                                </div> -->
                            </div>
                            
                        </div>

                        <?php
                        // Set the timezone to Astana/Dhaka time
                        date_default_timezone_set('Asia/Dhaka'); // Adjust this according to the desired timezone

                        // Fetch data with the specified order status (e.g., order_status_id = 4 for complete orders)
                        $sql_orders = "SELECT * FROM orders WHERE order_status_id = 4";
                        $stmt_orders = $connection->prepare($sql_orders);
                        $stmt_orders->execute();

                        // Process the data to extract necessary information
                        $data = [];
                        while ($row = $stmt_orders->fetch(PDO::FETCH_ASSOC)) {
                            // Convert order_date to Astana/Dhaka time
                            $order_date_local = date('Y-m-d H:i:s', strtotime($row['order_date']));

                            $data[] = [
                                'order_date' => $order_date_local,
                                'total_price' => $row['total_price'],
                            ];
                        }
                        ?>
                        <!-- User Activity -->
                        <div class="user-activity mt-4">
                            <div class="user-a-d">
                                <div class="u-header-a">
                                    <h3>User Activity</h3>
                                    <div class="ranges">
                                        <select id="timeRangeSelect"  class="form-select">
                                            <option value="Today">Today</option>
                                            <option value="Yesterday">Yesterday</option>
                                            <option value="Last 7 Days">Last 7 Days</option>
                                            <option value="Last 30 Days" selected>Last 30 Days</option>
                                            <option value="This Month">This Month</option>
                                            <option value="Last Month">Last Month</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="user-a-graph">
                                    <canvas id="userActivityChart"></canvas>
                                </div>
                            </div>
                            <div class="user-a-d">
                                <h3>Sold by Items</h3>
                                <?php
                                // Fetch data for total sales of each product, limiting to top 5
                                $sql = "SELECT 
                                    p.name AS product_name,
                                    COALESCE(SUM(oi.quantity), 0) AS total_sale,
                                    ROUND(
                                        (
                                            COALESCE(SUM(oi.quantity), 0) / 
                                            (
                                                SELECT SUM(oi.quantity) 
                                                FROM order_items oi 
                                                JOIN orders o ON oi.order_id = o.id 
                                                WHERE o.order_status_id = 4
                                            ) 
                                            * 100
                                        ), 
                                        2
                                    ) AS total_sale_by_percent
                                FROM 
                                    products p
                                LEFT JOIN 
                                    order_items oi ON p.id = oi.product_id
                                GROUP BY 
                                    p.name
                                HAVING 
                                    total_sale >= 5
                                ORDER BY 
                                    total_sale DESC
                                LIMIT 5";
                                $stmt = $connection->prepare($sql);
                                $stmt->execute();
                                $product_sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <ul class="mt-4">
                                    <?php foreach ($product_sales as $product_sale): ?>
                                        <li><span><?php echo $product_sale['product_name']; ?></span> <span><?php echo $product_sale['total_sale']; ?></span> <span><?php echo $product_sale['total_sale_by_percent']; ?>% <div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path id="arrowUp" d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg></div></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div class="user-activity mt-4">
                            <div class="card-table-border-none" id="recent-orders">
								<div class="flex">
									<h2>Recent Orders</h2>
									<div class="date-range-report">
										<select id="" class="form-select">
                                            <option>Feb 14, 2024 - Mar 14, 2024</option>
                                        </select>
									</div>
								</div>
								<div class="card-body mt-4 pb-5">
                                    <?php
                                    // Pagination variables
                                    $limit = 5; // Number of records per page
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $start = ($page - 1) * $limit;

                                    $sql = "SELECT o.id AS order_id, p.name AS product_name, oi.quantity, p.currency_code AS currency, o.order_date, oi.total_price AS total_price, os.status_name AS order_status
                                            FROM orders o
                                            INNER JOIN order_items oi ON o.id = oi.order_id
                                            INNER JOIN products p ON oi.product_id = p.id
                                            INNER JOIN order_status os ON o.order_status_id = os.id
                                            ORDER BY o.order_date DESC
                                            LIMIT :start, :limit";
                                    $stmt = $connection->prepare($sql);
                                    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
                                    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                                    $stmt->execute();
                                    // Display recent orders in HTML table format
                                    if ($stmt->rowCount() > 0) {
                                        echo '<table class="table card-table table-responsive table-responsive-large" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Product Name</th>
                                                        <th class="d-none d-lg-table-cell">Units</th>
                                                        <th class="d-none d-lg-table-cell">Order Date</th>
                                                        <th class="d-none d-lg-table-cell">Order Cost</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                        // Output data of each row
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $badge_class = '';
                                        switch ($row["order_status"]) {
                                            case 'Pending':
                                                $badge_class = 'text-bg-primary';
                                                break;
                                            case 'Payment Received':
                                                $badge_class = 'text-bg-info';
                                                break;
                                            case 'Processing':
                                                $badge_class = 'text-bg-warning';
                                                break;
                                            case 'Shipped':
                                                $badge_class = 'text-bg-success';
                                                break;
                                            case 'Cancel':
                                                $badge_class = 'text-bg-danger';
                                                break;
                                            default:
                                                $badge_class = 'text-bg-secondary';
                                                break;
                                        }
                                        echo "<tr>
                                                <td>" . $row["order_id"] . "</td>
                                                <td><a class='text-dark' href=''>" . $row["product_name"] . "</a></td>
                                                <td class='d-none d-lg-table-cell'>" . $row["quantity"] . " Units</td>
                                                <td class='d-none d-lg-table-cell'>" . $row["order_date"] . "</td>
                                                <td class='d-none d-lg-table-cell'>" . $row["currency"] . $row["total_price"] . "</td>
                                                <td><span class='badge $badge_class'>" . $row["order_status"] . "</span></td>
                                                <td class='text-right'>
                                                    <select class='form-select'>
                                                        <option value='view'>View</option>
                                                        <option value='remove'>Remove</option>
                                                    </select>
                                                </td>
                                            </tr>";
                                    }
                                        echo '</tbody></table>';

                                        // Pagination links
                                        $sql_count = "SELECT COUNT(id) AS total FROM orders";
                                        $stmt_count = $connection->prepare($sql_count);
                                        $stmt_count->execute();
                                        $total_pages = ceil($stmt_count->fetch(PDO::FETCH_ASSOC)["total"] / $limit);
                                        echo '<ul class="pagination">';
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo "0 results";
                                    }
                                    ?>
								</div>
							</div>
                            <?php
                            // Query to fetch top 5 products based on sales
                            $sql = "SELECT p.name AS product_name, p.price AS product_price, p.product_photo AS product_image, COUNT(oi.id) AS sales
                                    FROM products p
                                    JOIN order_items oi ON p.id = oi.product_id
                                    GROUP BY p.id
                                    ORDER BY sales DESC
                                    LIMIT 3";

                            $stmt = $connection->query($sql);

                            // Check if there are results
                            if ($stmt->rowCount() > 0) {
                                echo '<div class="top-orders">';
                                echo '<div class="flex"><h2>Top Products</h2></div>';

                                // Iterate over the top products
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<div class="top-products-d flex">';
                                    echo '<div class="products-d-d">';
                                    echo '<div class="products-img-d">';
                                    echo '<img src="../auth/assets/products/' . $row["product_image"] . '" alt="' . $row["product_name"] . '">';
                                    echo '</div>';
                                    echo '<div class="p-d-combined">';
                                    echo '<span class="text-muted">' . $row["product_name"] . '</span>';
                                    echo '<span>' . $row["product_price"] . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="products-sales-c text-muted">';
                                    echo 'Sales ' . $row["sales"];
                                    echo '</div>';
                                    echo '</div>';
                                }

                                echo '</div>'; // Close top-orders div
                            } else {
                                // No top products found
                                echo 'No top products found.';
                            }
                            ?>
                        </div>
                        <footer class="footer mt-5">
                            <p class="mb-0">
                                Copyright © <span>2024</span> Ecommerce . All Rights Reserved.
                            </p>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <script src="../files/js/userchart.js"></script>

    <!-- Notifications -->
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

    <script>
        // Data for the pie chart
        var data = {
            labels: ['Total Complete Orders', 'Total Pending Orders', 'Total Payment Received Orders', 'Total Canceled Orders'],
            datasets: [{
                data: [
                    <?php echo isset($complete_data['total_complete_orders']) ? $complete_data['total_complete_orders'] : '0'; ?>,
                    <?php echo isset($pending_data['total_pending_orders']) ? $pending_data['total_pending_orders'] : '0'; ?>,
                    <?php echo isset($payment_received_data['total_payment_received_orders']) ? $payment_received_data['total_payment_received_orders'] : '0'; ?>,
                    <?php echo isset($canceled_data['total_canceled_orders']) ? $canceled_data['total_canceled_orders'] : '0'; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)', // Total Complete Orders color
                    'rgba(255, 206, 86, 0.7)', // Total Pending Orders color
                    'rgba(75, 192, 192, 0.7)', // Total Payment Received Orders color
                    'rgba(255, 99, 132, 0.7)', // Total Canceled Orders color
                ]
            }]
        };

        // Render the pie chart
        var ctx = document.getElementById('salesPieChart').getContext('2d');
        var salesPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                legend: {
                    display: false
                }
            }
        });
    </script>
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
