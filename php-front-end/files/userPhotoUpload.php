<?php
include "../auth/connection/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if a file was selected
    if (isset($_FILES["userPhoto"])) {
        $file = $_FILES["userPhoto"];

        // Get the user ID from the session
        session_start();
        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

        if (!$userId) {
            echo "User not logged in.";
            exit;
        }

        // Specify the target directory for file upload based on the user ID
        $targetDirectory = "../assets/user-profile/{$userId}/";

        // Create the user's directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        // Generate a unique filename to avoid overwriting existing files
        $uniqueFilename = uniqid() . "_" . basename($file["name"]);

        // Specify the full path where the file will be stored
        $targetPath = $targetDirectory . $uniqueFilename;

        // Check if the file is an image (you may want to enhance this check)
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (getimagesize($file["tmp_name"])) {
            // Check if the file size is within limits (adjust as needed)
            if ($file["size"] <= 5000000) { // 5 MB
                // Move the uploaded file to the target directory
                if (move_uploaded_file($file["tmp_name"], $targetPath)) {
                    // Update the user's photo path in the database
                    $updatePhotoQuery = "UPDATE customers SET photo = :photo WHERE id = :userId";
                    $stmt = $connection->prepare($updatePhotoQuery);
                    $stmt->bindParam(":photo", $targetPath, PDO::PARAM_STR);
                    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
                    $stmt->execute();

                    // Redirect back to the user profile page
                    header("Location: ../userprofile.php");
                    exit;
                } else {
                    echo "Failed to move the uploaded file.";
                }
            } else {
                echo "File size exceeds the limit.";
            }
        } else {
            echo "Invalid file format. Please upload an image.";
        }
    } else {
        echo "No file selected.";
    }
}
?>
