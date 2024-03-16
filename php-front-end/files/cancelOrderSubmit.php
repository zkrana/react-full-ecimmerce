<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../auth/connection/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if customerId is set and not empty
    if (isset($_POST["customerId"]) && !empty($_POST["customerId"])) {
        $customerId = $_POST["customerId"]; // Retrieve customerId from the form

        // Proceed with the rest of the submission logic
        // Sanitize and validate orderId
        if (isset($_POST["orderId"]) && !empty($_POST["orderId"])) {
            $customerId = $_POST["customerId"];
            $orderIds = $_POST["orderId"];
            // Assuming customerName and customerEmail are also submitted via hidden fields
            $customerName = $_POST["customerName"];
            $customerEmail = $_POST["customerEmail"];
            // Sanitize and validate reason and comments
            $reason = $_POST["reason"];
            $comments = $_POST["comments"];

            try {
                // Prepare and bind SQL statement
                $stmt = $connection->prepare("INSERT INTO ordercancellation (customerId, order_id, customer_name, customer_email, reason, comments, cancellation_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");

                // Check if the statement is prepared successfully
                if ($stmt) {
                    // Bind parameters
                    $stmt->bindParam(1, $customerId);
                    $stmt->bindParam(2, $orderId);
                    $stmt->bindParam(3, $customerName);
                    $stmt->bindParam(4, $customerEmail);
                    $stmt->bindParam(5, $reason);
                    $stmt->bindParam(6, $comments);

                    // Insert data into the database for each orderId
                    foreach ($orderIds as $orderId) {
                        $stmt->execute();
                    }
                    
                    header("Location: ../cancelOrder.php?success='Order cancellation request submitted successfully.'");
                } else {
                    header("Location: ../cancelOrder.php?error='Failed to prepare statement.'");
                }

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            header("Location: ../cancelOrder.php?error='No orderId provided.'");
        }
    } else {
        header("Location: ../cancelOrder.php?error='No customerId provided.'");
    }
} else {
    header("Location: ../cancelOrder.php?error='Invalid request.'");

}
?>
