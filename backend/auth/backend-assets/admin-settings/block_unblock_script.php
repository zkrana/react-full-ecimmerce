<?php

// Include your database connection code here
include '../../db-connection/config.php'; // Adjust the path accordingly

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Validate $action and $id here

    // Update the database based on the action
    try {
        if ($action === 'block') {
            // Perform block action and update the database
            $stmt = $connection->prepare("INSERT INTO blocked_ips (ip_address, blocked_until) VALUES ((SELECT ip_address FROM blocked_ips WHERE id = :id), NOW())");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } elseif ($action === 'unblock') {
            // Perform unblock action and remove from the database
            $stmt = $connection->prepare("DELETE FROM blocked_ips WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Assuming the action was successful
        echo 'success';
    } catch (PDOException $e) {
        // Handle database connection or query error
        echo 'error';
    }
} else {
    // Invalid request method
    echo 'error';
}
?>
