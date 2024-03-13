<?php
// Include your database connection file
include "../../auth/db-connection/config.php";

// Fetch yearly data from the database
$sql_yearly_data = "SELECT YEAR(order_date) AS year, SUM(total_price) AS total_sales FROM orders WHERE order_status_id = 4 GROUP BY YEAR(order_date)";
$stmt_yearly_data = $connection->prepare($sql_yearly_data);
$stmt_yearly_data->execute();

// Process the fetched data
$yearlyData = [];
while ($row = $stmt_yearly_data->fetch(PDO::FETCH_ASSOC)) {
    $yearlyData[] = [
        'year' => $row['year'],
        'total_sales' => $row['total_sales']
    ];
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($yearlyData);
?>
