document.addEventListener('DOMContentLoaded', function () {
  new Swiper('.upcoming-events .swiper', {
    slidesPerView: 3,
    spaceBetween: 24,
    loop: true,
    navigation: {
      nextEl: '.upcoming-events .swiper-button-next',
      prevEl: '.upcoming-events .swiper-button-prev',
    },
    pagination: {
      el: '.upcoming-events .swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      1024: { slidesPerView: 3 },
      768: { slidesPerView: 2 },
      480: { slidesPerView: 1 }
    }
  });
});
