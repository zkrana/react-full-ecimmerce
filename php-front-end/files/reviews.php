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
        header("Location: ../index.php");
        exit;
    }

    echo "Product ID from URL: " . $_GET['id'] . "<br>";
    echo "Rating: " . $_POST['rating'] . "<br>";
    echo "Review Text: " . $_POST['review_text'] . "<br>";


    // Sanitize and validate user inputs
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

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

    $createdAt = date('Y-m-d H:i:s'); // Current date and time

    // Insert the review into the database using prepared statement
    $query = "INSERT INTO `product_reviews` (product_id, customer_id, rating, review_text, created_at)
              VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameters to the statement
    mysqli_stmt_bind_param($stmt, "iiiss", $productId, $customerId, $rating, $reviewText, $createdAt);

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "Review inserted successfully!";
        header("Location: ../products/singleProduct.php");
        exit;
    } else {
        echo "Error inserting review: " . mysqli_error($connection);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>
