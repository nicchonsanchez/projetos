<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cálculo de volume — Árvores isoladas</title>

  <meta name="theme-color" content="#F5F0E6" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#11141C" media="(prefers-color-scheme: dark)">

  <meta name="author" content="Nicchon Sanchez">
  <meta name="description" content="Calculadora de volume de árvores isoladas a partir de DAP, altura e fator de forma. Aplicação florestal.">
  <meta property="og:title" content="Cálculo de volume — Árvores isoladas">
  <meta property="og:description" content="Calculadora florestal de volume de árvores isoladas.">

  <link rel="icon" href="https://nicchon.com/favicon.svg" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;500;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/style.css">

  <script>
    // Anti-FOUC: aplica tema antes do render. Lê localStorage (compartilhado com app.nicchon.com).
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
  $DAP = "";
  $H = "";
  $ff = "";

  if (isset($_POST['Calcular'])) {
    $DAP = $_POST['DAP'] ?? '';
    $H   = $_POST['H']   ?? '';
    $ff  = $_POST['ff']  ?? '';

    if ($DAP !== "" && $H !== "" && $ff !== "") {
      $valorResultado = ((3.14 * pow((float)$DAP, 2)) / 4) * (float)$H * (float)$ff;
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
      <h2>Volume — Árvores isoladas</h2>
      <img src="images/formula.png" alt="Fórmula do cálculo" class="formula">

      <div class="input-box">
        <label for="DAP">Diâmetro da árvore (DAP)</label>
        <input type="number" step="any" name="DAP" id="DAP" value="<?= htmlspecialchars($DAP) ?>" required>
      </div>
      <div class="input-box">
        <label for="H">Altura da árvore (H)</label>
        <input type="number" step="any" name="H" id="H" value="<?= htmlspecialchars($H) ?>" required>
      </div>
      <div class="input-box">
        <label for="ff">Fator de forma (ff)</label>
        <input type="number" step="any" name="ff" id="ff" value="<?= htmlspecialchars($ff) ?>" required>
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
  // Toggle de tema. Os 2 SVGs (lua/sol) ficam inline no HTML e o CSS
  // decide qual mostrar via [data-theme="dark"]. Aqui só alternamos.
  function toggleTheme() {
    var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    var nxt = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', nxt);
    try { localStorage.setItem('app-theme', nxt); } catch (e) {}
  }
  // CSP script-src 'self' proíbe inline onclick — listener é a saída.
  document.addEventListener('DOMContentLoaded', function () {
    var btn = document.querySelector('.theme-toggle');
    if (btn) btn.addEventListener('click', toggleTheme);
  });
</script>

</body>
</html>
