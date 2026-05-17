<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php
;
            $dSplit = explode('/', $_SERVER['PHP_SELF']);
            $dSearchIndex = array_search('index.php', $dSplit);
            unset($dSplit[$dSearchIndex]);
            $diretorio = implode("/", $dSplit);
            $urlSite = 'https://'.$_SERVER['HTTP_HOST'].$diretorio;

        ?>
        <!-- Personalização -->
        <title>Venda de Carros | Projeto de portifólio de Nicchon Sanchez</title>
        <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
        <meta name="theme-color" content="#252525">
    
        <!-- Config -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <!-- CEO -->
        <meta property="og:image" itemprop="image" content="http://nicchon.com/images/icone-meta.png">
        <meta name="description" content="Este site é um projeto do portifólio de desenvolvedor de Nicchon Sanchez. Conferir mais em dev.nicchon.com">
        <meta name="keywords" content="palavra,separadas,por,virgula">
        <meta name="author" content="Nicchon Sanchez Pinto">
        <meta name="title" content="Nicchon Sanchez Pinto">
    
        <!-- CSS -->
        <link rel="stylesheet" href="<?php echo $urlSite;?>/css/style.css">
    
        <!-- JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
        <!-- Fontes -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="<?php echo $urlSite;?>/images/logo.jpg" alt="Logo">
            </div>
    
            <nav class="desktop">
                <ul>
                    <li><a href="<?php echo $urlSite;?>/home" class="scrollSuave">Home</a></li>
                    <li><a href="<?php echo $urlSite;?>/venda" class="scrollSuave">Venda</a></li>
                    <li><a href="<?php echo $urlSite;?>/sobre-nos" class="scrollSuave">Sobre nós</a></li>
                    <li><a href="<?php echo $urlSite;?>/home#contato" class="scrollSuave">Contato</a></li>
                </ul>
            </nav>
            <!-- /.desktop -->
            <nav class="mobile">
                <ul>
                    <li><a href="<?php echo $urlSite;?>/home" class="scrollSuave"><div>Home</div></a></li>
                    <li><a href="<?php echo $urlSite;?>/venda" class="scrollSuave"><div>Venda</div></a></li>
                    <li><a href="<?php echo $urlSite;?>/sobre-nos" class="scrollSuave"><div>Sobre nós</div></a></li>
                    <li><a href="<?php echo $urlSite;?>/home#contato" class="scrollSuave"><div>Contato</div></a></li>
                </ul>
            </nav>
            <!-- /.mobile -->
            <div class="clear"></div><!-- /.clear -->
        </div>
        <!-- /.container -->
    </header>

<?php

	if(isset($_GET['url'])){
		if(file_exists($_GET['url']).'.php'){
			include($_GET['url'].'.php');
		}else{
			include('404.php');
		}
	}else{
		include('home.php');
	}

?>

    <footer class="rodape">
        <div class="container">
        <nav>
            <ul>
                <li><a href="<?php echo $urlSite;?>/home" class="scrollSuave">Home</a></li>
                <li><a href="<?php echo $urlSite;?>/venda" class="scrollSuave">Venda</a></li>
                <li><a href="<?php echo $urlSite;?>/sobre-nos" class="scrollSuave">Sobre nós</a></li>
                <li><a href="<?php echo $urlSite;?>/home#contato" class="scrollSuave">Contato</a></li>
            </ul>
            <div class="clear"></div><!-- /.clear -->
        </nav>
        </div><!--container-->
        <footer>
            <p>
                2023 © Todos os direitos reservados | Feito por: <a href="http://nicchon.com" target="_blank" rel="noopener noreferrer">www.nicchon.com</a>
            </p>
        </footer>
    </footer>
    <!-- /.rodape -->


    <script src="<?php echo $urlSite;?>/js/script.js"></script>
</body>
</html>