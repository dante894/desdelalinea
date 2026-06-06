<?php
/**
 * Diagnóstico FotMob API — desdelalinea
 * https://desdelalinea.onrender.com/api_test.php?action=league&id=112
 * BORRAR DESPUÉS DE DIAGNOSTICAR.
 */
header('Content-Type: application/json; charset=utf-8');

function fotmob(string $endpoint): array {
    $url = 'https://www.fotmob.com/api' . $endpoint;
    $ctx = stream_context_create([
        'http' => [
            'timeout'    => 15,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
            'header'     => "Accept: application/json\r\nReferer: https://www.fotmob.com/\r\n",
        ],
        'ssl' => ['verify_peer' => false],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    if (!$resp) return ['error' => error_get_last()['message'] ?? 'no response', 'url' => $url];
    $data = json_decode($resp, true);
    return $data ?? ['error' => 'invalid json'];
}

$action = $_GET['action'] ?? 'info';
$id     = (int)($_GET['id'] ?? 112);

if ($action === 'league') {
    $data = fotmob("/leagues?id={$id}&cacheMaxAge=10800");
    // Mostrar solo estructura, no todo el JSON gigante
    echo json_encode([
        'league_name'     => $data['details']['name'] ?? null,
        'standings_keys'  => isset($data['table'])   ? array_keys($data['table'])   : null,
        'matches_keys'    => isset($data['matches']) ? array_keys($data['matches']) : null,
        'first_match'     => $data['matches']['allMatches'][0] ?? null,
        'standings_sample'=> $data['table']['data']['table'][0]['tableData']['all'][0]
                          ?? $data['table']['data']['table'][0]['tableRows'][0]
                          ?? null,
        'raw_table_keys'  => isset($data['table']['data']['table'][0])
                          ? array_keys($data['table']['data']['table'][0])
                          : null,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'scorers') {
    $data = fotmob("/leagueseasondeepstats?id={$id}&type=topscorers");
    echo json_encode([
        'keys'   => array_keys($data),
        'sample' => $data['stats']['players'][0] ?? null,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode([
        'uso' => [
            'datos_liga'    => "?action=league&id=112",
            'goleadores'    => "?action=scorers&id=112",
        ],
        'ids_arg' => [
            'liga_profesional' => 112,
            'copa_argentina'   => 359,
            'primera_nacional' => 7442,
            'copa_liga'        => 1341,
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
