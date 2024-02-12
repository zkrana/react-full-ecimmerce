<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assets/styling/style.css">
    <script src="../assets/styling/style.css"></script>
</head>

<body>
    <?php include '../components/header/header.php'; ?>
    <div class="container">
        <div class="w-full sm:max-w-7xl mx-auto my-10">
            <div class="pb-3 border-b border-slate-200">
                <h2 class="text-lg font-semibold"> All Categories</h2>
            </div>
            <?php
            // Fetch categories from the server (you need to implement this endpoint)
            $categoriesData = json_decode(file_get_contents("http://localhost/reactcrud/backend/auth/api/categories/categories.php"), true);

            echo '<div class="flex flex-wrap gap-8 mt-10">';
            renderCategories($categoriesData);
            echo '</div>';

            function renderCategories($categories)
            {
                foreach ($categories as $category) {
                    echo '<div class="ecom-cat-item xl:w-[calc(25%-24px)] lg:w-[calc(33.3333%-24px)] sm:w-[calc(50%-24px)] w-full mb-8 flex space-x-4 justify-between rounded p-3 border border-slate-200">';
                    echo '  <div class="ecom-cat-photo w-32 h-32 lg:w-40 lg:h-40 rounded-sm border border-slate-200 bg-gray-300 flex justify-center items-center">';
                    echo '    <img src="http://localhost/reactcrud/backend/auth/assets/categories/' . $category['id'] . '/' . $category['photo_name'] . '" alt="Category Image: ' . $category['name'] . '" class="w-full h-full object-fill" />';
                    echo '  </div>';
                    echo '  <div class="ecom-cat-d flex flex-col justify-between w-[calc(100%-128px)]">';
                    echo '    <div class="ecom-cat-header flex justify-between">';
                    echo '      <span class="block w-1/2 text-base lg:text-lg font-medium">' . $category['name'] . '</span>';
                    echo '      <span class="block w-1/2 text-gray-400 text-sm lg:text-base text-right">(' . $category['product_count'] . ($category['product_count'] !== 1 ? '' : '') . ')</span>';
                    echo '    </div>';
                echo '    <a href="/reactcrud/php-front-end/categories/singleCategory.php?category_id=' . $category['id'] . '" class="inline-block text-red-300">Show All</a>';
                    echo '  </div>';
                    echo '</div>';

                    if (isset($category['subcategories']) && is_array($category['subcategories']) && count($category['subcategories']) > 0) {
                        renderCategories($category['subcategories']);
                    }
                }
            }
            ?>
    
        </div>
    </div>
    <?php include '../components/footer/footer.php'; ?>
</body>

</html>
