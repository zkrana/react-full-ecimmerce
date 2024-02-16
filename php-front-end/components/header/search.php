<div class="relative">
  <form class="flex" action="" method="get">
    <input
      type="text"
      id="searchQuery" name="q" required
      placeholder="Search products or categories"
      class="w-full px-4 py-2 border border-gray-800 rounded-l-md focus:outline-none focus:border-blue-500"
    />
    <button
      class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-900 focus:outline-none"
    >
      <i class="fas fa-search"></i>
    </button>
  </form>
  <div id="searchResults" class="hidden absolute w-full bg-white shadow-sm z-20 mt-4 p-4 border border-gray-300 rounded">
      
  </div>

</div>
<script>
document.querySelector('form').addEventListener('submit', function (event) {
    event.preventDefault();

    // Get the user's search query
    const searchQuery = document.getElementById('searchQuery').value;

    // Make an AJAX request to the PHP script
    fetch(`components/header/searchData.php?q=${searchQuery}`)
        .then(response => response.json())
        .then(results => {
            // Update the page with the search results
            const searchResultsDiv = document.getElementById('searchResults');
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
});

</script>


