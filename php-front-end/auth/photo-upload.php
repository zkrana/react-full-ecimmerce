<?php
// Connect to the database
require_once '../auth/connection/config.php';

// Get user ID from the request parameters
$userID = isset($_GET['userId']) ? $_GET['userId'] : null;

// Get username from the database using the user ID
$username = '';
if ($userID !== null) {
    $selectQuery = "SELECT username FROM customers WHERE id = ?";
    $stmt = $connection->prepare($selectQuery);
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $username = $result['username'];
    }
}

// Check if a file was uploaded
if ($_FILES["file"] && $userID !== null && $username !== '') {
    $target_dir = "./assets/user-profile/$username/"; // Specify the folder where you want to save the uploaded photos

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            error_log('Failed to create directory: ' . $target_dir); // Log error
             $error = 'Failed to create directory' ;
            exit;
        } else {
            // Explicitly set permissions (including subdirectories)
            chmod($target_dir, 0777);
            error_log('Directory created: ' . $target_dir); // Log success
        }
    }
$target_file = $target_dir . basename($_FILES["file"]["name"]);
error_log('Target file: ' . $target_file); // Log file path

    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        $error = 'File is an image - ' . $check["mime"];
        $uploadOk = 1;
    } else {
        $error = 'File is not an image.';
        $uploadOk = 0;
    }

    // Check file size (you can customize the size limit)
    if ($_FILES["file"]["size"] > 500000) {
        $error = 'Sorry, your file is too large.';
        $uploadOk = 0;
    }

    // Allow certain file formats (you can customize the allowed formats)
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $error = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $error = 'Sorry, your file was not uploaded.';
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Store photo information in the database
            $filename = basename($_FILES["file"]["name"]);
            $filePath = $target_dir . $filename;

            // Update user's photo in the 'requests' table
            $updateQuery = "UPDATE customers SET photo = ? WHERE id = ?";
            $stmt = $connection->prepare($updateQuery);
            $stmt->bindParam(1, $filePath);
            $stmt->bindParam(2, $userID);
            $stmt->execute();

           
        } else {
            $error = 'Sorry, there was an error uploading your file.';
        }
    }
} else {
     $error = 'No file uploaded or missing user ID.';
}

?>
