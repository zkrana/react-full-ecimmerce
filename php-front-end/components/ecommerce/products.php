<?php
// Assume this PHP file is named products.php

// Fetch products from the server (you need to implement this endpoint)
$productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

// Initialize likedProducts with an array of false values
$likedProducts = array_fill(0, count($productsData), false);

// Include your HTML header, if applicable

echo '<div class=" mt-7">';
echo '  <div class=" pb-3 border-b border-slate-200">';
echo '    <h2 class="text-lg font-semibold">New Collections</h2>';
echo '  </div>';
echo '  <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';
foreach ($productsData as $index => $product) {
    echo '    <div class="bg-white p-4 rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group">';
    echo '      <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-cover rounded-md mb-4" />';
    echo '      <h2 class="text-xl font-semibold mb-2">' . $product['name'] . '</h2>';
    echo '      <p class="text-gray-600 mb-4">' . $product['description'] . '</p>';
    echo '      <div class="flex flex-col gap-3">';
    echo '        <div class="text-lg font-bold text-blue-600">${' . $product['price'] . '}</div>';
    echo '        <div class="flex flex-col">';
    echo '          <div class="text-gray-600 w-5 h-5 justify-center items-center hover:text-blue-600 transition duration-300 ease-in-out hidden group-hover:flex absolute top-3 right-3 z-10 cursor-pointer" onclick="handleLikeToggle(' . $index . ')">';
    echo '            <span class="text-lg">' . ($likedProducts[$index] ? '❤️' : '🤍') . '</span>';
    echo '          </div>';
    echo '          <button class="hidden group-hover:block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">';
    echo '            Add to Cart';
    echo '          </button>';
    echo '        </div>';
    echo '      </div>';
    echo '    </div>';
}
echo '  </div>';
echo '</div>';

// Include your HTML footer, if applicable
?>
