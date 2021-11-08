
// Slider =======================================
$(".intro_slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    dots: true,
    autoplaySpeed: 3000,
    pauseOnHover: false,


    infinite: true,
    speed: 1000,
    fade: true,
    adaptiveHeight: false,
    lazyLoad: 'ondemand',

    cssEase: 'linear',
    swipeToSlide: true,
    waitForAnimate: false,
    draggable: true,
});

$(".advert").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    autoplay: true,
    dots: true,
    autoplaySpeed: 2500,
    pauseOnHover: false,


    infinite: true,
    speed: 1000,
    fade: false,
    adaptiveHeight: false,
    lazyLoad: 'ondemand',

    cssEase: 'linear',
    swipeToSlide: true,
    waitForAnimate: false,
    draggable: true,
});

// Detail slider ======================================
$('.detail_photo_box').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: false,
    fade: true,
    autoplay: false,
    autoplaySpeed: 3000,
    asNavFor: '.detail_for_navs',

});
$('.detail_for_navs').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.detail_photo_box',
    dots: false,
    autoplay: false,
    vertical: true,
    centerMode: true,
    centerPadding: 0,
    focusOnSelect: true,
    verticalSwiping: true,
    adaptiveHeight: true,
    arrows: false,
    prevArrow: false,
    nextArrow: false,

    responsive: [
        {
            breakpoint: 1500,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
            }
        },
        {
            breakpoint: 900,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
            }
        }
    ]
});
