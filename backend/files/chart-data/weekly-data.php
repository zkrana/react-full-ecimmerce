<?php
// Include your database connection file
include "../../auth/db-connection/config.php";

// Fetch weekly data from the database
$sql_weekly_data = "SELECT YEAR(order_date) AS year, WEEK(order_date) AS week, MONTH(order_date) AS month, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4 GROUP BY YEAR(order_date), WEEK(order_date), MONTH(order_date)";
$stmt_weekly_data = $connection->prepare($sql_weekly_data);
$stmt_weekly_data->execute();

// Process the fetched data
$weeklyData = [];
while ($row = $stmt_weekly_data->fetch(PDO::FETCH_ASSOC)) {
    $weeklyData[] = [
        'year' => $row['year'],
        'week' => $row['week'],
        'month' => $row['month'],
        'total_sales' => $row['total_sales']
    ];
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($weeklyData);
?>
