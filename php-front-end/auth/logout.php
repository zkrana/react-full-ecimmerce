<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session if it exists
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Redirect to the home page
header('Location: /reactcrud/php-front-end/');
exit();
?>
