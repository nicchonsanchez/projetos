<?php
// app.nicchon.com/api/projects.php
// Catálogo do agregador. Lê 3 fontes em ordem:
//   1) mini-apps/{slug}/manifest.json    → projetos embutidos no monorepo
//   2) external-manifests/{slug}.json    → sub-deploys (outros repos entregam em /app.nicchon.com/{slug}/)
//   3) discovery: varre o filesystem do servidor procurando pastas órfãs (sem manifest declarado)
//
// Resposta: array JSON. Pastas órfãs entram com flag _orphan=true pra alertar admin.
// Header `X-App-Orphans` traz CSV dos slugs órfãos pra monitoring leve.

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');
header('X-Content-Type-Options: nosniff');

// __DIR__ = /api. Mini-apps em deploy plano ficam em ../{slug}/ (cada uma na raiz).
// external-manifests/ fica em ../external-manifests/ se sobreviveu ao deploy.
$root = realpath(__DIR__ . '/..');
if (!$root) {
    http_response_code(500);
    echo json_encode(['error' => 'cannot resolve root']);
    exit;
}

$results = [];
$declared_slugs = [];

// 1) Manifests embutidos: cada pasta em ../ que tem manifest.json é mini-app
foreach (scandir($root) ?: [] as $entry) {
    if ($entry === '.' || $entry === '..') continue;
    if (str_starts_with($entry, '.')) continue;
    $entryPath = $root . DIRECTORY_SEPARATOR . $entry;
    if (!is_dir($entryPath)) continue;
    $manifestPath = $entryPath . DIRECTORY_SEPARATOR . 'manifest.json';
    if (!is_file($manifestPath)) continue;
    $raw = file_get_contents($manifestPath);
    if ($raw === false) continue;
    $m = json_decode($raw, true);
    if (!is_array($m)) continue;
    if (($m['oculto'] ?? false) === true) {
        $declared_slugs[] = $entry;
        continue;
    }
    $m['slug'] = $m['slug'] ?? $entry;
    $m['url'] = '/' . rawurlencode($entry) . '/';
    $m['external'] = false;
    $m['atualizado_em'] = date('Y-m-d', filemtime($manifestPath));
    $results[] = $m;
    $declared_slugs[] = $entry;
}

// 2) external-manifests/: sub-deploys de outros repos
$extDir = $root . DIRECTORY_SEPARATOR . 'external-manifests';
if (is_dir($extDir)) {
    foreach (scandir($extDir) ?: [] as $file) {
        if (substr($file, -5) !== '.json') continue;
        $manifestPath = $extDir . DIRECTORY_SEPARATOR . $file;
        $raw = file_get_contents($manifestPath);
        if ($raw === false) continue;
        $m = json_decode($raw, true);
        if (!is_array($m)) continue;
        if (($m['oculto'] ?? false) === true) {
            $declared_slugs[] = $m['slug'] ?? pathinfo($file, PATHINFO_FILENAME);
            continue;
        }
        $slug = $m['slug'] ?? pathinfo($file, PATHINFO_FILENAME);
        $m['slug'] = $slug;
        $m['url'] = $m['url'] ?? ('/' . rawurlencode($slug) . '/');
        $m['external'] = false;
        $m['sub_deploy'] = true;
        $m['atualizado_em'] = date('Y-m-d', filemtime($manifestPath));
        $results[] = $m;
        $declared_slugs[] = $slug;
    }
}

// 3) Discovery: lista pastas órfãs (existem no servidor mas sem manifest)
//    Reservadas / técnicas não contam.
$blacklist = [
    'api', 'assets', 'external-manifests', 'redirects',
    'arthur', // WordPress secundário hospedado em subpasta
    'cgi-bin', '_atalhos',
];
$orphans = [];
foreach (scandir($root) ?: [] as $entry) {
    if ($entry === '.' || $entry === '..') continue;
    if (str_starts_with($entry, '.') || str_starts_with($entry, '_')) continue;
    if (in_array($entry, $blacklist, true)) continue;
    $entryPath = $root . DIRECTORY_SEPARATOR . $entry;
    if (!is_dir($entryPath)) continue;
    if (in_array($entry, $declared_slugs, true)) continue;
    $orphans[] = $entry;
    $results[] = [
        'slug' => $entry,
        'titulo' => $entry,
        'categoria' => 'Indefinido',
        'status' => 'Sem manifest',
        'ano' => '',
        'descricao' => 'Pasta detectada em /app.nicchon.com/ sem manifest declarado. Adicionar em mini-apps/ (se for embutido) ou app/external-manifests/{slug}.json (se for sub-deploy externo).',
        'url' => '/' . rawurlencode($entry) . '/',
        'external' => false,
        '_orphan' => true,
    ];
}

if (!empty($orphans)) {
    header('X-App-Orphans: ' . implode(', ', $orphans));
}

// Ordenação: declarados primeiro (status > ano desc > titulo asc), órfãos no fim
$statusOrder = [
    'Em produção' => 0,
    'Concluído' => 1,
    'Em desenvolvimento' => 2,
    'Arquivado' => 3,
    'Sem manifest' => 99,
];
usort($results, function ($a, $b) use ($statusOrder) {
    $oa = ($a['_orphan'] ?? false) ? 1 : 0;
    $ob = ($b['_orphan'] ?? false) ? 1 : 0;
    if ($oa !== $ob) return $oa - $ob;
    $sa = $statusOrder[$a['status'] ?? ''] ?? 4;
    $sb = $statusOrder[$b['status'] ?? ''] ?? 4;
    if ($sa !== $sb) return $sa - $sb;
    $ya = (string)($a['ano'] ?? '');
    $yb = (string)($b['ano'] ?? '');
    if ($ya !== $yb) return strcmp($yb, $ya);
    return strcmp((string)($a['titulo'] ?? ''), (string)($b['titulo'] ?? ''));
});

echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
