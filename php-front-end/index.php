<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
</head>
<body>
    <main class=" w-full block h-full">
        <?php include './components/header/header.php'; ?>
        <div class="container">
            <div class=" relative block mx-auto w-full mt-5">
                <?php include './components/banner-slider.php' ?>
            </div>
        </div>
        <div class="w-[90%] mx-auto min-h-screen block relative mt-12">
            <?php include './components/ecommerce/ecomAll.php' ?>
        </div>
        <?php include './components/footer/footer.php'; ?>

    </main>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="./assets/js/main.js"></script>

</body>
</html>