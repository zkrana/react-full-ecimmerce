<?php

// Connect to the database
require_once '../auth/connection/config.php';

// Brute force protection - Limit requests
function checkRequestLimit($ip_address)
{
    global $connection;
    $query = $connection->prepare("SELECT COUNT(*) FROM customers 
    WHERE ip_address = :ip_address AND request_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Maximum 100 requests/hour
    if ($result['COUNT(*)'] > 100) {
        return false;
    }

    return true;
}

// Limitation of access time
function checkRequestTime($ip_address)
{
    global $connection;
    $query = $connection->prepare("SELECT request_time FROM customers 
    WHERE ip_address = :ip_address 
    ORDER BY request_time DESC LIMIT 1");
    $query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $last_request_time = strtotime($result['request_time']);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        if ($current_time - $last_request_time < 1) {
            return false;
        }
    }

    return true;
}

// Encrypt
function xorEncrypt($input, $key)
{
    return base64_encode($input ^ $key);
}

// Processing user registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!checkRequestLimit($_SERVER['REMOTE_ADDR'])) {
        header("Location: ../files/registration.php?error=Too many requests! Try again later.");
        exit;
    }

    if (!checkRequestTime($_SERVER['REMOTE_ADDR'])) {
        header("Location: ../files/registration.php?error=Request too common! Try again later.");
        exit;
    }

    // Check and process entered data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if values are not empty
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../files/registration.php?error=All fields are mandatory on the form.");
        exit;
    }

    // Validate input
    if (!validateInput($username) || !validateInput($email) || !validateInput($password)) {
        header("Location: ../files/registration.php?error=You have entered incorrect information.");
        exit;
    }

    // Password validation
    $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,24}$/';
    if (!preg_match($pattern, $password)) {
        header("Location: ../files/registration.php?error=The password is not strong enough. It must be at least 8 characters long and contain at least one uppercase letter and number. Your password can be a maximum of 24 characters.");
        exit;
    }

    // Hash the password and encrypt the email
    $encrypted_password = password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 2048,
        'time_cost'   => 4,
        'threads'     => 2,
    ]);

    // Use a secure encryption key
    $encryption_key = 'shTYTS,os(**0te455432%3sgks$#SG';
    $encrypted_email = xorEncrypt($email, $encryption_key);

    // You can perform further actions with the registered user, such as saving to the database.
    // For now, just echoing a success message.
    header("Location: ../files/registration.php?success=Account registered successfully.");

    saveRequest($_SERVER['REMOTE_ADDR'], $username, $email, $encrypted_password, $encryption_key);
} else {
    header("Location: ../files/registration.php?error=Wrong request.");
    exit;
}

function saveRequest($ip_address, $username, $email, $password, $encryption_key)
{
    global $connection;

    // Encrypt the email
    $encrypted_email = xorEncrypt($email, $encryption_key);

    // Prepare and execute the SQL query
    $query = $connection->prepare("INSERT INTO customers (ip_address, username, email, password)
        VALUES (:ip_address, :username, :email, :password)");
    $query->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $encrypted_email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
}

function validateInput($input)
{
    // SQL Injection protection
    if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $input)) {
        return false;
    }

    // XSS protection
    if (preg_match('/<[^>]*>/', $input)) {
        return false;
    }

    return true;
}
?>
