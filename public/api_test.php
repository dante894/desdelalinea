<?php
/**
 * Diagnóstico rápido de la API de fútbol.
 * Acceder: https://desdelalinea.onrender.com/api_test.php
 * BORRAR DESPUÉS DE DIAGNOSTICAR.
 */
header('Content-Type: application/json; charset=utf-8');

$key  = '7427d2b6abmsh740da8a6c7bd8b1p1cac25jsnf4292e162a89';
$host = 'api-football-v1.p.rapidapi.com';
$base = 'https://api-football-v1.p.rapidapi.com/v3';

function apiGet(string $url, string $key, string $host): array {
    $ctx = stream_context_create([
        'http' => [
            'timeout'    => 12,
            'user_agent' => 'DesdeLaLinea/1.0',
            'header'     => "X-RapidAPI-Key: {$key}\r\nX-RapidAPI-Host: {$host}",
        ],
        'ssl' => ['verify_peer' => false],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    return [
        'raw_length' => $resp ? strlen($resp) : 0,
        'decoded'    => $resp ? json_decode($resp, true) : null,
        'error'      => $resp ? null : error_get_last()['message'] ?? 'no response',
    ];
}

$tests = [
    'liga_profesional_2024' => apiGet("{$base}/standings?league=128&season=2024", $key, $host),
    'liga_profesional_2025' => apiGet("{$base}/standings?league=128&season=2025", $key, $host),
    'fixtures_next_2025'    => apiGet("{$base}/fixtures?league=128&season=2025&next=3", $key, $host),
    'status'                => apiGet("{$base}/status", $key, $host),
];

// Resumir resultados para no mostrar todo el JSON gigante
$summary = [];
foreach ($tests as $name => $result) {
    $decoded = $result['decoded'];
    $summary[$name] = [
        'ok'            => $result['raw_length'] > 0,
        'raw_length'    => $result['raw_length'],
        'response_count'=> isset($decoded['response']) ? count($decoded['response']) : null,
        'errors'        => $decoded['errors'] ?? null,
        'message'       => $decoded['message'] ?? null,
        'error'         => $result['error'],
    ];
}

echo json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
