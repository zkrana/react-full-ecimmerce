function handleSearch() {
  const searchTerm = document.getElementById("searchTerm").value;
  // Implement your search logic and update the results accordingly
  // For demonstration purposes, a simple alert is used here
  alert("Search term: " + searchTerm);
}
// main.js

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
