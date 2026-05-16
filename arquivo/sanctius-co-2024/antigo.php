<!DOCTYPE html>
<html lang="pt-br">
<head>

<?php
    //$hostRunning = "Note";
    $hostRunning = "Online";
  
    //Variáveis para o decorrer do site:
    //$telSanctius = "5531990834271";
    $telSanctius = "5531989116860";
    $dominioSite = "https://sanctius.co";


    try{
        if($hostRunning == "Note"){
            define("HOST", "localhost");
            define("BANCO", "sanctius");
            define("USER", "root");
            define("PASS", "");
    
            $pdo = new PDO('mysql:local='.HOST.';dbname='.BANCO, USER, PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
    
        if($hostRunning == "Online"){
            define("HOST", "https://sanctius.co:3306");
            define("BANCO", "niccho25_sanctius");
            define("USER", "niccho25_sanctius");
            define("PASS", "Sanctius@2021");
            $pdo = new PDO('mysql:local='.HOST.';dbname='.BANCO, USER, PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
    } catch(Exception $e) { // 4B- Caso não dê certo, vai partir para essa parte abaixo do catch. Catch significa pague, ele vai pegar a Exceptoin (excessão) e jogar na variável $e, onde ficará o erro que está gerando.
        echo '<h1 style="background: #ccc; padding: 100px; text-align: center; font-family: Arial;">ERRO AO CONECTAR!</h1>';
        die;
    }

?>


    <!-- Config-->
    <title>Sanctius | Cosméticos Artesanais</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <meta name="theme-color" content="#c3b324">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <meta name="title" content="Sanctius">
    <meta name="author" content="Nicchon Sanchez Pinto">
    <meta property="og:image" itemprop="image" content="<?php echo $dominioSite; ?>/images/seo.png">
    <meta name="description" content="Cosméticos com os melhores preços! Veja nossa linha de cosméticos artesanais">
    <meta name="keywords" content="cosméticos,creme hidratante,creme,hidratação,creme de mão,creme de pele,creme para cabelo,cabelo,hidratante">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link para ICONS Fa fas-etc -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

</head>
<body>

    <header>
        <div class="container">
            <img class="logo" src="images/logo.png" alt="Sanctius">
            <nav>
                <h2>Sanctius</h2>
                <ul>
                    <li>
                        <div><a href="#Cabelos">Cabelos</a><div class="bb"></div><!-- underline --></div>
                    </li>
                    <li>
                        <div><a href="#Mãos">Mãos</a><div class="bb"></div><!-- underline --></div>
                    </li>
                    <li>
                        <div><a href="#Corporal">Corporal</a><div class="bb"></div><!-- underline --></div>
                    </li>
                    <li>
                        <div><a href="#Unhas">Unhas</a><div class="bb"></div><!-- underline --></div>
                    </li>
                    <li>
                        <div><a href="#Barba">Barba</a><div class="bb"></div><!-- underline --></div>
                    </li>
                    <li>
                        <div><a href="#Perfumes">Perfumes</a><div class="bb"></div><!-- underline --></div>
                    </li>
                </ul>
            </nav>
            <!--/nav-->
            <img class="cart hfloat desktop" src="images/shopping-cart.png" alt="Meu carrinho">
            <img class="menu-icon hfloat mobile" src="images/menu.png" alt="Meu carrinho">
            <div class="clear"></div>
        </div>
        <!--/.container-->
    </header>

    <nav class="menu mobile">
        <img class="close-menu" src="images/close.png" alt="">
        <a href="index.php"><div class="logo">
            <img class="logo" src="images/logo.png" alt="Sanctius">
            <h2>Sanctius</h2>
        </div></a>
        <!--/.logo-->

        <ul class="categorias">
            <li>
                <div><a href="#Cabelos">Cabelos</a></div>
            </li>
            <li>
                <div><a href="#Mãos">Mãos</a></div>
            </li>
            <li>
                <div><a href="#Corporal">Corporal</a></div>
            </li>
            <li>
                <div><a href="#Unhas">Unhas</a></div>
            </li>
            <li>
                <div><a href="#Barba">Barba</a></div>
            </li>
            <li>
                <div><a href="#Perfumes">Perfumes</a></div>
            </li>
        </ul>
        <!-- /.categorias -->

        <ul class="redes-sociais">
            <li><a href="https://wa.me/<?php echo $telSanctius; ?>" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
            <li><a href="https://instagram.com/sanctius.co/" target="_blank"><i class="fa fa-instagram"></i></a></li>
            <li><a href="tel: +55 31 98911-6860"><i class="fa fa-phone"></i></a></li>
            <li><a href="mailto: atendimento@sanctius.co"><i class="fa fa-envelope"></i></a></li>
        </ul>
        <!-- /.redes-sociais -->
    </nav>
    <!-- /.menu mobile -->

    <section class="slides propagandas">
        <article class="slide-page">
            <h3>Conheça nossa linha de cosméticos artesanais</h3>
        </article>
        <!-- /.slide-page -->
    </section>
    <!--/.slides-->

    <div class="container">
        <section class="produtos">
        
        <?php
            $sql = $pdo->prepare('SELECT * FROM produtos');
            $sql->execute();
            $produtos = $sql->fetchAll();

            foreach($produtos as $keyProduto => $valueProduto){
                $idProduto = $valueProduto['id'];
                $imagemProduto = $valueProduto['imagem'];
                $nomeProduto = $valueProduto['nome_produto'];
                $valorProduto = $valueProduto['preco'];
                $descricaoProduto = $valueProduto['descricao'];
                $uso = $valueProduto['id_local_uso'];
                $marca = $valueProduto['id_marca'];

                echo "
                    <a onClick='abrirDetalhes(\"id$idProduto\")'>
                    <article class='produto' id='produto-$idProduto' uso='$uso' marca='$marca'>
                        <h4 class='ver-mais'>VER MAIS</h4>
                        <div class='titulo-grid'>
                            <h3 class='titulo'>$nomeProduto</h3>
                            <div class='image' style=\"background-image: url('images/produtos/$imagemProduto');\"></div>
                        </div>
                        <p class='descricao'>
                ";

                $numLetras = 90; // Coloque aqui o número máximo de letras a mostrar na descrição
                if(strlen($descricaoProduto)>$numLetras){
                    for($ii=0;$ii<$numLetras;$ii++){
                        echo $descricaoProduto[$ii];
                    }
                    echo "...";
                } else {
                    echo $descricaoProduto;
                }
                        
                echo "</p>
                        <p class='preco'>R$ $valorProduto</p>
                    </article>
                    </a>
                    <!-- /.produto -->
                ";
            }

        ?>

            
        </section>
        <!-- /.produtos -->
    </div>
    <!-- /.container -->


        <span class='close-detalhes-produto'></span><!-- /.close-detalhes-produto -->
        <?php
            foreach($produtos as $keyProduto => $valueProduto){
                $idProduto = $valueProduto['id'];
                $imagemProduto = $valueProduto['imagem'];
                $nomeProduto = $valueProduto['nome_produto'];
                $valorProduto = $valueProduto['preco'];
                $descricaoProduto = $valueProduto['descricao'];

                echo "
                    <span class='detalhes-produto id$idProduto'>

                        <img src='images/close.png' class='close-detalhes-produto' onClick=\"fecharDetalhes()\">
                        <div class='img' style='background-image: url(\"images/produtos/$imagemProduto\");'></div><!-- Foto do produto -->
                        <div class='texto'>
                            <h3 class='titulo'>$nomeProduto</h3>
                            <p class='descricao'>$descricaoProduto</p>
                            <p class='preco'>R$ $valorProduto</p>
                        </div>
                        <a href='https:\/\/api.whatsapp.com/send?phone=$telSanctius&text=Ol%C3%A1!%0A%0AEstou%20querendo%20comprar%20o%20produto%3A%0A%2A$nomeProduto%2A%0AID%3A%20%2A$idProduto%2A%0A$dominioSite%2F%23produto-$idProduto%0A%0APoderia%20me%20atender%20por%20gentileza?%0A' target='_blank'><div class='btn-comprar'>Comprar</div></a>
                    </span>
                    <!-- /.detalhes-produto -->
                ";
            }
        ?>
    <!-- /.detalhes-produto -->
    
 

    <footer>
        <div class="container">
        <div class="coluna contate-nos">
                <h3>Contate-nos</h3>
                <ul>
                    <li><a target="_blank" href="mailto:atendimento@sanctius.co">atendimento@sanctius.co</a></li>
                    <li><a target="_blank" href="mailto:vendas@sanctius.co">vendas@sanctius.co</a></li>
                    <li><a target="_blank" href="tel: +<?php echo $telSanctius ?>">Tel: +55 31 99083-4271</a></li>
                    <li><a target="_blank" href="https://wa.me/<?php echo $telSanctius ?>">WhatsApp: +55 31 99083-4271</a></li>
                    <li><a target="_blank" href="https://instagram.com/sanctius.co/">Instagram: @sanctius.co</a></li>
                </ul>
            </div>
            <!-- /.contate-nos -->

            <div class="coluna suporte">
                <h3>Ajuda e Suporte</h3>
                <ul>
                    <li><a target="_blank" href="mailto:suporte@sanctius.co">suporte@sanctius.co</a></li>
                    <li><a target="_blank" href="tel: +55 31 98911-6860">Tel: +55 31 98911-6860</a></li>
                    <li><a target="_blank" href="https://wa.me/5531989116860">WhatsApp: +55 31 98911-6860</a></li>
                </ul>
            </div>
            <!-- /.contate-nos -->

            <div class="coluna marcas">
                <h3>Marcas vendidas</h3>
                <ul>
                    <li><a href="#">Sanctius</a></li>
                    <li><a href="#">Dr. Barba</a></li>
                    <li><a href="#">Flower Cosméticos</a></li>
                </ul>
            </div>
            <!-- /.contate-nos -->
        </div>
        <!-- /.container -->
    </footer>
    <footer class="dr">
        <p>2022 © Todos os direitos reservados | Feito por: <a href="https://nicchon.com" target="_blank">www.nicchon.com</a></p>
    </footer>


    <script src="js/jquery.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>