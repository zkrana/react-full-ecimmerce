<?php
// Assume this PHP file is named CategoryList.php

// Fetch categories from the server (you need to implement this endpoint)
$categoriesData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/categories/categories.php"), true);

// Include your HTML header, if applicable

echo '<div class="flex flex-wrap gap-8">';
$categoriesToShow = ['Men\'s & Boy\'s Fashion', 'Women\'s & Girl\'s Fashion', 'Kids', 'Health & Beauty'];

foreach ($categoriesData as $category) {
    // Check if the current category is in the list of categories to show
    if (in_array($category['name'], $categoriesToShow)) {
        echo '    <div class="ecom-cat-item w-[calc(25%-24px)] flex space-x-4 justify-between rounded p-3 border border-slate-200">';
        echo '      <div class="ecom-cat-photo w-32 h-32 rounded-sm border border-slate-200 bg-gray-300 flex justify-center items-center">';
        echo '        <img src="http://localhost/reactcrud/backend/auth/assets/categories/' . $category['id'] . '/' . $category['photo_name'] . '" alt="Category Image: ' . $category['name'] . '" class="w-full h-full object-fill" />';
        echo '      </div>';
        echo '      <div class="ecom-cat-d flex flex-col justify-between w-[calc(100%-128px)]">';
        echo '        <div class="ecom-cat-header flex justify-between">';
        echo '          <span class="block w-1/2 text-base font-medium">' . $category['name'] . '</span>';
        echo '          <span class="block w-1/2 text-gray-400 text-sm text-right">(' . calculateTotalProductCount($category) . ($category['product_count'] !== 1 ? '' : '') . ')</span>';
        echo '        </div>';
        echo '    <a href="/reactcrud/php-front-end/categories/singleCategory.php?category_id=' . $category['id'] . '" class="inline-block text-red-300">Show All</a>';
        echo '      </div>';
        echo '    </div>';
    }
}
echo '</div>';

// Include your HTML footer, if applicable

function calculateTotalProductCount($category) {
    $totalProductCount = $category['product_count'] ?? 0;

    if (isset($category['subcategories']) && is_array($category['subcategories']) && count($category['subcategories']) > 0) {
        foreach ($category['subcategories'] as $subcategory) {
            $totalProductCount += calculateTotalProductCount($subcategory);
        }
    }

    return $totalProductCount;
}
?>
