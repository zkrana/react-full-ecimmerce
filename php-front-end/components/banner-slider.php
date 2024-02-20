<?php
$apiUrl = "http://localhost/reactcrud/backend";
// Fetch banners from the server
$apiEndpoint = "$apiUrl/auth/api/banner/banner.php";
$response = file_get_contents($apiEndpoint);
$data = json_decode($response, true);

$banners = isset($data['banners']) ? $data['banners'] : [];
?>

<div class="w-[90%] mx-auto overflow-hidden rounded mt-2">
  <?php if (empty($banners)): ?>
    <p>No banners found.</p>
  <?php else: ?>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php foreach ($banners as $banner): ?>
          <div class="swiper-slide">
            <img
              class="w-full sm:h-[450px] h-full object-fill"
              src="<?php echo $apiUrl; ?>/auth/assets/banner/<?php echo $banner['photo_name']; ?>"
              alt="<?php echo $banner['photo_name']; ?>"
            />
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
      <!-- Add Navigation -->
      <!-- <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div> -->
    </div>
  <?php endif; ?>
</div>



