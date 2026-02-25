document.addEventListener("DOMContentLoaded", function () {

    new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 20,
        autoplay: {
            delay: 3000,
        },

        
        breakpoints: {
            0: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 3
            },
            1024: {
                slidesPerView: 3
            }
        }
    });

});