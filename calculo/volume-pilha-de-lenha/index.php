<!DOCTYPE html>
<html lang="pt-br">
<head>

    <!-- Config -->
    <title>Cálculo de volume de cilindro</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <meta name="theme-color" content="#061e4e">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <meta name="title" content="Nicchon Apps">
    <meta name="author" content="Nicchon Sanchez Pinto">
    <meta property="og:image" itemprop="image" content="http://nicchon.com/images/icone-meta.png">
    <meta name="description" content="Cálculo de volume de pilha de lenha">
    <meta name="keywords" content="Cálculo volume de pilha de lenha">

    <!-- Fontes e Icones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
        $valorInicial = "---";
        $valorResultado = $valorInicial;
        $H = "";
        $C = "";
        $L = "";
        $fe = "";

        if(isset($_POST['Calcular'])){
            $H = $_POST['H'];
            $C = $_POST['C'];
            $L = $_POST['L'];
            $fe = $_POST['fe'];
            
            if($H != "" && $C != "" && $L != "" && $fe != ""){
                $valorResultado = ($H*$C*$L)/$fe;
            } else {
                echo "
                    <script>
                        alert('Preencha todos os campos!');
                    </script>
                ";
            }
        }

    ?>

    <section class="calculo">
        <div class="container">
            <form action="" method="post">
                <h2>Calcular volume | Pilha de lenhas</h2>
                <img src="images/formula.png" alt="Fórmula do cálculo" class="formula">
                
                <div class="input-box">
                    <label for="H">Altura da pilha (H):</label>
                    <input type="number" step="any" name="H" id="H" value="<?php echo $H; ?>" required>
                </div><!--/.input-box-->
                <div class="input-box">
                    <label for="C">Comprimento da pilha (C):</label>
                    <input type="number" step="any" name="C" id="C" value="<?php echo $C; ?>" required>
                </div><!--/.input-box-->
                <div class="input-box">
                    <label for="L">Largura da pilha (L):</label>
                    <input type="number" step="any" name="L" id="L" value="<?php echo $L; ?>" required>
                </div><!--/.input-box-->
                <div class="input-box">
                    <label for="fe">Fator de empilhamento (fe):</label>
                    <input type="number" step="any" name="fe" id="fe" value="<?php echo $fe; ?>" required>
                </div><!--/.input-box-->
                <div class="input-box">
                    <input type="submit" name="Calcular" value="Calcular">
                </div>
            </form>

            <div class="resultado">
                <h2>Resultado</h2>
                <p class="valor-resultado"><?php 
                    if($valorResultado == $valorInicial){
                        echo $valorResultado;
                    } else {
                        echo round($valorResultado,2);
                    } ?></p>
            </div><!-- /.resultado -->

        </div><!-- /.container -->

        <a href="../"><div class="button voltar"><span class="material-symbols-outlined">undo</span> Voltar</div></a>
        <br>
    </section>
</body>
</html>