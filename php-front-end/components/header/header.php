<?php include "userfetch.php"; ?>

<div class="block">
    <?php include 'topbar.php'; ?>
    <div class="w-[90%] mx-auto sm:pb-3 pb-4 border-b border-gray-200 sm:pt-2 py-7 pt-4">
        <div class="flex justify-between items-center">
            <div class="logo w-[20%] text-2xl font-bold text-gray-800">
                <a href="./index.php">Logo</a>
            </div>

            <div class="nav w-[60%] px-20 lg:block hidden">
                <?php include 'search.php'; ?>
            </div>

            <div class="header-actions flex justify-end lg:gap-2 gap-6 items-center sm:w-[20%] w-[60%]">
                <div class="head-ecom-wall w-50 h-10 flex items-center justify-end lg:gap-2 gap-4">
                    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center text-lg relative cursor-pointer">
                        <i class="fa-regular fa-heart  text-xl"></i>
                        <span id="wishlist" class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1">0</span>
                    </div>
                    <?php
                    // Fetch user's IP address
                    function getUserIP()
                    {
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                            return $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            return $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            return $_SERVER['REMOTE_ADDR'];
                        }
                    }

                    // Fetch user's IP address
                    $userIP = getUserIP();

                    // Fetch all cart IDs based on the user's IP address
                    $cartQuery = $connection->prepare("SELECT cart_id FROM cart WHERE ip_address = :ip_address");
                    $cartQuery->bindParam(':ip_address', $userIP);

                    if ($cartQuery->execute()) {
                        $cartIds = $cartQuery->fetchAll(PDO::FETCH_COLUMN);

                        // If cart IDs are found, fetch and display total items
                        if (!empty($cartIds)) {
                            // Fetch and count items for each cart
                            $totalItems = 0;
                            foreach ($cartIds as $cartId) {
                                $cartItemsQuery = $connection->prepare("SELECT COUNT(*) FROM cart_items WHERE cart_id = :cart_id");
                                $cartItemsQuery->bindParam(':cart_id', $cartId);

                                if ($cartItemsQuery->execute()) {
                                    $totalItems += $cartItemsQuery->fetchColumn();
                                } else {
                                    // Handle cart items query error
                                    echo 'Error fetching cart items: ' . $cartItemsQuery->errorInfo()[2];
                                }
                            }

                            // Display the cart icon with the total number of items
                            echo '<a href="cart.php">';
                            echo '    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">';
                            echo '        <i class="fa-solid fa-cart-shopping text-xl"></i>';
                            echo '        <span class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount">' . $totalItems . '</span>';
                            echo '    </div>';
                            echo '</a>';
                        } else {
                            // No cart found for the user's IP address
                            echo '<a href="cart.php">';
                            echo '    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">';
                            echo '        <i class="fa-solid fa-cart-shopping text-xl"></i>';
                            echo '        <span class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount">0</span>';
                            echo '    </div>';
                            echo '</a>';
                        }
                    } else {
                        // Handle cart query error
                        echo 'Error executing cart query: ' . $cartQuery->errorInfo()[2];
                    }
                    ?>


                    <div>
                        <?php if (!$userId) { ?>
                        <a href="./files/userlogin.php" class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">
                            <i class="fa-solid fa-user text-xl"></i>
                        </a>
                        <?php } ?>
                        <?php if ($userId) { ?>
                            <div class="user-p flex items-center justify-end relative">
                                <div class="d-u sm:w-11 w-7 sm:h-11 h-7 rounded-full bg-white border border-gray-200 p-1 flex justify-center items-center text-white cursor-pointer"
                                    onclick="toggleUserDropdown()">
                                    <img src="http://localhost/reactcrud/backend/auth/<?php echo $userPhoto; ?>" alt="User"
                                        class="w-10 h-10 rounded-full object-cover">
                                </div>

                                <?php if ($user) { ?>
                                    <div class="main-u hidden absolute right-0 top-12 w-72 rounded-md px-4 pt-5 pb-4 shadow-md border border-gray-300 bg-white z-50">
                                        <div class="u-header pb-3 border-b border-slate-200">
                                            <?php if ($userPhoto) { ?>
                                                <div class="u-status flex items-center gap-2">
                                                    <div class="u w-11 h-11 bg-slate-400 rounded-full m-1 flex justify-center items-center border-2 border-gray-500">
                                                        <img src="http://localhost/reactcrud/backend/auth/<?php echo $userPhoto; ?>" alt="User" class="w-10 h-10 rounded-full object-cover">
                                                    </div>

                                                    <div class="uer">
                                                        <span class="block text-base text-black">
                                                            <?php echo ucfirst($username); ?>
                                                        </span>
                                                        <span class="block text-base text-black">
                                                            Employee
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="u-status flex items-center gap-2">
                                                    <form action="../../auth/photo-upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                                                        <div class="u-h-d flex gap-3 items-center pb-3 border-b border-slate-200 cursor-pointer">
                                                            <label for="photoInput" class="upload-label">
                                                                Upload Photo
                                                            </label>
                                                            <input type="file" id="photoInput" accept="image/*" class="hidden">
                                                        </div>

                                                        <!-- Display uploaded image -->
                                                        <div id="uploadedImageContainer" class="hidden">
                                                            <img id="uploadedImage" src="" alt="Uploaded Photo">
                                                        </div>
                                                    </form>
                                                    <div class="uer">
                                                        <span class="block text-base text-black">
                                                            <?php echo ucfirst($username); ?>
                                                        </span>
                                                        <span class="block text-base text-black">
                                                            Employee
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <ul className="flex flex-col gap-3 mt-3">
                                            <li>Profile</li>
                                            <li>Security</li>
                                            <li>Book</li>
                                            <li className=" pt-3 border-t border-slate-200">
                                                <a variant="contained" href="components/logout.php">
                                                Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Hamburger Icon for Smaller Screens -->
                <div class="lg:hidden">
                    <!-- You can use an icon library or an inline SVG for the hamburger icon -->
                    <button id="mobileMenuButton" class="text-gray-800 hover:text-gray-600 transition duration-300 focus:outline-none">
                        <!-- Example: Hamburger Icon using inline SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 mt-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="mainNav flex justify-center items-center pt-2">
        <?php include 'nav.php'; ?>
    </div>
</div>
