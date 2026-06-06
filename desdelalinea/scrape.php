<?php
/**
 * CLI scraper: php scrape.php
 * Cron ejemplo: */30 * * * * php /var/www/html/scrape.php >> /var/log/scraper.log 2>&1
 */
require_once __DIR__ . '/config/bootstrap.php';

use App\Services\Scraper;

$scraper = new Scraper();
$result  = $scraper->run();

echo "\n=== Scraper Desde la Línea ===\n";
echo date('Y-m-d H:i:s') . "\n\n";
foreach ($result['log'] as $line) {
    echo $line . "\n";
}
echo "\n📥 Total procesados : {$result['total']}\n";
echo "✨ Nuevos guardados : {$result['new']}\n";
if (!empty($result['errors'])) {
    echo "\n⚠️  Errores:\n";
    foreach ($result['errors'] as $err) echo "   - $err\n";
}
echo "\n";
