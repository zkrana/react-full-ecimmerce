function handleSearch(event) {
  if (event.keyCode === 13) {
    const searchTerm = document.getElementById("searchTerm").value;
    // Implement your search logic and update the results accordingly
    // For demonstration purposes, a simple alert is used here
    alert("Search term: " + searchTerm);
  }
}

function toggleUserDropdown() {
  const userDropdown = document.querySelector(".main-u");

  // Toggle the 'hidden' class to show/hide the user dropdown
  userDropdown.classList.toggle("showDrop");
}

// Add a click event listener to the document to close the dropdown when clicking outside of it
document.addEventListener("click", function (event) {
  const userDropdown = document.querySelector(".main-u");

  // Check if the clicked element is outside the user dropdown
  if (!userDropdown.contains(event.target)) {
    // Close the dropdown by adding the 'hidden' class
    userDropdown.classList.add("hidden");
  }
});

// Hide the dropdown initially
document.addEventListener("DOMContentLoaded", function () {
  const userDropdown = document.querySelector(".main-u");
  userDropdown.classList.add("hidden");
});

console.log("Script is running!"); // Check if this line is logged in the console

// Swiper initialization code
// var swiper = new Swiper(".swiper-container", {
//   slidesPerView: 1,
//   spaceBetween: 10,
//   navigation: {
//     nextEl: ".swiper-button-next",
//     prevEl: ".swiper-button-prev",
//   },
//   autoplay: {
//     duration: 3000,
//   },
//   pagination: {
//     el: ".swiper-pagination",
//     clickable: true,
//   },
// });

// Category
// document.addEventListener("DOMContentLoaded", function () {
//   const categoryHeaders = document.querySelectorAll(".category-header");

//   categoryHeaders.forEach((header) => {
//     const subcategoryList = header.nextElementSibling;

//     if (subcategoryList && subcategoryList.children.length > 0) {
//       // Show plus icon only if there are subcategories
//       const plusIcon = document.createElement("span");
//       plusIcon.className = "toggle-icon text-base font-medium text-gray-400";
//       header.appendChild(plusIcon);

//       header.addEventListener("click", () => {
//         subcategoryList.classList.toggle("expanded");
//         plusIcon.textContent = subcategoryList.classList.contains("expanded")
//           ? "-"
//           : "+";
//       });
//     }
//   });
// });

document.addEventListener("DOMContentLoaded", function () {
  const categoryHeaders = document.querySelectorAll(".category-header");

  categoryHeaders.forEach((header) => {
    const subcategoryList = header.nextElementSibling;

    if (subcategoryList && subcategoryList.children.length > 0) {
      // Show plus icon only if there are subcategories
      const plusIcon = document.createElement("span");
      plusIcon.className =
        "toggle-icon block text-base font-medium text-gray-400";
      header.appendChild(plusIcon);

      header.addEventListener("click", () => {
        subcategoryList.classList.toggle("expanded");
        plusIcon.textContent = subcategoryList.classList.contains("expanded")
          ? "-"
          : "+";
      });
    }
  });
});

function handleFileChange() {
  var fileInput = document.getElementById("photoInput");
  var formData = new FormData();
  formData.append("file", fileInput.files[0]);

  // Make AJAX request to upload the file
  $.ajax({
    type: "POST",
    url: "../../auth/photo-upload.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      // Assuming your PHP script returns the file path upon successful upload
      var imagePath = response.filePath;

      // Display the uploaded image
      displayUploadedPhoto(imagePath);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error uploading file:", textStatus, errorThrown);
    },
  });

  return false; // Prevents default form submission behavior
}

function displayUploadedPhoto(imagePath) {
  var uploadedImageContainer = document.getElementById(
    "uploadedImageContainer"
  );
  var uploadedImage = document.getElementById("uploadedImage");

  // Update image source and display the container
  uploadedImage.src = imagePath;
  uploadedImageContainer.classList.remove("hidden");
}
