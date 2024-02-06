<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
</head>
<body>
    <?php include './components/header/header.php'; ?>

    <div class="min-h-screen bg-gray-100 pt-20 pb-14">
        <h1 class="mb-10 text-center text-2xl font-bold">Cart Items</h1>
        <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
            <div class="rounded-lg md:w-2/3" id="cartItemsContainer">
                <!-- Cart items will be dynamically inserted here -->
            </div>
            <!-- Sub total -->
            <div class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
                <div class="mb-2 flex justify-between">
                    <p class="text-gray-700">Subtotal</p>
                    <p class="text-gray-700">$129.99</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-700">Shipping</p>
                    <p class="text-gray-700">$4.99</p>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between">
                    <p class="text-lg font-bold">Total</p>
                    <div class="">
                        <p id="totalPrice" class="mb-1 text-lg font-bold">$134.98 USD</p>
                        <p class="text-sm text-gray-700">including VAT</p>
                    </div>
                </div>
                <button class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600">Check out</button>
            </div>
        </div>
    </div>

    <?php include './components/footer/footer.php'; ?>

    <!-- Include any necessary JavaScript files, especially for fetching and displaying cart data -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Function to fetch product details based on product ID
        function getProductDetails(productId) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: 'files/fetchProductById.php',
                    method: 'POST',
                    data: { productId: productId },
                    success: function(response) {
                        resolve(JSON.parse(response));
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            var cartItems = JSON.parse(localStorage.getItem("cart")) || [];
            var cartItemsContainer = document.getElementById("cartItemsContainer");

            cartItems.forEach(function (item) {
                getProductDetails(item.productId).then(function(productDetails) {
                    if (productDetails) {
                        var card = document.createElement("div");
                        card.className = "cart-item justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start";
                        cartItemsContainer.appendChild(card);

                        var img = document.createElement("img");
                        var productPhotoUrl = "http://localhost/reactcrud/backend/auth/assets/products/" + productDetails.product_photo;
                        img.src = productPhotoUrl;
                        img.alt = productDetails.name;
                        img.className = "w-full rounded-lg sm:w-40";
                        card.appendChild(img);

                        var detailsDiv = document.createElement("div");
                        detailsDiv.className = "sm:ml-4 sm:flex sm:w-full sm:justify-between";
                        card.appendChild(detailsDiv);

                        var infoDiv = document.createElement("div");
                        infoDiv.className = "mt-5 sm:mt-0";
                        detailsDiv.appendChild(infoDiv);

                        var productName = document.createElement("h2");
                        productName.className = "text-lg font-bold text-gray-900";
                        productName.textContent = productDetails.name;
                        infoDiv.appendChild(productName);

                        var productSize = document.createElement("p");
                        productSize.className = "mt-1 text-xs text-gray-700";
                        productSize.textContent = productDetails.description;
                        infoDiv.appendChild(productSize);

                        var quantityPriceDiv = document.createElement("div");
                        quantityPriceDiv.className = "mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6";
                        detailsDiv.appendChild(quantityPriceDiv);

                        var quantityControlDiv = document.createElement("div");
                        quantityControlDiv.className = "flex items-center border-gray-100";
                        quantityPriceDiv.appendChild(quantityControlDiv);

                        var minusBtn = document.createElement("span");
                        minusBtn.className = "cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50";
                        minusBtn.textContent = "-";
                        quantityControlDiv.appendChild(minusBtn);

                        var quantityInput = document.createElement("input");
                        quantityInput.className = "h-8 w-8 border bg-white text-center text-xs outline-none";
                        quantityInput.type = "number";
                        quantityInput.value = item.quantity;
                        quantityInput.min = "1";
                        quantityControlDiv.appendChild(quantityInput);

                        var plusBtn = document.createElement("span");
                        plusBtn.className = "cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50";
                        plusBtn.textContent = "+";
                        quantityControlDiv.appendChild(plusBtn);

                        var priceDiv = document.createElement("div");
                        priceDiv.className = "flex items-center space-x-4";
                        quantityPriceDiv.appendChild(priceDiv);

                        var priceText = document.createElement("p");
                        priceText.className = "text-sm";
                        priceText.textContent = productDetails.price;
                        priceDiv.appendChild(priceText);

                        var deleteIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                        deleteIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                        deleteIcon.setAttribute("fill", "none");
                        deleteIcon.setAttribute("viewBox", "0 0 24 24");
                        deleteIcon.setAttribute("stroke-width", "1.5");
                        deleteIcon.setAttribute("stroke", "currentColor");
                        deleteIcon.setAttribute("class", "h-5 w-5 cursor-pointer duration-150 hover:text-red-500 delete-icon"); // Add the delete-icon class here
                        quantityPriceDiv.appendChild(deleteIcon);

                        var deletePath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                        deletePath.setAttribute("stroke-linecap", "round");
                        deletePath.setAttribute("stroke-linejoin", "round");
                        deletePath.setAttribute("d", "M6 18L18 6M6 6l12 12");
                        deleteIcon.appendChild(deletePath);

                    }
                }).catch(function(error) {
                    console.error('Error fetching product details:', error);
                });
            });
        });

        // Function to update the cart count in the header
        function updateCartCount() {
            // Get cart items from local storage
            var cartItems = JSON.parse(localStorage.getItem("cart")) || [];

            // Calculate total quantity
            var totalQuantity = cartItems.reduce(
                (total, item) => total + item.quantity,
                0
            );

            // Display the total quantity in the header
            document.getElementById("cartCount").textContent = totalQuantity;
        }

        // Function to remove product from cart
        function removeProductFromCart(productId) {
            var cartItems = JSON.parse(localStorage.getItem("cart")) || [];
            var index = cartItems.findIndex(function(item) {
                return item.productId === productId;
            });
            if (index !== -1) {
                cartItems.splice(index, 1);
                localStorage.setItem("cart", JSON.stringify(cartItems));
            }
            // Update the cart count after removing the product
            updateCartCount();
        }

        // Call updateCartCount function when the page loads to update the cart count in the header
        updateCartCount();

        // Rest of your code...

                document.addEventListener("DOMContentLoaded", function () {
                    var cartItemsContainer = document.getElementById("cartItemsContainer");

                    // Check if the cart container is empty
                    if (cartItemsContainer.innerHTML.trim() === "") {
                        // Execute this code only if the cart container is empty

                        var cartItems = JSON.parse(localStorage.getItem("cart")) || [];

                        cartItems.forEach(function (item) {
                            getProductDetails(item.productId).then(function(productDetails) {
                                if (productDetails) {
                                    var card = document.createElement("div");
                                    card.className = "cart-item justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start";
                                    cartItemsContainer.appendChild(card);

                                    // Remaining code to display cart items...
                                }
                            }).catch(function(error) {
                                console.error('Error fetching product details:', error);
                            });
                        });
                    }
                });

                // Event listener for delete icon
        // Event listener for delete icon
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("delete-icon")) {
                // Find the parent cart item element
                var cartItemElement = event.target.closest(".cart-item");
                console.log("Cart Item Element:", cartItemElement);
                // Check if cart item element exists and has children
                if (cartItemElement && cartItemElement.children.length > 0) {
                    // Log all children of the cart item element
                    console.log("Children of Cart Item Element:", cartItemElement.children);
                    
                    // Find the img element
                    var imgElement = cartItemElement.querySelector("img");
                    // Check if img element exists
                    if (imgElement) {
                        // Retrieve the product ID from the alt attribute of the image element
                        var productId = imgElement.alt;
                        console.log("Product ID:", productId);
                        // Remove the product from the cart based on its ID
                        removeProductFromCart(productId);
                        // Remove the entire cart item from the UI
                        cartItemElement.remove();
                    } else {
                        console.error("Image element not found inside cart item element.");
                    }
                } else {
                    console.error("Cart item element not found or has no children.");
                }
            }
        });


    </script>
</body>
</html>
