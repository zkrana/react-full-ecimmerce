<?php
// Include your database connection file
include "../../auth/db-connection/config.php";

// Fetch monthly data from the database
$sql_monthly_data = "SELECT YEAR(order_date) AS year, MONTH(order_date) AS month, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4 GROUP BY YEAR(order_date), MONTH(order_date)";
$stmt_monthly_data = $connection->prepare($sql_monthly_data);
$stmt_monthly_data->execute();

// Process the fetched data
$monthlyData = [];
while ($row = $stmt_monthly_data->fetch(PDO::FETCH_ASSOC)) {
    $monthlyData[] = [
        'year' => $row['year'],
        'month' => $row['month'],
        'total_sales' => $row['total_sales']
    ];
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($monthlyData);
?>
