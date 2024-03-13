<?php
// Include your database connection file
include "../../auth/db-connection/config.php";

// Fetch daily data from the database
$sql_daily_data = "SELECT DATE(order_date) AS order_date, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4 GROUP BY DATE(order_date)";
$stmt_daily_data = $connection->prepare($sql_daily_data);
$stmt_daily_data->execute();

// Process the fetched data
$dailyData = [];
while ($row = $stmt_daily_data->fetch(PDO::FETCH_ASSOC)) {
    // Format date if needed (depends on how it's stored in the database)
    $orderDate = date("Y-m-d", strtotime($row['order_date']));
    $dailyData[] = [
        'date' => $orderDate,
        'total_sales' => $row['total_sales']
    ];
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($dailyData);
?>
