<?php
// Assuming you have a database connection already established
require_once 'config.php';

// Session start (if not already started)
session_start();

// Get userId from the session
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

// Check if userId is available
if ($userId) {
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM customers WHERE id = :userId";
    $query = $connection->prepare($sql);
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    
    // Fetch the user data
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists
    if ($user) {
        // Access user data, e.g., $user['username'], $user['email'], etc.
        $username = $user['username'];
        $userPhoto = $user['photo']; // Assuming there's a column named 'photo' in your 'customers' table
    } 
} 
// else {
//         echo "User not found!";

//     }
// } else {
//     echo "User ID not available in the session!";
// }
?>