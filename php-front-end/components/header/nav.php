<!-- mainNav.php -->

<?php

function fetchCategories() {
  $apiEndpoint = "http://localhost/reactcrud/backend/auth/api/categories/categories.php";
  
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
  if (empty($subCategory['subcategories'])) {
    return;
  }

  echo '<ul class="absolute sub-menu left-full top-0 hidden bg-white shadow-md py-2 ml-2">';
  foreach ($subCategory['subcategories'] as $subSubCategory) {
    echo '<li class="relative group">';
    echo '<a href="categories/subcategory/' . $subSubCategory['id'] . '" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition duration-300 cursor-pointer h-11">';
    echo $subSubCategory['name'];
    echo '</a>';
    renderSubMenu($subSubCategory); // Recursively render sub-subcategories
    echo '</li>';
  }
  echo '</ul>';
}

function renderNestedMenu($menu) {
  if (empty($menu)) {
    return null;
  }

  echo '<ul class="absolute sub-sub-menu left-0 top-0 hidden group-hover:block bg-white shadow-md py-2">';
  foreach ($menu as $category) {
    echo '<li class="relative group">';
    echo '<a href="categories/' . $category['id'] . '" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition duration-300 cursor-pointer h-11">';
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
  <ul class="main-menu flex items-center space-x-6 h-full">
    <?php foreach ($categories as $category): ?>
      <li class="relative group">
        <a href="categories/<?php echo $category['id']; ?>" class="flex items-center text-gray-800 hover:text-gray-600 transition duration-300 cursor-pointer h-11">
          <?php echo $category['name']; ?>
        </a>
        <?php renderSubMenu($category); ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

