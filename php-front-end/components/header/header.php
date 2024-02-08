<?php include "userfetch.php"; ?>

<div class="block">
    <?php include 'topbar.php'; ?>
    <div class="w-[90%] mx-auto pb-3 border-b border-gray-200 pt-2">
        <div class="flex justify-between items-center">
            <div class="logo w-[15%] text-2xl font-bold text-gray-800">
                <a href="/">Logo</a>
            </div>

            <div class="nav w-[60%]">
                <?php include 'search.php'; ?>
            </div>

            <div class="header-actions flex justify-end gap-2 items-center w-[25%]">
                <div class="head-ecom-wall w-50 h-10 flex items-center justify-end gap-2">
                    <div class="w-9 h-9 flex justify-center items-center text-lg relative cursor-pointer">
                        <i class="fa-regular fa-heart  text-xl"></i>
                        <span id="wishlist" class="absolute -top-1 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1">0</span>
                    </div>
                <?php

                // Fetch the total number of items in the cart
                $totalItemsQuery = $connection->prepare("SELECT COUNT(*) AS totalItems FROM cart_items");
                $totalItemsQuery->execute();
                $totalItemsResult = $totalItemsQuery->fetch(PDO::FETCH_ASSOC);
                $totalItems = $totalItemsResult['totalItems'] ?? 0;
                ?>

                <a href="cart.php">
                    <div class="w-9 h-9 flex justify-center items-center relative cursor-pointer">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span class="absolute -top-1 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount"><?php echo $totalItems; ?></span>
                    </div>
                </a>

                <div>
                    <?php if (!$userId) { ?>
                    <a href="./files/userlogin.php">
                        <span class="bg-slate-800 rounded-full py-2 px-4 border-none text-sm text-white hover:bg-slate-900 transition duration-300 cursor-pointer">
                            Signin / Signup
                        </span>
                    </a>
                    <?php } ?>
                        <?php if ($userId) { ?>
                            <div class="user-p flex items-center justify-end relative">
                                <div class="d-u w-11 h-11 rounded-full bg-white border border-gray-200 p-1 flex justify-center items-center text-white cursor-pointer"
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
            </div>
        </div>
    </div>
    <div class="mainNav flex justify-center items-center pt-2">
        <?php include 'nav.php'; ?>
    </div>
</div>
