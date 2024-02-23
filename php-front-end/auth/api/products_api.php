<?php
include "../connection/config.php";

// Get filter parameters from the request
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // Default sort by id

try {
    // Build the SQL query based on filter parameters
    $sql = "SELECT * FROM products";

    $whereClauses = [];

    if ($category_id !== null) {
        $whereClauses[] = "category_id = :category_id";
    }

    if ($min_price !== null) {
        $whereClauses[] = "price >= :min_price";
    }

    if ($max_price !== null) {
        $whereClauses[] = "price <= :max_price";
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Validate the $sort parameter to prevent SQL injection
    $validSortColumns = ['id', 'name', 'price', 'category_id', 'created_at', 'updated_at'];
    $sort = in_array($sort, $validSortColumns) ? $sort : 'id';

    $sql .= " ORDER BY $sort";

    // Add pagination logic
    $items_per_page = 12;
    $page = isset($_GET['page']) ? max(1, $_GET['page']) : 1;
    $offset = ($page - 1) * $items_per_page;
    $sql .= " LIMIT $items_per_page OFFSET $offset";

    $stmt = $connection->prepare($sql);

    if ($category_id !== null) {
        $stmt->bindParam(':category_id', $category_id);
    }

    if ($min_price !== null) {
        $stmt->bindParam(':min_price', $min_price);
    }

    if ($max_price !== null) {
        $stmt->bindParam(':max_price', $max_price);
    }

    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

   // Output products as JSON
    echo json_encode($products);

    // Log debug information
    error_log("Debug - SQL: " . $sql);
    error_log("Debug - Params: " . print_r($stmt->debugDumpParams(), true));
    error_log("Debug - Products: " . print_r($products, true));
} catch (Exception $e) {
    // Handle exceptions (e.g., log, display an error message)
    error_log("Error: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
}
?>
