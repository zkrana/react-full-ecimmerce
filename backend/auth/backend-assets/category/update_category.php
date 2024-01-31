<?php
require_once "../../db-connection/config.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Handle category name and description update
    $categoryName = isset($_POST["categoryName"]) ? $_POST["categoryName"] : null;
    $categoryDescription = isset($_POST["categoryDescription"]) ? $_POST["categoryDescription"] : null;

    // Update category details in the database only if provided
    if ($categoryName !== null || $categoryDescription !== null) {
        $sqlUpdateDetails = "UPDATE categories SET";
        if ($categoryName !== null) {
            $sqlUpdateDetails .= " name = :categoryName";
        }
        if ($categoryDescription !== null) {
            $sqlUpdateDetails .= " , category_description = :categoryDescription";
        }
        $sqlUpdateDetails .= " WHERE id = :category_id";

        $stmtUpdateDetails = $connection->prepare($sqlUpdateDetails);

        if ($categoryName !== null) {
            $stmtUpdateDetails->bindParam(":categoryName", $categoryName, PDO::PARAM_STR);
        }
        if ($categoryDescription !== null) {
            $stmtUpdateDetails->bindParam(":categoryDescription", $categoryDescription, PDO::PARAM_STR);
        }

        $stmtUpdateDetails->bindParam(":category_id", $category_id, PDO::PARAM_INT);

        $stmtUpdateDetails->execute();
    }

    // Check if category id folder exists, if not, create it
    $categoryFolderPath = "../../assets/categories/" . $category_id;
    if (!file_exists($categoryFolderPath)) {
        mkdir($categoryFolderPath, 0777, true);
    }

    // Handle category photo update
if (isset($_FILES["categoryPhoto"]) && $_FILES["categoryPhoto"]["error"] == UPLOAD_ERR_OK) {
    $oldPhotoPath = $categoryFolderPath . "/" . $category['category_photo'];
    if (file_exists($oldPhotoPath)) {
        unlink($oldPhotoPath);
    }

    $newFileName = uniqid() . "_" . basename($_FILES["categoryPhoto"]["name"]);
    $newPhotoPath = $categoryFolderPath . "/" . $newFileName;

    move_uploaded_file($_FILES["categoryPhoto"]["tmp_name"], $newPhotoPath);

    // Update the category photo file name with the relative path in the database
    $relativePath = "../../assets/categories/" . $category_id . "/";
    $sqlUpdatePhoto = "UPDATE categories SET category_photo = :relativePath :newFileName WHERE id = :category_id";
    $stmtUpdatePhoto = $connection->prepare($sqlUpdatePhoto);
    $stmtUpdatePhoto->bindParam(":relativePath", $relativePath, PDO::PARAM_STR);
    $stmtUpdatePhoto->bindParam(":newFileName", $newFileName, PDO::PARAM_STR);
    $stmtUpdatePhoto->bindParam(":category_id", $category_id, PDO::PARAM_INT);
    $stmtUpdatePhoto->execute();
}

    header("Location: ../../../files/categories.php?success='Category updated successfully!'");
    exit();
} else {
    header("Location: ../../../files/categories.php");
    exit();
}
?>
