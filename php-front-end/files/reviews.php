<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../auth/connection/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve product ID from the URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Validate product ID (you might want to add more validation)
    if ($productId <= 0) {
        echo "Invalid product ID";
        exit;
    }

    if (isset($_SESSION['userId'])) {
        // Fetch customer ID from the session
        $customerId = $_SESSION['userId'];
    } else {
        echo "User is not logged in";
        header("Location: ./userlogin.php");
        exit;
    }

    // Initialize variables
    $rating = 0;
    $reviewText = '';

    // Check if the request contains JSON data
    $jsonInput = file_get_contents('php://input');
    $jsonData = json_decode($jsonInput, true);

    if ($jsonData !== null) {
        // If JSON data is present, use it
        $rating = isset($jsonData['rating']) ? intval($jsonData['rating']) : 0;
        $reviewText = isset($jsonData['review_text']) ? trim($jsonData['review_text']) : '';
    } else {
        // If no JSON data, assume form data
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
    }

    // Validate the rating (you might want to add more validation)
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating";
        exit;
    }

    // Validate the review text (you might want to add more validation)
    if (empty($reviewText)) {
        echo "Review text cannot be empty";
        exit;
    }
    // Set the timezone to Asia/Dhaka
    date_default_timezone_set('Asia/Dhaka');

    // Get the current date and time in Asia/Dhaka timezone
    $createdAt = date('Y-m-d H:i:s');

    // Insert the review into the database using prepared statement (PDO)
    $query = "INSERT INTO `product_reviews` (product_id, customer_id, rating, review_text, created_at)
              VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $connection->prepare($query);

    // Bind parameters to the statement
    $stmt->bindParam(1, $productId, PDO::PARAM_INT);
    $stmt->bindParam(2, $customerId, PDO::PARAM_INT);
    $stmt->bindParam(3, $rating, PDO::PARAM_INT);
    $stmt->bindParam(4, $reviewText, PDO::PARAM_STR);
    $stmt->bindParam(5, $createdAt, PDO::PARAM_STR);

    // Execute the statement
    $result = $stmt->execute();

if ($result) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("Thanks! for your review.");
                // Redirect to the product page after displaying the alert
                window.location.href = "../products/singleProduct.php?id=' . $productId . '";
            });
          </script>';
    exit;
} else {
    echo "Error inserting review: " . $stmt->errorInfo()[2];
    exit;
}

}
?>
