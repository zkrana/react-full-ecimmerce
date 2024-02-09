<?php
require_once '../auth/connection/config.php';

function validateInput($input)
{
    // Use a more robust method for input validation (e.g., htmlspecialchars)
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function xorEncrypt($input, $key)
{
    return base64_encode($input ^ $key);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? validateInput($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        $error = "Error: Missing required fields.";
        header("Location: ../files/userlogin.php?error=$error");
        exit;
    }

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM customers WHERE email = :email";
    $query = $connection->prepare($sql);
    $query->bindParam(':email', xorEncrypt($email, 'shTYTS,os(**0te455432%3sgks$#SG'), PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        // Start session on successful login
        session_start();
        $_SESSION['userId'] = $row['id'];
        $_SESSION['username'] = $row['username'];
         $_SESSION['loggedIn'] = true;

        // Regenerate the session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // Redirect to the dashboard after successful login
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Error: Incorrect login information.";
        header("Location: ../files/userlogin.php?error=$error");
        exit;
    }
} else {
    $error = "Error: Wrong request.";
    header("Location: ../files/userlogin.php?error=$error");
    exit;
}
?>
