<?php
function fetchCategories() {
  $apiEndpoint = "http://localhost/reactcrud/backend/auth/api/categories/categories.php";
  global $baseUrl;
  // Perform a cURL request to the API endpoint
  $ch = curl_init($apiEndpoint);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);

  // Check for cURL errors
  if (curl_errno($ch)) {
    echo 'Error fetching categories: ' . curl_error($ch);
    exit;
  }

  curl_close($ch);

  // Decode the JSON response
  $data = json_decode($response, true);

  return $data; // Adjust this based on your API response structure
}

function renderSubMenu($subCategory) {
  global $baseUrl;
  if (empty($subCategory['subcategories'])) {
    return;
  }

  echo '<ul class="absolute sub-menu left-full top-0 hidden bg-white shadow-md py-2 ml-2">';
  foreach ($subCategory['subcategories'] as $subSubCategory) {
    echo '<li class="relative group">';
    echo '<a href="'. $baseUrl .'/categories/singleCategory.php?category_id=' . $subSubCategory['id'] . '" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition duration-300 cursor-pointer h-11">';
    echo $subSubCategory['name'];
    echo '</a>';
    renderSubMenu($subSubCategory); // Recursively render sub-subcategories
    echo '</li>';
  }
  echo '</ul>';
}

function renderNestedMenu($menu) {
  global $baseUrl;
  if (empty($menu)) {
    return null;
  }

  echo '<ul class="absolute sub-sub-menu left-0 top-0 hidden group-hover:block bg-white shadow-md py-2">';
  foreach ($menu as $category) {
    echo '<li class="relative group">';
    echo '<a href="'. $baseUrl .'/categories/singleCategory.php?category_id=' . $category['id'] . '" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition duration-300 cursor-pointer h-11">';
    echo $category['name'];
    echo '</a>';

    renderSubMenu($category); // Recursively render subcategories
    echo '</li>';
  }
  echo '</ul>';
}

$categories = fetchCategories();
// var_dump($categories);


?>


<?php if (isset($categories) && is_array($categories)): ?>
  <nav class="main-menu-container lg:block flex items-center">
    <!-- Main Menu -->
    <ul id="mainMenu" class="main-menu lg:static fixed bottom-0 left-0 p-5 lg:p-0 h-[calc(100vh-90px)] lg:h-auto bg-white lg:bg-transparent w-full lg:flex flex-col items-center lg:flex-row lg:space-x-6 hidden">
      <?php foreach ($categories as $category): ?>
        <li class="relative group">
          <a href="<?php echo $baseUrl; ?>/categories/singleCategory.php?category_id=<?php echo $category['id']; ?>" class="flex items-center text-gray-800 hover:text-gray-600 transition duration-300 cursor-pointer">
            <?php echo $category['name']; ?>
          </a>
          <?php renderSubMenu($category); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <div id="closeNavMb" class="w-10 h-10 cursor-pointer hidden justify-center items-center text-lg text-white absolute z-[999999] right-4 top-4 bg-slate-600">
      <i class="fa-solid fa-xmark "></i>
    </div>
  </nav>
<?php endif; ?>

