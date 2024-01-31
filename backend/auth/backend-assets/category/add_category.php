<?php
// add_category.php
require_once "../../db-connection/config.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $categoryName = $_POST["categoryName"];
    $categoryDescription = $_POST["categoryDescription"];
    $parentCategoryId = isset($_POST["parentCategory"]) ? $_POST["parentCategory"] : null;

    // File upload handling
    $targetDirectory = "../../assets/categories/";
    $uploadOk = 1;

    // Check if image file is a valid image
    $check = getimagesize($_FILES["categoryPhoto"]["tmp_name"]);
    if ($check === false) {
        echo "File is not a valid image.";
        $uploadOk = 0;
        header("Location: ../../../files/categories.php?error='File is not a valid image.'");
        exit;
    }

    // Check file size
    if ($_FILES["categoryPhoto"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
        header("Location: ../../../files/categories.php?error='File is too large.'");
        exit;
    }

    // Allow certain file formats
    $allowedFileTypes = ["jpg", "jpeg", "png", "gif"];
    $imageFileType = strtolower(pathinfo($_FILES["categoryPhoto"]["name"], PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowedFileTypes)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        header("Location: ../../../files/categories.php?error='Only JPG, JPEG, PNG & GIF files are allowed.'");
        exit;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        header("Location: ../../../files/categories.php?error='File upload failed. Please try again.'");
        exit;
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["categoryPhoto"]["tmp_name"], $targetDirectory . $_FILES["categoryPhoto"]["name"])) {
            // File uploaded successfully, now insert data into database
            try {
                // Determine the level based on the parent category
                $level = 0;

                if ($parentCategoryId !== null && $parentCategoryId !== "") {
                    // Check if the parent category exists
                    $sqlParent = "SELECT level FROM categories WHERE id = :parentId";
                    $stmtParent = $connection->prepare($sqlParent);
                    $stmtParent->bindParam(':parentId', $parentCategoryId, PDO::PARAM_INT);
                    $stmtParent->execute();
                    $parentLevel = $stmtParent->fetchColumn();

                    if ($parentLevel !== false) {
                        $level = $parentLevel + 1;
                    } else {
                        echo "Error: Parent category does not exist.";
                        header("Location: ../../../files/categories.php?error='Parent category does not exist.'");
                        exit;
                    }
                }

                // Use prepared statement to prevent SQL injection
                $sql = "INSERT INTO categories (name, category_description, parent_category_id, level, category_photo) VALUES (:name, :description, :parentCategoryId, :level, :categoryPhoto)";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':name', $categoryName);
                $stmt->bindParam(':description', $categoryDescription);

                // Check if $parentCategoryId is null and set it to NULL in the database
                if ($parentCategoryId === "") {
                    $parentCategoryId = null;
                }

                $stmt->bindParam(':parentCategoryId', $parentCategoryId, PDO::PARAM_INT);
                $stmt->bindParam(':level', $level, PDO::PARAM_INT);
                $stmt->bindParam(':categoryPhoto', $_FILES["categoryPhoto"]["name"]);

                if ($stmt->execute()) {
                    // Get the last inserted category ID
                    $lastCategoryId = $connection->lastInsertId();

                    // Create a folder based on category ID
                    $categoryFolder = $targetDirectory . $lastCategoryId;

                    if (!file_exists($categoryFolder)) {
                        mkdir($categoryFolder, 0777, true);
                    }

                    // Move the uploaded photo to the category folder
                    $targetFile = $categoryFolder . '/' . $_FILES["categoryPhoto"]["name"];
                    rename($targetDirectory . $_FILES["categoryPhoto"]["name"], $targetFile);

                    // Update the category_photo column with the new file path
                    $sqlUpdate = "UPDATE categories SET category_photo = :categoryPhoto WHERE id = :categoryId";
                    $stmtUpdate = $connection->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':categoryPhoto', $targetFile);
                    $stmtUpdate->bindParam(':categoryId', $lastCategoryId, PDO::PARAM_INT);
                    $stmtUpdate->execute();

                    // Redirect with success parameter
                    header("Location: ../../../files/categories.php?success='Category added successfully!'");
                    exit();
                } else {
                    echo "Error adding category. Please try again.";
                    var_dump($stmt->errorInfo());  // Display more detailed error information
                    header("Location: ../../../files/categories.php?error='Error adding category. Please try again.'");
                    exit;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                header("Location: ../../../files/categories.php?error='Error: " . $e->getMessage() . "'");
                exit;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
            header("Location: ../../../files/categories.php?error='File upload failed. Please try again.'");
            exit;
        }
    }
}

// Close connection
unset($connection);
?>
