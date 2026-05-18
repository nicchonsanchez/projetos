<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cálculo de volume — Cilindro</title>

  <meta name="theme-color" content="#F5F0E6" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#11141C" media="(prefers-color-scheme: dark)">

  <meta name="author" content="Nicchon Sanchez">
  <meta name="description" content="Calculadora de volume de cilindro a partir dos diâmetros e altura. Aplicação florestal.">
  <meta property="og:title" content="Cálculo de volume — Cilindro">
  <meta property="og:description" content="Calculadora florestal de volume de cilindro.">

  <link rel="icon" href="https://nicchon.com/favicon.svg" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;500;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/style.css">

  <script>
    (function () {
      try {
        var t = localStorage.getItem('app-theme');
        if (t === 'dark' || t === 'light') {
          document.documentElement.setAttribute('data-theme', t);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          document.documentElement.setAttribute('data-theme', 'dark');
        }
      } catch (e) {}
    })();
  </script>
</head>
<body>

<?php
  $valorInicial = "---";
  $valorResultado = $valorInicial;
  $d1 = "";
  $d2 = "";
  $altura = "";

  if (isset($_POST['Calcular'])) {
    $d1     = $_POST['diametro-1'] ?? '';
    $d2     = $_POST['diametro-2'] ?? '';
    $altura = $_POST['altura']     ?? '';

    if ($d1 !== "" && $d2 !== "" && $altura !== "") {
      $raio = ((float)$d1 + (float)$d2) / 4;
      $valorResultado = 3.14 * pow($raio, 2) * (float)$altura;
    } else {
      echo "<script>alert('Preencha todos os campos!');</script>";
    }
  }
?>

<header class="site-header">
  <a href="../" class="back-link" aria-label="Voltar para o app.nicchon.com">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
    <span class="back-link-text">app.nicchon.com</span>
  </a>
  <button type="button" class="theme-toggle" aria-label="Alternar tema claro/escuro" title="Alternar tema">
    <svg class="theme-icon theme-icon-moon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
    <svg class="theme-icon theme-icon-sun" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
  </button>
</header>

<section class="calculo">
  <div class="container">
    <form action="" method="post">
      <h2>Volume — Cilindro</h2>
      <img src="images/formula.png" alt="Fórmula do cálculo" class="formula">

      <div class="input-box">
        <label for="diametro-1">Diâmetro de cima</label>
        <input type="number" step="any" name="diametro-1" id="diametro-1" value="<?= htmlspecialchars($d1) ?>" required>
      </div>
      <div class="input-box">
        <label for="diametro-2">Diâmetro de baixo</label>
        <input type="number" step="any" name="diametro-2" id="diametro-2" value="<?= htmlspecialchars($d2) ?>" required>
      </div>
      <div class="input-box">
        <label for="altura">Altura</label>
        <input type="number" step="any" name="altura" id="altura" value="<?= htmlspecialchars($altura) ?>" required>
      </div>
      <div class="input-box">
        <input type="submit" name="Calcular" value="Calcular">
      </div>
    </form>

    <div class="resultado">
      <h2>Resultado</h2>
      <p class="valor-resultado"><?= $valorResultado === $valorInicial ? $valorResultado : round($valorResultado, 2) ?></p>
    </div>
  </div>

  <a href="../calculos-florestais/">
    <div class="button voltar">
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5v0a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
      Voltar aos cálculos
    </div>
  </a>
</section>

<script>
  function toggleTheme() {
    var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    var nxt = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', nxt);
    try { localStorage.setItem('app-theme', nxt); } catch (e) {}
  }
  document.addEventListener('DOMContentLoaded', function () {
    var btn = document.querySelector('.theme-toggle');
    if (btn) btn.addEventListener('click', toggleTheme);
  });
</script>

</body>
</html>
