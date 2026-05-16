<?php
// app.nicchon.com/api/projects.php
// Varre ../mini-apps/{slug}/manifest.json e devolve lista ordenada.
// Sem dependência de DB. Sem cache (volume baixo).

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');
header('X-Content-Type-Options: nosniff');

// __DIR__ = /api, então mini-apps fica em ../../mini-apps no deploy plano
// e em ../mini-apps no layout do app local. Tenta os dois.
$candidates = [
    __DIR__ . '/../mini-apps',
    __DIR__ . '/../../mini-apps',
    __DIR__ . '/../',  // fallback: irmãos do api/ no deploy plano
];

$base = null;
foreach ($candidates as $c) {
    if (is_dir($c)) { $base = realpath($c); break; }
}

if (!$base) {
    http_response_code(500);
    echo json_encode(['error' => 'mini-apps directory not found']);
    exit;
}

$results = [];
foreach (scandir($base) ?: [] as $entry) {
    if ($entry === '.' || $entry === '..') continue;
    if ($entry === 'api' || $entry === 'assets' || str_starts_with($entry, '.')) continue;
    $slugPath = $base . DIRECTORY_SEPARATOR . $entry;
    if (!is_dir($slugPath)) continue;
    $manifestPath = $slugPath . DIRECTORY_SEPARATOR . 'manifest.json';
    if (!is_file($manifestPath)) continue;
    $raw = file_get_contents($manifestPath);
    if ($raw === false) continue;
    $m = json_decode($raw, true);
    if (!is_array($m)) continue;
    if (($m['oculto'] ?? false) === true) continue;
    $m['slug'] = $m['slug'] ?? $entry;
    $m['url'] = '/' . rawurlencode($entry) . '/';
    $m['external'] = false;
    $m['atualizado_em'] = date('Y-m-d', filemtime($manifestPath));
    $results[] = $m;
}

// Ordena: status "Em produção" > "Concluído" > "Em desenvolvimento" > "Arquivado"
// Depois por ano desc, depois por título asc.
$statusOrder = [
    'Em produção' => 0,
    'Em produção ' => 0,
    'Concluído' => 1,
    'Em desenvolvimento' => 2,
    'Arquivado' => 3,
];
usort($results, function ($a, $b) use ($statusOrder) {
    $sa = $statusOrder[$a['status'] ?? ''] ?? 4;
    $sb = $statusOrder[$b['status'] ?? ''] ?? 4;
    if ($sa !== $sb) return $sa - $sb;
    $ya = (string)($a['ano'] ?? '');
    $yb = (string)($b['ano'] ?? '');
    if ($ya !== $yb) return strcmp($yb, $ya);
    return strcmp((string)($a['titulo'] ?? ''), (string)($b['titulo'] ?? ''));
});

echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
