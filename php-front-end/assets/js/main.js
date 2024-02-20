function handleSearch(event) {
  if (event.keyCode === 13) {
    const searchTerm = document.getElementById("searchTerm").value;
    // Implement your search logic and update the results accordingly
    // For demonstration purposes, a simple alert is used here
    alert("Search term: " + searchTerm);
  }
}
// Mobile Menu
document.addEventListener("DOMContentLoaded", function () {
  function mobileMenuButton() {
    const mainMenu = document.getElementById("mainMenu");
    mainMenu.classList.toggle("hidden");
    const menuClose = document.getElementById("closeNavMb");
    menuClose.classList.toggle("MbNavMenuShow");
  }

  // Attach the event listener to the button
  const button = document.getElementById("mobileMenuButton");
  button.addEventListener("click", mobileMenuButton);
});

// Function to toggle user dropdown// Hide the dropdown initially
document.addEventListener("DOMContentLoaded", function () {
  const userDropdown = document.querySelector(".main-u");

  // Check if the userDropdown element exists
  if (userDropdown) {
    userDropdown.classList.add("hidden");
  }
});

document.addEventListener("click", function (event) {
  const userDropdown = document.querySelector(".main-u");

  if (userDropdown) {
    if (!userDropdown.contains(event.target)) {
      userDropdown.classList.add("hidden");
    }
  }
});

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
