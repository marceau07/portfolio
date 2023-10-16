$(function ($) {
  "use strict";

  jQuery(document).ready(function () {
    /* niceSelect */
    $("select").niceSelect();

    /* Counter */
    $(".counter-item .count-num").rCounter({
      duration: 60
    });

    /* country-slider */
    var $country = $(".users .country");
    $country.owlCarousel({
      loop: true, 
      nav: false,
      dots: false,
      autoplay: true,  
      autoplayTimeout: 3000,
      smartSpeed: 1200,
      responsive: {
        0: {
          items: 3
        },
        576: {
          items: 3
        },
        768: {
          items: 4
        },
        991: {
          items: 4
        },
        1200: {
          items: 5
        }
      }
    });

        /* testimonial-slider */
        var $testo = $(".testo-box");
        $testo .owlCarousel({
          loop: true, 
          nav: false,
          dots: false,
          autoplay: true,  
          margin: 20,
          autoplayTimeout: 3000,
          smartSpeed: 1200, 
          items: 1
        });
 
    /* Magnific Popup Activation Probucket */
    $(".youtube-video").magnificPopup({
      type: "video",
    });

    /*back to top-*/
    $(document).on("click", "#scrollUp", function () {
      $("html,body").animate({
          scrollTop: 0,
        },
        100
      );
    });

    /* sticky navigation menu */
    var lastScrollTop = "";
    $(window).on("scroll", function () {
      var $window = $(window);
      if ($window.scrollTop() > 100) {
        $(".mein-menu").addClass("nav-fixed");
      } else {
        $(".mein-menu").removeClass("nav-fixed");
      }

      /*---------------------------
         back to top show / hide
     ---------------------------*/
      var st = $(this).scrollTop();
      if ($(window).scrollTop() > 500) {
        $("#scrollUp").fadeIn();
      } else {
        $("#scrollUp").fadeOut();
      }
      lastScrollTop = st;
    });

  });

  $(window).on("load", function () {
    /*Preloader*/
    var preLoder = $("#preloader");
    preLoder.hide();
  });
});