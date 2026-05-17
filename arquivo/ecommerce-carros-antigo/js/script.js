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
