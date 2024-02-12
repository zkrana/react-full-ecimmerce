<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
</head>
<body>
    <main class=" w-full block h-full">
        <?php include './components/header/header.php'; ?>
        <div class="container">
            <div class=" relative block mx-auto w-full mt-5">
                <?php include './components/banner-slider.php' ?>
            </div>
        </div>
        <div class="container">
            <div class="w-full min-h-screen block relative mt-12">
                <?php include './components/ecommerce/ecomAll.php' ?>
            </div>
        </div>
        <?php include './components/footer/footer.php'; ?>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Add this script tag before your main.js -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="./assets/js/main.js"></script>

</body>
</html>