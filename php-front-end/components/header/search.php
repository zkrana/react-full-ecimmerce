<div class="relative">
  <div class="flex">
    <input
      type="text"
      id="searchTerm"
      placeholder="Search products or categories"
      class="w-full px-4 py-2 border border-gray-800 rounded-l-md focus:outline-none focus:border-blue-500"
    />
    <button
      onclick="handleSearch()"
      class="bg-gray-800 text-white px-4 py-2 rounded-r-md hover:bg-gray-900 focus:outline-none"
    >
      <i class="fas fa-search"></i>
    </button>
  </div>

  <!-- Display search results here -->
  <div id="searchResults" class="mt-4 absolute top-7 left-0 w-full bg-white z-30 p-5 shadow-sm">
    <!-- Results will be dynamically added here using JavaScript -->
  </div>
</div>