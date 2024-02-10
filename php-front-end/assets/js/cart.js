// Function to remove cart item
function removeCartItem(itemId) {
  // Send AJAX request to remove cart item
  $.ajax({
    url: "./files/removeCartItem.php",
    type: "POST",
    data: { itemId: itemId },
    success: function (response) {
      // Parse the JSON response
      var data = JSON.parse(response);

      // If removal is successful, remove the cart item from the UI
      if (data.success) {
        $('.cart-item[data-item-id="' + itemId + '"]').remove();
        // Show success modal
        showCartRemovedModal();
      } else {
        // Display error message if removal was not successful
        alert("Error: " + data.message);
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
}

// Event listener for clicking the remove button
$(document).ready(function () {
  $(document).on("click", "#removeCart", function (e) {
    // Prevent the default behavior of the click event
    e.preventDefault();

    // Get the item ID of the cart item to remove
    var itemId = $(this).closest(".cart-item").data("item-id");

    // Call the removeCartItem function with a delay
    removeCartItemWithDelay(itemId);
  });
});

// Function to remove cart item with a delay
function removeCartItemWithDelay(itemId) {
  // Call the removeCartItem function
  removeCartItem(itemId);

  // Delay the page reload after a short delay (adjust the delay as needed)
  setTimeout(function () {
    location.reload();
  }, 1000); // 1000 milliseconds = 1 second
}

// Function to close the modal
function closeCartRemovedModal() {
  document.getElementById("cartRemovedModal").classList.add("hidden");
}

// Function to show the modal
function showCartRemovedModal() {
  document.getElementById("cartRemovedModal").classList.remove("hidden");
}
$(document).on("click", ".cursor-pointer.rounded-r", function () {
  // Increment the quantity
  var input = $(this).siblings("input.quantity-input");
  var currentValue = parseInt(input.val());
  if (!isNaN(currentValue)) {
    input.val(currentValue + 1);
    input.trigger("change"); // Trigger change event to update price
  }
});

$(document).on("click", ".cursor-pointer.rounded-l", function () {
  // Decrement the quantity
  var input = $(this).siblings("input.quantity-input");
  var currentValue = parseInt(input.val());
  if (!isNaN(currentValue) && currentValue > 1) {
    input.val(currentValue - 1);
    input.trigger("change"); // Trigger change event to update price
  }
});

// Function to update subtotal and total price
function updatePrice(itemId, newQuantity) {
  // Find the cart item with the given itemId
  var cartItem = $('.cart-item[data-item-id="' + itemId + '"]');

  // Get the original price from the hidden element in the cart item
  var originalPriceString = cartItem.find(".original-price").text();
  var originalPrice = parseFloat(originalPriceString.replace("$", ""));

  // Update the quantity in the UI
  cartItem.find(".quantity-input").val(newQuantity);

  // Calculate the new subtotal
  var subtotal = originalPrice * newQuantity;

  // Update the subtotal in the UI
  cartItem.find(".text-sm").text("$" + subtotal.toFixed(2));

  // Recalculate the subtotal of all items
  var newSubtotal = 0;
  $(".text-sm").each(function () {
    var subtotalValue = parseFloat($(this).text().replace("$", ""));
    if (!isNaN(subtotalValue)) {
      newSubtotal += subtotalValue;
    }
  });

  // Update the subtotal price in the UI
  $("#subtotalPrice").text("$" + newSubtotal.toFixed(2));

  // Calculate the total price including shipping
  var shippingCost = 4.0; // Assuming the shipping cost is $4.00
  var total = newSubtotal + shippingCost;

  // Update the total price in the UI
  $("#totalPrice").text("$" + total.toFixed(2) + " USD");
}

function handleUpdateCart(itemId, cartId) {
  var newQuantity = parseInt($(".quantity-input").val());

  $.ajax({
    type: "POST",
    url: "./files/update_cart.php",
    data: { itemId: itemId, newQuantity: newQuantity, cartId: cartId }, // Include cartId in the data
    success: function (response) {
      console.log(response);
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    },
  });
}

// Event listener for changing quantity
$(document).ready(function () {
  $(document).on("change", ".quantity-input", function () {
    // Get the new quantity
    var newQuantity = parseInt($(this).val());
    if (isNaN(newQuantity) || newQuantity < 1) {
      newQuantity = 1;
      $(this).val(1);
    }

    // Get the item ID
    var itemId = $(this).closest(".cart-item").data("item-id");

    // Update the price
    updatePrice(itemId, newQuantity);
  });
});

// Loggin Check
function checkout() {
  console.log("Checkout button clicked.");

  // Check if the checkout button is disabled
  if ($("#checkoutButton").prop("disabled")) {
    alert("Your cart is empty. Add items to proceed.");
    return;
  }

  // Check user login status using an AJAX request
  $.ajax({
    url: "./files/checkLogin.php",
    type: "GET",
    success: function (response) {
      console.log("AJAX success:", response);
      if (response === "loggedIn") {
        console.log("User logged in. Redirecting to checkout page.");
        window.location.href = "./checkout.php";
      } else {
        console.log("User not logged in. Redirecting to login page.");
        window.location.href = "./files/userlogin.php";
      }
    },
    error: function (xhr, status, error) {
      console.error("Error checking login status:", error);
    },
  });
}
