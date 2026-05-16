// Menu mobile
$('img.menu-icon').click(function() {
    $('nav.menu.mobile').css('display', 'block');
});

$('img.close-menu').click(function() {
    $('nav.menu.mobile').css('display', 'none');
});






// Span de detalhes do produto
$('img.close-detalhes-produto').click(function() {
    $('span.detalhes-produto').css('display', 'none');
    $('span.close-detalhes-produto').css('display', 'none');
});

function abrirDetalhes(X) {
    $(`span.detalhes-produto.${X}`).css('display', 'block');
    $('span.close-detalhes-produto').css('display', 'block');
}








// Filtrar os produtos por USO
function filtrarUso(idUso) {
    // Esvaziar sessão de produtos
    $('section.produtos').html('');

    if (idUso == 0) {
        // Código para exibir todos os produtos
        for(i = 1 ; i < nomeProduto.length; i++){
            $('section.produtos').html($('section.produtos').html()+`
        
                <a onClick='abrirDetalhes("id${i}")'>
                    <article class='produto' id='produto-${i}' uso='${uso[i]}' marca='${marca[i]}'>
                        <h4 class='ver-mais'>VER MAIS</h4>
                        <div class='titulo-grid'>
                            <h3 class='titulo'>${nomeProduto[i]}</h3>
                            <div class='image' style="background-image: url('images/produtos/${imagemProduto[i]}');"></div>
                        </div>
                        <p class='descricao'>${descricaoProdutoAbreviada[i]}</p>
                        <p class='preco'>R$ ${valorProduto[i]}</p>
                    </article>
                </a>
                <!-- /.produto -->

            `);
        }
    } else {
        // Código para filtrar por uso
        uso.forEach((value,index) => {

            if(value == idUso){
                $('section.produtos').html($('section.produtos').html()+`
                
                    <a onClick='abrirDetalhes("id${index}")'>
                        <article class='produto' id='produto-${index}' uso='${uso[index]}' marca='${marca[index]}'>
                            <h4 class='ver-mais'>VER MAIS</h4>
                            <div class='titulo-grid'>
                                <h3 class='titulo'>${nomeProduto[index]}</h3>
                                <div class='image' style="background-image: url('images/produtos/${imagemProduto[index]}');"></div>
                            </div>
                            <p class='descricao'>${descricaoProdutoAbreviada[index]}</p>
                            <p class='preco'>R$ ${valorProduto[index]}</p>
                        </article>
                    </a>
                    <!-- /.produto -->

                `);
            }

        });
    }

    if ($('section.produtos').html() == '') {
        $('section.produtos').html(`
            <h1 class="sem-produtos">Lamentamos, mas ainda não temos produtos dessa categoria...</h1>
        `);
    }

    // Fechar menu mobile
    $('nav.menu.mobile').css('display', 'none');
}




// Filtrar os produtos por MARCA
function filtrarMarca(idMarca) {
    // Esvaziar sessão de produtos
    $('section.produtos').html('');

    if (idMarca == 0) {
        
        // Código para exibir todos os produtos
        for(i = 1 ; i < nomeProduto.length; i++){
            $('section.produtos').html($('section.produtos').html()+`
        
                <a onClick='abrirDetalhes("id${i}")'>
                    <article class='produto' id='produto-${i}' uso='${uso[i]}' marca='${marca[i]}'>
                        <h4 class='ver-mais'>VER MAIS</h4>
                        <div class='titulo-grid'>
                            <h3 class='titulo'>${nomeProduto[i]}</h3>
                            <div class='image' style="background-image: url('images/produtos/${imagemProduto[i]}');"></div>
                        </div>
                        <p class='descricao'>${descricaoProdutoAbreviada[i]}</p>
                        <p class='preco'>R$ ${valorProduto[i]}</p>
                    </article>
                </a>
                <!-- /.produto -->

            `);
        }

    } else {

        // Código para filtrar por marca
        marca.forEach((value,index) => {

            if(value == idMarca){
                $('section.produtos').html($('section.produtos').html()+`
                
                    <a onClick='abrirDetalhes("id${index}")'>
                        <article class='produto' id='produto-${index}' uso='${uso[index]}' marca='${marca[index]}'>
                            <h4 class='ver-mais'>VER MAIS</h4>
                            <div class='titulo-grid'>
                                <h3 class='titulo'>${nomeProduto[index]}</h3>
                                <div class='image' style="background-image: url('images/produtos/${imagemProduto[index]}');"></div>
                            </div>
                            <p class='descricao'>${descricaoProdutoAbreviada[index]}</p>
                            <p class='preco'>R$ ${valorProduto[index]}</p>
                        </article>
                    </a>
                    <!-- /.produto -->

                `);
            }

        });
    }

    if ($('section.produtos').html() == '') {
        $('section.produtos').html(`
            <h1 class="sem-produtos">Lamentamos, mas ainda não temos produtos dessa categoria...</h1>
        `);
    }

    // Fechar menu mobile
    $('nav.menu.mobile').css('display', 'none');
}




// ---------------------------------------------------------------------------------------------------------------------------------

// EXCLUIR DEPOIS QUE FIZER O CARRINHO

$('header img.cart').click(()=>{

    alert(`
        Site em manutenção!
        Função indisponível no momento.
    `)

})


// ---------------------------------------------------------------------------------------------------------------------------------