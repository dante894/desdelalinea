<?php
header('Content-Type: application/json; charset=utf-8');

function espn(string $url): array {
    $ctx = stream_context_create(['http' => ['timeout' => 15, 'ignore_errors' => true], 'ssl' => ['verify_peer' => false]]);
    $resp = @file_get_contents($url, false, $ctx);
    return $resp ? json_decode($resp, true) ?? [] : [];
}

$action = $_GET['action'] ?? 'info';

if ($action === 'scoreboard') {
    $d = espn('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.1/scoreboard');
    // Mostrar primer evento como muestra
    $ev = $d['events'][0] ?? null;
    echo json_encode(['total_events' => count($d['events'] ?? []), 'sample_event' => $ev], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'standings') {
    $d = espn('https://site.api.espn.com/apis/v2/sports/soccer/arg.1/standings');
    $group = $d['children'][0] ?? $d;
    $entry = $group['standings']['entries'][0] ?? null;
    echo json_encode([
        'groups'       => array_column($d['children'] ?? [], 'name'),
        'sample_entry' => $entry,
        'stat_names'   => array_column($entry['stats'] ?? [], 'name'),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'summary') {
    $id = $_GET['id'] ?? '';
    $d = espn("https://site.api.espn.com/apis/site/v2/sports/soccer/arg.1/summary?event={$id}");
    echo json_encode(['keys' => array_keys($d)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'leagues') {
    // Probar otras ligas arg disponibles en ESPN
    $tests = [
        'arg.1_scoreboard' => espn('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.1/scoreboard'),
        'arg.copa_scoreboard' => espn('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.copa/scoreboard'),
        'arg.2_scoreboard' => espn('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.2/scoreboard'),
    ];
    echo json_encode(array_map(fn($d) => [
        'ok' => !empty($d['events']),
        'league' => $d['leagues'][0]['name'] ?? null,
        'events' => count($d['events'] ?? []),
    ], $tests), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode([
        'endpoints' => [
            'scoreboard'    => '?action=scoreboard',
            'standings'     => '?action=standings',
            'otras_ligas'   => '?action=leagues',
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
