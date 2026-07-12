<?php
/**
 * Endpoint de cron interno.
 * Render puede llamarlo via Cron Job: GET /cron.php
 * O configurar en Render Dashboard > Cron Jobs:
 *   Command: curl https://desdelalinea.onrender.com/cron.php
 *   Schedule: 0/30 * * * *  (cada 30 min)
 */

// Clave de seguridad simple
$key = $_GET['key'] ?? '';
if ($key !== 'desdelalinea2026') {
    http_response_code(403);
    echo 'No autorizado';
    exit;
}

require_once __DIR__ . '/../config/bootstrap.php';

use App\Services\Scraper;

$start   = microtime(true);
$scraper = new Scraper();
$result  = $scraper->run();
$elapsed = round(microtime(true) - $start, 2);

header('Content-Type: application/json');
echo json_encode([
    'ok'      => true,
    'time'    => $elapsed . 's',
    'total'   => $result['total'],
    'new'     => $result['new'],
    'log'     => $result['log'],
    'errors'  => $result['errors'],
    'ran_at'  => date('Y-m-d H:i:s'),
]);
