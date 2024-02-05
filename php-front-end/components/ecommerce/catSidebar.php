<div id="categoryList" class="p-4 border border-slate-200 rounded">
  <?php
    function fetchDataFromAPI($apiUrl) {
      $json = file_get_contents($apiUrl);
      return json_decode($json, true);
    }

    function renderCategories($categories) {
      echo '<ul>';
      foreach ($categories as $category) {
        echo '<li>';
        echo '<div class="category-header space-y-2 w-full flex justify-between items-center">';
        echo '<span class="category-name block">' . $category['name'] . '</span>';
        if (!empty($category['subcategories'])) {
          echo '<span class="toggle-icon block text-base font-medium text-gray-400">+</span>';
          echo '<ul class="subcategory-list">';
          renderCategories($category['subcategories']);
          echo '</ul>';
        }
        echo '</div>';
        echo '</li>';
      }
      echo '</ul>';
    }

    $categories = fetchDataFromAPI("http://localhost/reactcrud/backend/auth/api/categories/categories.php");
    renderCategories($categories);
  ?>
</div>


