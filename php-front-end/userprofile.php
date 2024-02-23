
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <?php
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    // Assuming $userId is set from your session
    if ($userId === null) {
        header("Location: index.php");
        exit;
    }
    ?>
    <div class="container">
        <div class="min-h-screen w-full sm:max-w-7xl mx-auto sm:pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">User Profile.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <span class="text-gray-600">User Profile</span>
            </div>
        </div>

        <?php

        // Check if the user is logged in
        if (isset($_SESSION['userId'])) {
            // Fetch user data based on the user's ID
            $userId = $_SESSION['userId'];

            // Fetch user data
            $userSql = "SELECT * FROM `customers` WHERE `id` = :userId";
            $stmtUser = $connection->prepare($userSql);
            $stmtUser->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtUser->execute();

            // Fetch user data as an associative array
            $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

            // Use basename to get just the filename
            $photoFilename = basename($userData['photo']);

            // Fetch summarized order history
            $orderSummaryQuery = "
            SELECT o.id AS order_id, COUNT(oi.id) AS item_count, 
            SUM(oi.quantity) AS total_quantity, SUM(oi.total_price) AS total_price,
            os.status_name
                FROM orders AS o
                JOIN order_items AS oi ON o.id = oi.order_id
                LEFT JOIN order_status AS os ON o.order_status_id = os.id
                WHERE o.user_id = :userId
                GROUP BY o.id
                ORDER BY o.order_date DESC
            ";

            $stmtOrderSummary = $connection->prepare($orderSummaryQuery);
            $stmtOrderSummary->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtOrderSummary->execute();

            $orderSummary = $stmtOrderSummary->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // User is not logged in, redirect to login page
            header("Location: login.php");
            exit;
        }
        ?>

        <div class="flex gap-8">
            <div class="md:w-1/3 bg-white p-6 border-r border-slate-200">
                <?php
                    // Assuming $userData is an associative array containing user data fetched from the database
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

                    // Check if user data is found
                    if ($userData) {
                        $decryptedEmail = xorDecrypt($userData["email"], 'shTYTS,os(**0te455432%3sgks$#SG');
                ?>
                <div class="mb-4">
                    <?php
                        // Check if the user has a photo
                        if (!empty($userData['photo'])) {
                    ?>
                    <div class="w-20 h-20 rounded-sm overflow-hidden">
                        <img src="http://localhost/reactcrud/php-front-end/assets/user-profile/<?php echo $userData['id']; ?>/<?php echo $photoFilename; ?>" alt="User Photo" class="w-full h-auto rounded">
                    </div>
                    <?php
                        } else {
                    ?>
                        <div class="w-full h-auto rounded bg-gray-300 text-center py-6">
                            <p class="text-gray-500">Upload Photo</p>
                            <form action="files/userPhotoUpload.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="userPhoto">
                                <input type="submit" class="bg-slate-900 p-2 rounded-sm text-white" value="Upload">
                            </form>
                        </div>
                    <?php
                        }
                    ?>
                </div>

                <h2 class="text-2xl font-semibold mb-4">User Information</h2>
                <p><span class="font-semibold">Name:</span> <?php echo $userData['first_name'] . ' ' . $userData['last_name']; ?></p>
                <p><span class="font-semibold">Email:</span> <?php echo $decryptedEmail; ?></p>
                <p><span class="font-semibold">Address:</span> <?php echo $userData['billing_address'] . ', ' . $userData['city']; ?></p>
                <p><span class="font-semibold">Phone:</span> <?php echo $userData['phone_number']; ?></p>
                <a variant="contained" class=" outline ring-1 text-lg inline-block py-1 px-5 text-slate-600 rounded-sm mt-3" href="/reactcrud/php-front-end/components/logout.php">
                    Logout
                </a>
                <?php
                    } else {
                        // User not found, handle accordingly (redirect to login page or display an error)
                        header("Location: login.php");
                        exit;
                    }
                ?>
            </div>

            <div class="md:w-2/3 bg-white p-6 rounded shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Order History</h2>
                <?php if (!empty($orderSummary)) : ?>
                    <ul class="divide-y divide-gray-300">
                        <?php foreach ($orderSummary as $order) : ?>
                            <li class="py-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold"><span class="bg-slate-500 rounded-sm p-1">Order #</span> <?php echo $order['order_id']; ?></p>
                                        <p class="mt-2">
                                            <span class="text-slate-400 rounded-sm p-1">Items:</span>     
                                            <?php echo $order['item_count']; ?>
                                        </p>
                                        <p class="mt-2">
                                            <span class="text-slate-400 rounded-sm p-1">quantity</span>     
                                            <?php echo $order['total_quantity']; ?>
                                        </p>
                                    </div>
                                    <div>
                                    <p class="text-gray-500">Status: <span class="text-white py-1 px-2 rounded-sm bg-[tomato]">
                                        <?php echo isset($order['status_name']) ? $order['status_name'] : 'N/A'; ?>
                                    </span></p>

                                    </div>
                                </div>
                                <div class="mt-2">
                                    <?php 
                                    if ($order['status_name'] === "Pending") {
                                        echo '<a href="cancelOrder.php?orderId=' . $order['order_id'] . '" class="text-slate-100 hover:underline mt-3 inline-block p-2 rounded-sm bg-[tomato]">Cancel Order</a>';
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="text-gray-500">No order history available.</p>
                <?php endif; ?>
            </div>
        </div>
     </div>
    </div>

    <?php include './components/footer/footer.php'; ?>

</body>
</html>