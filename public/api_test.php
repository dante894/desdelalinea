<?php
header('Content-Type: application/json; charset=utf-8');

function fetch(string $url, array $headers = []): array {
    $ctx = stream_context_create([
        'http' => [
            'timeout'         => 15,
            'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
            'header'          => implode("\r\n", $headers),
            'ignore_errors'   => true,  // ← captura respuesta aunque sea 4xx/5xx
        ],
        'ssl' => ['verify_peer' => false],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    $httpCode = 0;
    if (isset($http_response_header)) {
        preg_match('/HTTP\/\S+ (\d+)/', $http_response_header[0] ?? '', $m);
        $httpCode = (int)($m[1] ?? 0);
    }
    return [
        'http_code' => $httpCode,
        'length'    => $resp ? strlen($resp) : 0,
        'preview'   => $resp ? substr($resp, 0, 300) : null,
        'error'     => $resp ? null : (error_get_last()['message'] ?? 'no response'),
    ];
}

$results = [];

// Test 1: FotMob sin headers extra
$results['fotmob_plain'] = fetch('https://www.fotmob.com/api/leagues?id=112&cacheMaxAge=10800');

// Test 2: FotMob con headers completos de browser
$results['fotmob_full_headers'] = fetch(
    'https://www.fotmob.com/api/leagues?id=112&cacheMaxAge=10800',
    [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: es-AR,es;q=0.9,en;q=0.8',
        'Referer: https://www.fotmob.com/es/leagues/112/overview/liga-profesional',
        'Origin: https://www.fotmob.com',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
    ]
);

// Test 3: ESPN API pública (no requiere key)
$results['espn_arg'] = fetch('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.1/scoreboard');

// Test 4: ESPN standings
$results['espn_standings'] = fetch('https://site.api.espn.com/apis/v2/sports/soccer/arg.1/standings');

// Test 5: SofaScore (otra opción)
$results['sofascore_arg'] = fetch('https://api.sofascore.com/api/v1/unique-tournament/406/season/latest/standings/total');

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
