<?php
// Include your database connection file
include_once("../auth/connection/config.php");
session_start();

try {
    // Replace the following line with your actual mechanism to get the user ID
    $customerId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    // Get the local IP address
    $userIpAddress = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

    // Check if the user has a cookie, and if not, generate a unique identifier and set the cookie
    if (!isset($_COOKIE['userIdentifier'])) {
        $userIdentifier = uniqid('user_', true); // You can use a more sophisticated method to generate a unique identifier
        setcookie('userIdentifier', $userIdentifier, time() + 31536000, '/'); // Cookie set to expire in one year
    } else {
        $userIdentifier = $_COOKIE['userIdentifier'];
    }

    // Fetch wishlist count from the database based on userIdentifier
    $sql = "SELECT COUNT(DISTINCT wishlistId) AS wishlistCount FROM wishlists WHERE userIdentifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$userIdentifier]);
    $wishlistCount = $stmt->fetchColumn();

    // Return JSON response
    echo json_encode(['success' => true, 'wishlistCount' => $wishlistCount]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching wishlist count: ' . $e->getMessage()]);
}

?>

