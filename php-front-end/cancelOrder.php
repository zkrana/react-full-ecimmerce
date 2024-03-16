<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <div class="w-[90%] sm:max-w-7xl mx-auto sm:pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Cancel Order.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="<?php echo $baseUrl; ?>" class="focus:outline-none hover:underline text-gray-500">Home</a> / <span class="text-gray-600">Cancel Order</span>
            </div>
        </div>
        <div class="mt-7">
            <?php
            // Define xorDecrypt function
            function xorDecrypt($input, $key) {
                // Decode the base64-encoded input
                $decodedInput = base64_decode($input);
                // XOR decryption
                $decrypted = '';
                $keyLength = strlen($key);

                for ($i = 0; $i < strlen($decodedInput); $i++) {
                    $decrypted .= $decodedInput[$i] ^ $key[$i % $keyLength];
                }
                return $decrypted;
            }

            // Fetch data based on the customerId
            if(isset($_SESSION['userId'])) {
                $customerId = $_SESSION['userId'];
                
                // Prepare and execute SQL query to fetch order data
                $sql = "SELECT orders.id AS order_id, customers.username AS customer_name, customers.email AS customer_email
                        FROM orders
                        INNER JOIN customers ON orders.user_id = customers.id
                        WHERE customers.id = :customerId";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
                $stmt->execute();
                // Fetch and assign fetched data to variables
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row) {
                    $orderId = $row['order_id'];
                    $customerName = $row['customer_name'];
                    $customerEmail = $row['customer_email'];
                    // Decrypt email
                    $decryptedEmail = xorDecrypt($row['customer_email'], 'shTYTS,os(**0te455432%3sgks$#SG');
                }
                else {
                    echo "No order found for the provided customer ID.";
                    exit;
                }
            }
            else {
                echo "No customer ID provided.";
                exit;
            }
            ?>
           <div class="max-w-md mx-auto mb-4">
                <div class="action-response">
                    <?php
                    // Check for error or success messages in URL parameters
                    if (isset($_GET['error'])) {
                        // Display error message
                        $error = $_GET['error'];
                        echo '<div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong>Error:</strong> ' . $error . '
                            </div>';
                        echo '<script>
                            setTimeout(function() {
                                window.location.href = window.location.pathname + "?customerId=' . $customerId . '";
                            }, 5000);
                        </script>';
                    } elseif (isset($_GET['success'])) {
                        // Display success message
                        $success = $_GET['success'];
                        echo '<div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <strong>Success:</strong> ' . $success . '
                            </div>';
                        echo '<script>
                                setTimeout(function() {
                                    window.location.href = window.location.pathname + "?customerId=' . $customerId . '";
                                }, 5000);
                            </script>';
                    }
                    ?>
                </div>

            </div>
            <form action="./files/cancelOrderSubmit.php" method="post">
                <div class="max-w-md mx-auto bg-white shadow-md p-8 rounded-md">
                    <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
                    <input type="hidden" name="customerName" value="<?php echo $customerName; ?>">
                    <input type="hidden" name="customerEmail" value="<?php echo $decryptedEmail; ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Order ID:</label>
                        <div class="chek-wrapper-oid flex gap-3 flex-wrap">
                            <?php
                            // Fetch order IDs based on the provided customer ID
                            // Prepare and execute SQL query to fetch order IDs
                            $sql = "SELECT id FROM orders WHERE user_id = :customerId";
                            $stmt = $connection->prepare($sql);
                            $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
                            $stmt->execute();

                            // Fetch and display order IDs as checkboxes
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $orderId = $row['id'];
                                echo "<div class='mb-2 flex items-center gap-2 ring-1 p-1 rounded-sm'>
                                    <input type='checkbox' class='mt-1' name='orderId[]' value='$orderId'>$orderId</div>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="customerName">Customer Name:</label>
                        <input id="customerName" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2" value="<?php echo $customerName; ?>" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="customerEmail">Customer Email:</label>
                        <input id="customerEmail" type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2" value="<?php echo $decryptedEmail; ?>" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="reason">Reason for Cancellation:</label>
                        <select id="reason" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2 bg-transparent" name="reason">
                            <option disabled>Select a reason</option>
                            <option value="customer_request">Customer Request</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="refund">Refund</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="text-sm text-gray-500 mb-4">
                        For refund requests, please read our <a href="refund_policy.php" class="text-indigo-500 underline">refund policy</a>.
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" for="comments">Additional Comments/Notes:</label>
                        <textarea id="comments" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2" rows="3" placeholder="Enter any additional comments or notes" name="comments"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-500 text-white rounded-md py-2 hover:bg-indigo-600 transition duration-300">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include './components/footer/footer.php'; ?>
<script src="./assets/js/wishlist.js"></script>
<script>
    // Function to remove the message element after 5 seconds
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        var successMessage = document.getElementById('success-message');

        if (errorMessage) {
            errorMessage.remove();
        }

        if (successMessage) {
            successMessage.remove();
        }
    }, 5000); // Remove after 5 seconds (5000 milliseconds)
</script>
</body>
</html>