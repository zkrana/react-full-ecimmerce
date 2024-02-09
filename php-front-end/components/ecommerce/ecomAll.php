<div class=" w-[90%] mx-auto mt-14">
  <div class="ecom-main-cat">
    <?php include 'categories.php'; ?>
  </div>
  <div class="w-full flex justify-between gap-8 mt-10">
    <div class="ecomBody w-full">
      <?php include 'dealOfTheDay.php'; ?>
      <div class="featured-com flex justify-between space-x-6 mt-14">
        <?php include 'newArrival.php'; ?>
        <?php include 'trending.php'; ?>
        <?php include 'topRated.php'; ?>
      </div>
      <?php include 'products.php'; ?>
    </div>
  </div>
</div>
