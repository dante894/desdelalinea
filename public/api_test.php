<?php
/**
 * Diagnóstico apifootball.com
 * Acceder: https://desdelalinea.onrender.com/api_test.php
 * BORRAR DESPUÉS DE DIAGNOSTICAR.
 */
header('Content-Type: application/json; charset=utf-8');

$key  = '2aae56c367a376f78bb3048cad2c33007a0d0c03d5fe7eac98b6a75f8ab8e417';
$base = 'https://apiv3.apifootball.com';

function apiFetch(string $url): array {
    $ctx = stream_context_create([
        'http' => ['timeout' => 15, 'user_agent' => 'DesdeLaLinea/1.0'],
        'ssl'  => ['verify_peer' => false],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    if (!$resp) return ['error' => error_get_last()['message'] ?? 'no response'];
    $data = json_decode($resp, true);
    return $data ?? ['error' => 'invalid json'];
}

$action = $_GET['action'] ?? 'status';

if ($action === 'leagues_arg') {
    // Buscar country_id de Argentina primero
    $countries = apiFetch("$base/?action=get_countries&APIkey=$key");
    $argId = null;
    foreach ((array)$countries as $c) {
        if (stripos($c['country_name'] ?? '', 'argentina') !== false) {
            $argId = $c['country_id'];
            break;
        }
    }
    $leagues = $argId ? apiFetch("$base/?action=get_leagues&country_id=$argId&APIkey=$key") : [];
    echo json_encode([
        'argentina_country_id' => $argId,
        'leagues' => array_map(fn($l) => [
            'id'   => $l['league_id'],
            'name' => $l['league_name'],
            'season' => $l['league_season'],
        ], (array)$leagues)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'standings') {
    $lid = $_GET['lid'] ?? 44;
    $data = apiFetch("$base/?action=get_standings&league_id=$lid&APIkey=$key");
    $sample = is_array($data) ? array_slice($data, 0, 3) : $data;
    echo json_encode(['sample_3_rows' => $sample], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'events') {
    $lid  = $_GET['lid'] ?? 44;
    $from = date('Y-m-d', strtotime('-30 days'));
    $to   = date('Y-m-d', strtotime('+30 days'));
    $data = apiFetch("$base/?action=get_events&from=$from&to=$to&league_id=$lid&APIkey=$key");
    $sample = is_array($data) ? array_slice(array_values($data), 0, 2) : $data;
    echo json_encode(['sample_2_events' => $sample], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode([
        'endpoints' => [
            'ligas_argentina' => '?action=leagues_arg',
            'tabla_liga44'    => '?action=standings&lid=44',
            'partidos_liga44' => '?action=events&lid=44',
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
