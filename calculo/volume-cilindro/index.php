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
    <meta name="title" content="Nicchon Apps | Cálculo de volume de cilindro">
    <meta name="author" content="Nicchon Sanchez Pinto">
    <meta property="og:image" itemprop="image" content="http://nicchon.com/images/icone-meta.png">
    <meta name="description" content="Cálculo de volume de cilindro">
    <meta name="keywords" content="Cálculo volume de cilindro">

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
        $d1 = "";
        $d2 = "";
        $altura = "";

        if(isset($_POST['Calcular'])){
            $d1 = $_POST['diametro-1'];
            $d2 = $_POST['diametro-2'];
            $altura = $_POST['altura'];
            
            if($d1 != "" && $d2 != "" && $altura != ""){
                $raio = ($d1 + $d2)/4;
                $valorResultado = (3.14)*pow($raio, 2)*$altura;
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
                <h2>Calcular volume do cilindro</h2>
                <img src="images/formula.png" alt="Fórmula do cálculo" class="formula">

                <div class="input-box">
                    <label for="diametro-1">Diâmetro de cima:</label>
                    <input type="number" step="any" name="diametro-1" id="diametro-1" value="<?php echo $d1; ?>" required>
                </div>
                <div class="input-box">
                    <label for="diametro-2">Diâmetro de baixo:</label>
                    <input type="number" step="any" name="diametro-2" id="diametro-2" value="<?php echo $d2; ?>" required>
                </div>
                <div class="input-box">
                    <label for="altura">Altura:</label>
                    <input type="number" step="any" name="altura" id="altura" value="<?php echo $altura; ?>" required>
                </div>
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
            </div></div><!-- /.resultado -->
        </div><!-- /.container -->
    </section>

    <a href="../"><div class="button voltar"><span class="material-symbols-outlined">undo</span> Voltar</div></a>
    <br>
</body>
</html>