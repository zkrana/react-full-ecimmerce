$(document).ready(function () {
  // AJAX function to remove wishlist item
  $(".remove-wishlist").on("click", function () {
    var wishlistItemId = $(this).data("wishlist-id");

    $.ajax({
      type: "POST",
      url: "./files/removeWishlistItem.php", // Replace with your actual backend endpoint
      data: { wishlistItemId: wishlistItemId },
      success: function (response) {
        console.log(response); // Log the entire response object for debugging

        // Check if response is a JSON string and parse it
        try {
          var jsonResponse = JSON.parse(response);
          if (jsonResponse.success) {
            // If successful, remove the corresponding HTML element
            $("[data-item-id='" + wishlistItemId + "']").remove();
          } else {
            // Handle error (e.g., show an alert)
            console.error(
              "Error removing wishlist item: " + jsonResponse.message
            );
          }
        } catch (error) {
          console.error("Error parsing JSON response: " + error);
        }
      },
      error: function () {
        console.error("Error during AJAX request");
      },
    });
  });
});

// Event listener for clicking the remove button in the cart
$(document).on("click", "#removeWishlist", function (e) {
  // Prevent the default behavior of the click event
  e.preventDefault();

  // Get the item ID of the cart item to remove
  var itemId = $(this).closest(".cart-item").data("item-id");
  // Call the removeCartItem function
  removeCartItem(itemId);
});
