<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the database connection
include '../../auth/connection/config.php';

// Get the search query from the user
$searchQuery = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

// Perform a search on categories and products based on $searchQuery

// Query categories
$categoryQuery = "SELECT * FROM categories WHERE LOWER(name) LIKE '%$searchQuery%'";
$categoryResult = $connection->query($categoryQuery);

// Query products
$productQuery = "SELECT * FROM products WHERE LOWER(name) LIKE '%$searchQuery%'";
$productResult = $connection->query($productQuery);

// Combine results
$results = ['categories' => [], 'products' => []];

while ($row = $categoryResult->fetch(PDO::FETCH_ASSOC)) {
    $results['categories'][] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'link' => 'categories/singleCategory.php?category_id=' . $row['id']
    ];
}

while ($row = $productResult->fetch(PDO::FETCH_ASSOC)) {
    $results['products'][] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'link' => 'product_link_placeholder?id=' . $row['id']
    ];
}

// Return the results as JSON
header('Content-Type: application/json');

if (empty($results['categories']) && empty($results['products'])) {
    // No matching results found
    echo json_encode(['message' => 'No matching results found.']);
} else {
    // Results found
    echo json_encode($results);
}
?>
