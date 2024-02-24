// Swiper initialization code
var swiper = new Swiper(".swiper-container", {
  slidesPerView: 4,
  slidesPerScroll: 1,
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  autoplay: {
    duration: 3000,
  },
});
