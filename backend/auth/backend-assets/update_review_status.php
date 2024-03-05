<?php
// Include the database configuration
require_once '../db-connection/config.php';

// Get the review id and new status from the POST request
$reviewId = isset($_POST['id']) ? $_POST['id'] : null;
$newStatus = isset($_POST['status']) ? $_POST['status'] : null;

// Validate inputs (You might want to add more validation based on your requirements)

if ($reviewId !== null && $newStatus !== null) {
    try {

        // Prepare and execute the SQL update statement
        $stmt = $connection->prepare("UPDATE product_reviews SET reviewStatus = :status WHERE id = :id");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();

        // Close the connection
        $connection = null;

        // Return a success message (you can customize this as needed)
        echo "Review status updated successfully.";
    } catch (PDOException $e) {
        // Handle any errors that occur during the update
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle invalid or missing parameters
    echo "Invalid or missing parameters.";
}
?>
