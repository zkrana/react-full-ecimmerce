<?php
// Include your database connection code here
// Example assuming you have a $connection variable:
include "../auth/connection/config.php";

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the item ID and new quantity from the POST data
    $itemId = $_POST["itemId"];
    $newQuantity = $_POST["newQuantity"];

    // Validate input (you may want to perform additional validation)
    if (!is_numeric($itemId) || !is_numeric($newQuantity)) {
        // Handle invalid input
        http_response_code(400); // Bad Request
        echo "Invalid input data.";
        exit;
    }

    try {
        // Fetch the current product price from the database
        $getProductPriceQuery = "SELECT product_id, price FROM cart_items WHERE item_id = :itemId";
        $stmtProductPrice = $connection->prepare($getProductPriceQuery);
        $stmtProductPrice->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmtProductPrice->execute();
        $productData = $stmtProductPrice->fetch(PDO::FETCH_ASSOC);

        // Calculate the new subtotal and total price
        $newPrice = $productData["price"] * $newQuantity;

        // Update the quantity, subtotal, and total price in the cart_items table
        $updateCartItemsQuery = "UPDATE cart_items SET quantity = :newQuantity, subtotal = :newPrice, total = :newPrice WHERE item_id = :itemId";
        $stmtCartItems = $connection->prepare($updateCartItemsQuery);
        $stmtCartItems->bindParam(":newQuantity", $newQuantity, PDO::PARAM_INT);
        $stmtCartItems->bindParam(":newPrice", $newPrice, PDO::PARAM_STR); // Assuming total and subtotal are decimal/float
        $stmtCartItems->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmtCartItems->execute();

        // Send a success response
        http_response_code(200); // OK
        echo "Quantity and price updated successfully.";
    } catch (PDOException $e) {
        // Handle database error
        http_response_code(500); // Internal Server Error
        echo "Error updating quantity and price: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
