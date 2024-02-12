<?php
    // Fetch products from the server (you need to implement this endpoint)
    $productsData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/product/products.php"), true);

    // Display product information
    echo '<div class="mt-7">';
    echo '  <div class="pb-3 border-b border-slate-200">';
    echo '    <h2 class="text-lg font-semibold">New Collections</h2>';
    echo '  </div>';
    echo '  <div class="product-wrapper flex justify-between gap-6">';
    echo '    <div class="w-full mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">';
    
    foreach ($productsData as $product) {
        echo '    <div class="bg-white p-4 rounded-md shadow hover:shadow-lg transition duration-300 ease-in-out relative group">';
        echo '      <img src="http://localhost/reactcrud/backend/auth/assets/products/' . $product['product_photo'] . '" alt="' . $product['name'] . '" class="w-full h-40 object-cover rounded-md mb-4" />';
        echo '      <h2 class="text-xl font-semibold mb-2">' . $product['name'] . '</h2>';
        echo '      <div class="flex flex-col gap-3">';
        echo '        <div class="text-lg font-bold text-blue-600">'. $product['currency_code'] .' ' . $product['price'] .'</div>';
        echo '        <form method="post" action="./files/addToCart.php">';
        echo '          <input type="hidden" name="productId" value="' . $product['id'] . '">';
        echo '          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md 
                    hover:bg-blue-700 transition duration-300 ease-in-out">';
        echo '            Add to Cart';
        echo '          </button>';
        echo '        </form>';
        echo '      </div>';
        echo '    </div>';
    }
    
    echo '  </div>';
    echo ' </div>';
    echo '</div>';
?>
