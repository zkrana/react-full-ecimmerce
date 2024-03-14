<?php
session_start();
// Include your database connection file
include "../../auth/db-connection/config.php";
// SQL query to fetch user activity data
$sql = "SELECT DATE(request_time) AS signup_date, 
               SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_count,
               SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS inactive_count
        FROM customers
        GROUP BY DATE(request_time)
        ORDER BY signup_date";

$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for JSON response
$data = [
    'labels' => [],
    'datasets' => [
        [
            'label' => 'Active Users',
            'data' => [],
            'fill' => false,
            'borderColor' => 'rgb(75, 192, 192)',
            'lineTension' => 0.1
        ],
        [
            'label' => 'Inactive Users',
            'data' => [],
            'fill' => false,
            'borderColor' => 'rgb(255, 99, 132)',
            'lineTension' => 0.1
        ]
    ]
];

foreach ($result as $row) {
    $data['labels'][] = $row['signup_date'];
    $data['datasets'][0]['data'][] = $row['active_count'];
    $data['datasets'][1]['data'][] = $row['inactive_count'];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>