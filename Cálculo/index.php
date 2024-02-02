<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Config -->
    <title>Projetos de Nicchon Sanchez</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <meta name="theme-color" content="#061e4e">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Fontes e Icones -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <header>
        <a href="../"><img src="../images/favicon.png" alt=""></a>
    </header>

    <ul class="lista-projetos">
        <p>Aplicativos para cálculo</p>
        <?php
            $path = "../".basename(__DIR__).'/';
            $diretorio = dir($path);

            $i = 0;
            $nomeArquivo = [];
            $nomeProjeto = "";

            while($arquivo = $diretorio -> read()){
                for($i=0; strlen($arquivo) > $i; $i++){
                    if($arquivo[$i] == "-"){
                        $nomeArquivo[$i] = " &nbsp;";
                    } else {
                        $nomeArquivo[$i] = $arquivo[$i];
                    }
                }

                $nomeProjeto = implode("", $nomeArquivo);

                if($arquivo != '.' && $arquivo != '..' && $arquivo != 'css' && $arquivo != 'js' && $arquivo != 'index.php' && $arquivo != 'images'){
                    echo "<li><a href='".$path.$arquivo."'><div class='button'>".$nomeProjeto."</div></a></li>";
                }
                $i++;

                $nomeArquivo = [];
                $nomeProjeto = "";
            }
            $diretorio -> close();
        ?>

        <li><a href="../"><div class="button voltar"><span class="material-symbols-outlined">undo</span> Voltar</div></a></li>
    </ul>
</body>
</html>