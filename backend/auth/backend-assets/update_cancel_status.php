<?php
session_start();
include "../db-connection/config.php";

// Check if the request is sent using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data sent via POST
    $data = json_decode(file_get_contents("php://input"));

    // Check if the status and cancellation ID are set in the JSON data
    if (isset($data->status) && isset($data->cancellationId)) {
        $status = $data->status;
        $cancellationId = $data->cancellationId;

        try {
            // Prepare and execute the SQL query to update the status
            $stmt = $connection->prepare("UPDATE ordercancellation SET statusUpdate = :status WHERE cancellation_id = :id");
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":id", $cancellationId);
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->rowCount() > 0) {
                // Return a JSON response with success message
                echo json_encode(array("success" => true, "message" => "Status updated successfully"));
            } else {
                // Return a JSON response with failure message
                echo json_encode(array("success" => false, "message" => "Failed to update status"));
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo json_encode(array("success" => false, "message" => "Database error: " . $e->getMessage()));
        }
    } else {
        // Error: Missing status or cancellation ID
        echo json_encode(array("success" => false, "message" => "Status or cancellation ID not provided"));
    }
} else {
    // Error: Invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
