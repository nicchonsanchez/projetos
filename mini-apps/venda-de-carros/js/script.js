$(function(){

    // Menu mobile
    $('nav.mobile').click(function(){
        $('nav.mobile').find('ul').slideToggle();
    })

    // Regular altura do menu mobile
    window.onload = function(){
        document.querySelector('header nav.mobile ul').style.top = (document.querySelector('body header').getBoundingClientRect().height - document.querySelector('header nav.mobile').getBoundingClientRect().top);
    }

    // Ancora - Scroll suave
    var $doc = $('html, body');
    $('a').click(function() {
        $doc.animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top //- document.querySelector('body header').getBoundingClientRect().height // Estou subtraindo a altura do menu
        }, 500);
        return false;
    });


    // Menu - Página selecionada
    checkUrl();

    function checkUrl(){
        var url = location.href.split('/');
        var curPage = url[url.length-1].split('#');

        if(curPage[1] != undefined || curPage == 'contato'){
            $('header a[href$="#contato"]').css('color','#EB2D2D');
            // $('html, body').animate({'scrollTop':$('[href$="#contato"]').offset().top});
        } else if(curPage == 'index'){
            $('header a[href$="home"]').css('color','#EB2D2D');
        } else if (curPage == '') {
            $('header a[href$="home"]').css('color','#EB2D2D');
        } else {
            $('header a[href$='+curPage+']').css('color','#EB2D2D');
        }
    }



    // Slide Depoimentos
    var amtDepoimentos = $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').length;
    var curDepoimentos  = 0;

    initDepoimentos();
    navDepoimentos();

    function initDepoimentos(){
        var depoimentosHeight = [];
        for(var i=0; i<amtDepoimentos; i++){
            depoimentosHeight.push($('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote p').eq(i).css('height'));

            var pHeight = depoimentosHeight.reduce(function(prev, current){
                return prev > current ? prev : current;
            })
        }

        $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote p').css('min-height', pHeight);
        console.log(pHeight);

        $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').css('display', 'none');
        $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').eq(0).fadeIn();
    }

    function navDepoimentos(){
        $('.servicos-depoimentos section.depoimentos [prev]').click(function(){
            curDepoimentos --;
            if(curDepoimentos < 0)
                curDepoimentos = amtDepoimentos-1;
            
            $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').css('display', 'none');
            $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').eq(curDepoimentos).fadeIn();
        })

        $('.servicos-depoimentos section.depoimentos [next]').click(function(){
            curDepoimentos ++;
            if(curDepoimentos == amtDepoimentos)
                curDepoimentos = 0;
            
            $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').css('display', 'none');
            $('.servicos-depoimentos section.depoimentos .depoimentos-lista blockquote').eq(curDepoimentos).fadeIn();
        })
    }



    // Barra de preço do filtro - Página de vendas
    var currentValue = 0;
    var isDrag = false;
    var preco_maximo = 70000;
    var preco_atual = 0;

    $('.pointer-barra').mousedown(function(){
        isDrag = true;
    })

    $(document).mouseup(function(){
        isDrag = false;
        enableTextSelection();
    })

    // Mover a barra de preço
    $('.barra-preco').mousemove(function(e){
        
        if(isDrag){
            disableTextSelection();
            var elBase = $(this);
            var mouseX = e.pageX - elBase.offset().left;

            if(mouseX < 0)
                mouseX = 0

            if(mouseX > elBase.width())
                mouseX = elBase.width();

            $('.pointer-barra').css('left', mouseX+'px');
            currentValue = (mouseX / elBase.width()) *100;
            $('.barra-preco-fill').css('width', currentValue+"%");

            preco_atual = currentValue * preco_maximo / 100;
            preco_atual = formatarPreco(preco_atual);
            $('.preco-pesquisa').html('R$'+preco_atual);
        }

    })

    // Clicar na barra de preço
    $('.barra-preco').click(function(e){
        
        disableTextSelection();
        var elBase = $(this);
        var mouseX = e.pageX - elBase.offset().left;

        if(mouseX < 0)
            mouseX = 0

        if(mouseX > elBase.width())
            mouseX = elBase.width();

        $('.pointer-barra').css('left', mouseX+'px');
        currentValue = (mouseX / elBase.width()) *100;
        $('.barra-preco-fill').css('width', currentValue+"%");

        preco_atual = currentValue * preco_maximo / 100;
        preco_atual = formatarPreco(preco_atual);
        $('.preco-pesquisa').html('R$'+preco_atual);

    })

    // Formatar preço no formato de reais
    function formatarPreco(preco_atual){
        preco_atual = preco_atual.toFixed(2);
        preco_arr = preco_atual.split('.');

        var preco_formatado = caracterizarPreco(preco_arr);

        return preco_formatado

        function caracterizarPreco(preco_arr){
            if(preco_arr[0] < 1000){
                return preco_arr[0]+','+preco_arr[1];
            }else if(preco_arr[0] < 10000){
                return preco_arr[0][0]+'.'+preco_arr[0].substr(1, preco_arr[0].length)+','+preco_arr[1];
            }else{
                return preco_arr[0][0]+preco_arr[0][1]+'.'+preco_arr[0].substr(2, preco_arr[0].length)+','+preco_arr[1];
            }

        }
    }


    // Desativar seleção de texto para evitar interferência no arraste da barra de preço
    function disableTextSelection(){
        $('body').css({
            '-wibkit-user-select': 'none',
            '-moz-user-select': 'none',
            '-ms-user-select': 'none',
            '-o-user-select': 'none',
            '-user-select': 'none',
        });
    }
    function enableTextSelection(){
        $('body').css({
            '-wibkit-user-select': 'auto',
            '-moz-user-select': 'auto',
            '-ms-user-select': 'auto',
            '-o-user-select': 'auto',
            '-user-select': 'auto',
        });
    }





    // Slide - Página: Veículo para venda

    // mini-img-wraper (a selecionada) => style="background-color:rgb(210,210,210);"
    // foto-destaque => style="background-image:url('../images/carro1.jpg');"

    var imgShow = 3;
    var maxIndex = Math.ceil($('.mini-img-wraper').length / imgShow) - 1;
    var curIndex = 0;

    initSlider();
    navigateSlider()
    miniImageClick();

    function initSlider(){
        var amt = $('.mini-img-wraper').length * 100 / imgShow;
        var elScroll = $('.nav-galeria-wraper');
        var elSingle = $('.mini-img-wraper');

        elScroll.css('width', amt+'%');
        elSingle.css('width', (100/imgShow)*(100/amt)+'%');

        $(".foto-destaque").css('background-image', $('.nav-galeria .mini-img-wraper').eq(0).find('div').css('background-image'));
        $('.nav-galeria .mini-img-wraper').eq(0).css("background-color","rgb(210,210,210)");
    }

    function navigateSlider(){
        $('.arrow-right-nav').click(function(){
            if(curIndex < maxIndex){
                curIndex++;
                
                if(curIndex < 0)
                    curIndex = 0;
                if(curIndex > maxIndex)
                    curIndex = maxIndex;
                console.log(curIndex);

                var elOff = $('.mini-img-wraper').eq(curIndex*3).offset().left - $('.nav-galeria-wraper').offset().left;
                $('.nav-galeria').animate({'scrollLeft': elOff+'px'});
            } else {
                curIndex = 0;
                console.log(curIndex);

                var elOff = $('.mini-img-wraper').eq(0).offset().left - $('.nav-galeria-wraper').offset().left;
                $('.nav-galeria').animate({'scrollLeft': elOff+'px'});
            }
        })

        $('.arrow-left-nav').click(function(){
            if(curIndex > 0){
                curIndex--;
                
                if(curIndex < 0)
                    curIndex = 0;
                if(curIndex > maxIndex)
                    curIndex = maxIndex;
                console.log(curIndex);

                var elOff = $('.mini-img-wraper').eq(curIndex*3).offset().left - $('.nav-galeria-wraper').offset().left;
                $('.nav-galeria').animate({'scrollLeft': elOff+'px'});
            } else {
                curIndex = maxIndex;
                console.log(curIndex);

                var elOff = $('.mini-img-wraper').eq($('.mini-img-wraper').length -1 ).offset().left - $('.nav-galeria-wraper').offset().left;
                $('.nav-galeria').animate({'scrollLeft': elOff+'px'});
            }
        })
    }

    // Troca de imagem ao clicar na mini imagem
    function miniImageClick(){
        $('.nav-galeria .mini-img-wraper').click(function(){

            imgIndex = $(this).index();
            imgURL = $(this).find('div').css('background-image');

            $('.nav-galeria .mini-img-wraper').css("background-color","transparent");
            $(this).css("background-color","rgb(210,210,210)");
            $(".foto-destaque").css('background-image', imgURL);

            console.log(imgURL);

        })
    }
})