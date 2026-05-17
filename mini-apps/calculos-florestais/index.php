<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cálculos florestais</title>

  <meta name="theme-color" content="#F5F0E6" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#11141C" media="(prefers-color-scheme: dark)">

  <meta name="author" content="Nicchon Sanchez">
  <meta name="description" content="Três calculadoras pra manejo florestal: volume de árvores isoladas, cilindro e pilha de lenha.">
  <meta property="og:title" content="Cálculos florestais — Nicchon Sanchez">
  <meta property="og:description" content="Calculadoras pra manejo florestal.">

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

<main class="hub">
  <section class="hub-hero">
    <p class="font-mono-meta">Manejo florestal</p>
    <h1 class="font-serif-display">Cálculos florestais.</h1>
    <p class="lede">Três calculadoras feitas para um madeireiro de verdade. Escolha qual usar.</p>
  </section>

  <div class="hub-grid">
    <a href="../volume-arvores-isoladas/" class="hub-card">
      <p class="hub-card-meta">01</p>
      <h2>Árvores isoladas</h2>
      <p class="hub-card-desc">Volume estimado de uma árvore a partir do DAP (diâmetro à altura do peito), altura e fator de forma.</p>
      <p class="hub-card-formula"><code>V = (π · DAP² / 4) · H · ff</code></p>
    </a>

    <a href="../volume-cilindro/" class="hub-card">
      <p class="hub-card-meta">02</p>
      <h2>Cilindro</h2>
      <p class="hub-card-desc">Volume de tora cilíndrica a partir dos diâmetros das duas pontas e altura.</p>
      <p class="hub-card-formula"><code>V = π · ((d₁+d₂)/4)² · H</code></p>
    </a>

    <a href="../volume-pilha-de-lenha/" class="hub-card">
      <p class="hub-card-meta">03</p>
      <h2>Pilha de lenha</h2>
      <p class="hub-card-desc">Volume real (m³) de uma pilha de lenha (estéreo) descontando os vazios pelo fator de empilhamento.</p>
      <p class="hub-card-formula"><code>V = (H · C · L) / fe</code></p>
    </a>
  </div>
</main>

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
