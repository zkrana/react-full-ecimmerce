<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../auth/connection/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the email
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    error_log(print_r($_POST, true)); // Check if POST data is received

    // Check if the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert the email into the database
        $stmt = $connection->prepare("INSERT INTO subscribers (email) VALUES (:email)");
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Subscription successful!"]);
        } catch (PDOException $e) {
            // Check if the error is due to a duplicate entry (email already exists)
            if ($e->errorInfo[1] == 1062) {
                echo json_encode(["success" => false, "message" => "Email is already subscribed."]);
            } else {
                echo json_encode(["success" => false, "message" => "Subscription failed. Please try again later. Error: " . $e->getMessage()]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email address."]);
    }
}
?>
