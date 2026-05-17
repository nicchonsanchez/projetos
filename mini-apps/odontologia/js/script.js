$(function(){

    // Menu mobile
    $('nav.mobile').click(function(){
        $('nav.mobile').find('ul').slideToggle();
    })

    // Ancora - Scroll suave
    var $doc = $('html, body');
    $('a').click(function() {
        $doc.animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top - document.querySelector('body header').getBoundingClientRect().height // Estou subtraindo a altura do menu
        }, 500);
        return false;
    });




    /*
    $menuMobile = document.querySelector('header nav.mobile');
    $menuMobileUl = document.querySelector('header nav.mobile ul');

    $menuMobile.addEventListener(
        "click",
        function(){
            if($menuMobileUl.style.display == "none"){
                $menuMobileUl.style.display = "block";
            } else {
                $menuMobileUl.style.display = "none";
            }
        }
    );
    */



    // Slide MOSAICO
    $('.mosaico .mosaico-wraper').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        fade: false,
        centerMode: true,
        focusOnSelect: true,
        autoplay: true,
        autoplaySpeed: 1000,
        infinite: true,
        centerPadding: '10px',

        responsive:[
            {
                breakpoint: 768,
                settings:{
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },

            {
                breakpoint: 500,
                settings:{
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }
        ]
    })

    // Slide TRATAMENTOS
    $('#tratamentos .container').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        fade: false,
        centerMode: false,
        focusOnSelect: true,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        centerPadding: '10px',

        responsive:[
            {
                breakpoint: 768,
                settings:{
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    dots: true,
                }
            },

            {
                breakpoint: 500,
                settings:{
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                }
            }
        ]
    })

    // Slide DEPOIMENTOS
    $('#depoimentos .container').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        fade: false,
        focusOnSelect: true,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
    })

})