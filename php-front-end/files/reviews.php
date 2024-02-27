<?php
session_start();

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
        header("Loction: ../index.php");
    }

    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text'];
    $createdAt = date('Y-m-d H:i:s'); // Current date and time

    // Insert the review into the database
    $query = "INSERT INTO `product_reviews` (product_id, customer_id, rating, review_text, created_at)
              VALUES ('$productId', '$customerId', '$rating', '$reviewText', '$createdAt')";

    // Execute the query
    $result = mysqli_query($yourDbConnection, $query);

    if ($result) {
        echo "Review inserted successfully!";
    } else {
        echo "Error inserting review: " . mysqli_error($yourDbConnection);
    }

    // Close the database connection
    mysqli_close($yourDbConnection);
}
?>