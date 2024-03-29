<?php
ob_start();
// Include your userfetch.php file
include "userfetch.php";
$baseUrl = 'http://localhost/reactcrud/php-front-end';
// Function to fetch user ID from the session
function getUserID()
{
    // Check if user ID is set in the session
    if (isset($_SESSION['userId'])) {
        return $_SESSION['userId'];
    } else {
        // Return a default value or handle the case when user ID is not set
        return null;
    }
}

// Fetch user's ID
$userID = getUserID();

?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<div class="block">
    <?php include 'topbar.php'; ?>
    <div id="stickyHeader" class="max-w-7xl mx-auto transition-all">
        <div class="w-[90%] mx-auto sm:pb-3 pb-4 border-b border-gray-200 sm:pt-2 py-7 pt-4">
            <div class="flex justify-between items-center">
                <div class="logo w-[20%] text-2xl font-bold text-gray-800">
                    <div class="mb-6 md:mb-0">
                        <a href="<?php echo $baseUrl; ?>" class="flex items-center">
                            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
                            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Ecommerce</span>
                        </a>
                    </div>
                </div>

                <div class="search w-[60%] px-20 lg:block hidden">
                    <?php include 'search.php'; ?>
                </div>

                <div class="header-actions flex justify-end lg:gap-2 sm:gap-6 gap-2 items-center sm:w-[20%] w-[60%]">
                    <div class="head-ecom-wall w-50 h-10 flex items-center justify-end lg:gap-2 sm:gap-4 gap-2">
                        <a href="<?php echo $baseUrl; ?>/wishlist.php" class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center text-lg relative cursor-pointer" id="wishlistIcon">
                            <i class="fa-regular fa-heart text-xl"></i>
                            <span id="wishlistCount" class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1">0</span>
                        </a>
                        <?php
                        // Fetch all cart IDs based on the user's ID
                        if ($userID !== null) {
                            try {
                            $cartQuery = $connection->prepare("SELECT cart_id FROM cart WHERE customer_id = :customer_id");
                                $cartQuery->bindParam(':customer_id', $userID);
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
                                                echo 'Error fetching cart items: ' . implode(' - ', $cartItemsQuery->errorInfo());
                                            }
                                        }

                                        // Display the cart icon with the total number of items
                                        echo '<a href="' . $baseUrl . '/cart.php">';
                                        echo '    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">';
                                        echo '        <i class="fa-solid fa-cart-shopping text-xl"></i>';
                                        echo '        <span class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount">' . $totalItems . '</span>';
                                        echo '    </div>';
                                        echo '</a>';
                                    } else {
                                        // No cart found for the user
                                        echo '<a href="' . $baseUrl . '/cart.php">';
                                        echo '    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">';
                                        echo '        <i class="fa-solid fa-cart-shopping text-xl"></i>';
                                        echo '        <span class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount">0</span>';
                                        echo '    </div>';
                                        echo '</a>';
                                    }
                                } else {
                                    // Handle cart query error
                                    echo 'Error executing cart query: ' . implode(' - ', $cartQuery->errorInfo());
                                }
                            } catch (PDOException $e) {
                                // Handle other database-related errors
                                echo 'Error: ' . $e->getMessage();
                            }
                        } else {
                            // User not logged in
                            echo '<a href="' . $baseUrl . '/cart.php">';
                            echo '    <div class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">';
                            echo '        <i class="fa-solid fa-cart-shopping text-xl"></i>';
                            echo '        <span class="absolute sm:-top-1 -top-3 w-4 text-xs h-4 right-0 flex justify-center items-center text-white bg-[tomato] rounded-full p-1" id="cartCount">0</span>';
                            echo '    </div>';
                            echo '</a>';
                        }
                        ?>

                        <div>
                        <?php if (!$userID) { ?>
                            <a href="<?php echo $baseUrl; ?>/files/userlogin.php" class="sm:w-9 w-5 sm:h-9 h-5 flex justify-center items-center relative cursor-pointer">
                                <i class="fa-solid fa-user text-xl"></i>
                            </a>
                        <?php } ?>
                        <?php if ($userID) { ?>
                            <div class="user-p flex items-center justify-end relative">
                                <div class="d-u sm:w-11 w-7 sm:h-11 h-7 rounded-full bg-white border border-gray-200
                                flex justify-center items-center text-white cursor-pointer"
                                    onclick="toggleUserDropdown()">
                                    <img src="<?php echo $baseUrl; ?>/<?php echo $userID; ?>/<?php echo $userPhoto; ?>" alt="User" class="w-10 h-10 rounded-full object-cover">
                                </div>

                                <?php if ($user) { ?>
                                    <div class="main-u hidden absolute right-0 top-12 w-72 rounded-md px-4 pt-5 pb-4 shadow-md border border-gray-300 bg-white z-50">
                                        <div class="u-header pb-3 border-b border-slate-200">
                                            <?php if ($userPhoto) { ?>
                                                <div class="u-status flex items-center gap-2">
                                                    <div class="u w-11 h-11 bg-slate-400 rounded-full m-1 flex justify-center items-center border-2 border-gray-500">
                                                        <img src="<?php echo $baseUrl; ?>/<?php echo $userID; ?>/<?php echo $userPhoto; ?>" alt="User" class="w-10 h-10 rounded-full object-cover">
                                                    </div>

                                                    <div class="uer">
                                                        <span class="block text-base text-black">
                                                            <?php echo ucfirst($username); ?>
                                                        </span>
                                                        <span class="block text-base text-black">
                                                            user
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="u-status flex items-center gap-2">
                                                    <div class="d-u sm:w-11 w-7 sm:h-11 h-7 rounded-full bg-white border border-gray-200
                                                    flex justify-center items-center text-white">
                                                        <img src="<?php echo $baseUrl; ?>/assets/user-profile/user-profile-icon-vector-avatar-600nw-2247726673.webp" alt="User"
                                                            class="w-full h-full rounded-full object-cover">
                                                    </div>
                                                    <div class="uer">
                                                        <span class="block text-base text-black">
                                                            <?php echo ucfirst($username); ?>
                                                        </span>
                                                        <span class="block text-base text-black">
                                                            User
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <ul class="flex flex-col gap-3 mt-3">
                                            <li> 
                                                <a href="<?php echo $baseUrl; ?>/userprofile.php">Profile</a>
                                            </li>
                                            <li class="pt-3 border-t border-slate-200">
                                                <a variant="contained" href="<?php echo $baseUrl; ?>/components/logout.php">
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
    </div>
    <div class="mainNav max-w-7xl mx-auto py-5">
        <div class="w-[90%] mx-auto flex justify-between items-center">
            <?php include 'nav.php'; ?>
            <div>
                <nav>
                    <ul>
                        <li> <a href="<?php echo $baseUrl; ?>/track-order.php"> Track Order</a> </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <script>
      // Function to toggle user dropdown
  function toggleUserDropdown() {
    const userDropdown = document.querySelector(".main-u");

    // Check if the userDropdown element exists
    if (userDropdown) {
      userDropdown.classList.toggle("showDrop");
    }
  }
    jQuery(document).ready(function ($) {
    // Sticky header functionality
    const header = document.getElementById('stickyHeader');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', function () {
        const currentScrollY = window.scrollY;

        if (currentScrollY > 300) {
        header.classList.add('fixedHead');
        } else {
        header.classList.remove('fixedHead');
        }

        lastScrollY = currentScrollY;
    });
    // Function to update the wishlist count
    function updateWishlistCount() {
        $.ajax({
            type: "GET",
            url: "<?php echo $baseUrl; ?>/files/wishlist_count_endpoint.php",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Update the wishlist count on the page
                    $("#wishlistCount").text(response.wishlistCount);
                } else {
                    console.error("Error fetching wishlist count: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed: " + status + " - " + error);
                console.error(xhr.responseText); // Log the detailed error response
            }
        });
    }
    // Initial call to update wishlist count on page load
    updateWishlistCount();

    // Optionally, set up a click event on the wishlist icon to trigger an update
    $("#wishlistIcon").on("click", function () {
        updateWishlistCount();
    });
    });

  </script>

