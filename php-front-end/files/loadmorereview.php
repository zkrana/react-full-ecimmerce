<?php
// Include your database connection file
include_once("../auth/connection/config.php");
session_start();

// Get the product ID from the query parameters
$productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

// Fetch reviews with customer names for the specific product using a JOIN
$reviewsPerPage = 2; // Number of reviews to show per page
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

// Your database query to fetch reviews with an offset
$query = "SELECT pr.*, CONCAT(c.first_name, ' ', c.last_name) AS customer_name
          FROM `product_reviews` pr
          JOIN `customers` c ON pr.customer_id = c.id
          WHERE pr.product_id = ? AND pr.reviewStatus = 'approved'
          ORDER BY pr.created_at DESC
          LIMIT $offset, $reviewsPerPage";

$stmt = $connection->prepare($query);
$stmt->bindParam(1, $productId, PDO::PARAM_INT);
$stmt->execute();
$reviewsForPage = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return reviews as JSON
header('Content-Type: application/json');
echo json_encode($reviewsForPage);
?>
