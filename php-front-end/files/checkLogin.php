<?php
// checkLogin.php
session_start();

if (isset($_SESSION['userId'])) {
    echo "loggedIn";
} else {
    echo "notLoggedIn";
}
?>
