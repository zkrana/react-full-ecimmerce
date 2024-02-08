<?php
// Start or resume the session
session_start();

// Connection to the database
require_once '../auth/connection/config.php';

// Check if the request method is POST and the itemId is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itemId"])) {
    try {
        // Retrieve item ID from the POST request
        $itemId = $_POST["itemId"];
        
        // Perform the deletion query on cart_items table
        $stmtCartItems = $connection->prepare("DELETE FROM cart_items WHERE item_id = ?");
        $stmtCartItems->execute([$itemId]);

        // Perform the deletion query on cart table if no remaining items
        $stmtCart = $connection->prepare("DELETE FROM cart WHERE cart_id NOT IN (SELECT DISTINCT cart_id FROM cart_items)");
        $stmtCart->execute();

        // Check if the deletion was successful for both tables
        if ($stmtCartItems->rowCount() > 0 && $stmtCart->rowCount() > 0) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return error response if no rows were affected
            echo json_encode(['success' => false, 'message' => 'No rows affected.']);
        }
    } catch (PDOException $e) {
        // Send error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Return error response if invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

// Debugging: Log the received POST data
error_log('Received POST data: ' . print_r($_POST, true));

// Debugging: Log the SQL query executed
$errorInfoCartItems = $stmtCartItems->errorInfo();
if ($errorInfoCartItems[0] !== '00000') {
    error_log('SQL Error on cart_items table: ' . $errorInfoCartItems[2]);
}

$errorInfoCart = $stmtCart->errorInfo();
if ($errorInfoCart[0] !== '00000') {
    error_log('SQL Error on cart table: ' . $errorInfoCart[2]);
}
?>
