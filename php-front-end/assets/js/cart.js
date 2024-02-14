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

// Loggin Check
function checkout() {
  // Check if the checkout button is disabled
  if ($("#checkoutButton").prop("disabled")) {
    alert("Your cart is empty. Add items to proceed.");
    return;
  }
}
