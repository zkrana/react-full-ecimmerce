<?php
// Include your database connection file
include_once("../auth/connection/config.php");
session_start();

try {
    // Get the user's unique identifier (using a cookie)
    $userIdentifier = isset($_COOKIE['userIdentifier']) ? $_COOKIE['userIdentifier'] : null;

    if (!$userIdentifier) {
        // If the user doesn't have a cookie, generate a unique identifier and set the cookie
        $userIdentifier = uniqid();
        setcookie('userIdentifier', $userIdentifier, time() + (365 * 24 * 60 * 60));
    }

    // Fetch wishlist count from the database based on userIdentifier
    $sql = "SELECT COUNT(DISTINCT wishlistItemId) AS wishlistCount FROM wishlist_items WHERE userIdentifier = ?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$userIdentifier]);
    $wishlistCount = $stmt->fetchColumn();

    // Return JSON response
    echo json_encode(['success' => true, 'wishlistCount' => $wishlistCount]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching wishlist count: ' . $e->getMessage()]);
}
?>

