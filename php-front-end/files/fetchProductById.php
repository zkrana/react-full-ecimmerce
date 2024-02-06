<?php
// connection to the database
require_once '../auth/connection/config.php';

// Get the product ID from the POST request
$productId = $_POST['productId'];

// Prepare and execute the SQL query to fetch product details
$sql = "SELECT * FROM products WHERE id = :productId";
$stmt = $connection->prepare($sql);
$stmt->bindParam(':productId', $productId, PDO::PARAM_INT); // Assuming productId is an integer
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the product exists
if ($product) {
    // Return product details as JSON response
    echo json_encode($product);
} else {
    // Product not found
    echo json_encode(['error' => 'Product not found']);
}

// Close database connection
$connection = null;
?>
