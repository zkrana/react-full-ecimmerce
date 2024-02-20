<div class="relative">
  <form class="flex" action="" method="get" style="margin-bottom: 0 !important">
    <input
      type="text"
      id="searchQuery" name="q" required
      placeholder="Search products or categories"
      autocomplete="off"
      class="w-full px-4 py-2 border border-gray-800 rounded-l-md focus:outline-none focus:border-blue-500"
    />
    <button
      class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-900 focus:outline-none"
    >
      <i class="fas fa-search"></i>
    </button>
  </form>
  <div id="searchResults" class="hidden absolute w-full bg-white shadow-sm z-20 mt-1 p-4 border border-gray-300 rounded">
      
  </div>

</div>
<script>
const searchForm = document.querySelector('form');
const searchQueryInput = document.getElementById('searchQuery');
const searchResultsDiv = document.getElementById('searchResults');

// Function to update search results
function updateSearchResults() {
    // Get the user's search query
    const searchQuery = searchQueryInput.value;

    // Hide the search results div if the search query is empty
    if (searchQuery.trim() === '') {
        searchResultsDiv.classList.add('hidden');
        return;
    }

    // Make an AJAX request to the PHP script
    fetch(`http://localhost/reactcrud/php-front-end/components/header/searchData.php?q=${searchQuery}`)
        .then(response => response.json())
        .then(results => {
            // Update the page with the search results
            searchResultsDiv.innerHTML = '';

            if ('message' in results && results.message === 'No matching results found.') {
                // Display a message when no results are found
                const noResultsMessage = document.createElement('div');
                noResultsMessage.textContent = results.message;
                searchResultsDiv.appendChild(noResultsMessage);

                // Show the search results container
                searchResultsDiv.classList.remove('hidden');
            } else if (results.categories.length === 0 && results.products.length === 0) {
                // Display a message when no results are found
                const noResultsMessage = document.createElement('div');
                noResultsMessage.textContent = 'No matching results found.';
                searchResultsDiv.appendChild(noResultsMessage);

                // Show the search results container
                searchResultsDiv.classList.remove('hidden');
            } else {
                // Display the results
                results.categories.forEach(category => {
                    const categoryDiv = document.createElement('div');
                    categoryDiv.innerHTML = `<a href="${category.link}">${category.name}</a>`;
                    searchResultsDiv.appendChild(categoryDiv);
                });

                results.products.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.innerHTML = `<a href="${product.link}">${product.name}</a>`;
                    searchResultsDiv.appendChild(productDiv);

                    // Show the search results container
                    searchResultsDiv.classList.remove('hidden');
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Event listener for form submission
searchForm.addEventListener('submit', function (event) {
    event.preventDefault();
    updateSearchResults();
});

// Event listener for input field changes
searchQueryInput.addEventListener('input', updateSearchResults);

// Event listener to hide results when clicking elsewhere on the page
document.addEventListener('click', function (event) {
    if (!searchResultsDiv.contains(event.target) && event.target !== searchQueryInput) {
        searchResultsDiv.classList.add('hidden');
    }
});

</script>
