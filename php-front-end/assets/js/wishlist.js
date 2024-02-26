$(document).ready(function () {
  // AJAX function to remove wishlist item
  $(".remove-wishlist").on("click", function () {
    var wishlistItemId = $(this).data("wishlist-id");
    var wishlistItem = $("[data-wishlist-id='" + wishlistItemId + "']");

    console.log("Removing wishlist item with ID:", wishlistItemId);

    $.ajax({
      type: "POST",
      url: "./files/removeWishlistItem.php", // Replace with your actual backend endpoint
      data: { wishlistItemId: wishlistItemId },
      success: function (response) {
        console.log("AJAX Response:", response);

        // Check if response is a JSON string and parse it
        try {
          var jsonResponse = JSON.parse(response);
          if (jsonResponse.success) {
            alert("Wishlist item removed successfully.");
            // If successful, smoothly fade out the corresponding HTML element
            wishlistItem.fadeOut("normal", function () {
              wishlistItem.remove();
              console.log("Wishlist item HTML removed.");

              // Delay the page reload after a short delay (adjust the delay as needed)
              setTimeout(function () {
                location.reload();
              }, 500); // 500 milliseconds = 0.5 seconds
            });
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
