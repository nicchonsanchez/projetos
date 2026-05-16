
$(function(){

    /*
        scrollSuave
    */

    $('a').click(function() {

        $('html, body').animate({

            scrollTop: $( $.attr(this, 'href') ).offset().top - 90 //- document.querySelector('body > header').getBoundingClientRect().height // Estou subtraindo a altura do menu

        }, 500);

        return false;

    });




    /*
        Menú fixo
    */

    $(window).scroll(function(){

        var windowOffY = $(window).scrollTop();
        var windowHeight = $(window).height();

        console.log(windowOffY);
            
        if(windowOffY > windowHeight - 100){
            $('body > header').css('position', 'fixed');
        }
        else if (windowOffY < windowHeight - 100){
            $('body > header').css('position', 'absolute');
        }


        /*
            Mudar cor do menú
        */
       
        $('body > section').each(function(){

            var elOffY = $(this).offset().top;
            if(elOffY+$(window).height()-$('body > header').height()-45 < (windowOffY + windowHeight) && elOffY-30+$(this).height() > windowOffY){

                $('body > header').css('background-color', $(this).css('background-color'));
                $('body > header li').css('color', $(this).css('color'));
                $('body > header nav').css('border-color', $(this).css('color'));

                return;

            }

        })

    })


})