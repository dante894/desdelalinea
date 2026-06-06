<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApiRapid;

class HomeController
{
    public function index(): void
    {
        $db       = Database::getInstance();
        $api      = new FootballApiRapid();

        $featured   = $db->query("SELECT * FROM news ORDER BY scraped_at DESC LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        $latest     = $db->query("SELECT * FROM news ORDER BY scraped_at DESC LIMIT 9 OFFSET 1")->fetchAll(\PDO::FETCH_ASSOC);
        $categories = $db->query("SELECT DISTINCT category FROM news ORDER BY category")->fetchAll(\PDO::FETCH_COLUMN);

        // Partidos en vivo — todas las ligas argentinas + mundial
        $liveMatches = $api->getAllLiveMatches();
        $mundialLive = $api->getMundialLive();
        foreach ($mundialLive as &$m) {
            $m['league_name'] = 'Mundial 2026';
            $m['league_flag'] = '🏆';
            $m['league_key']  = 'mundial';
        }
        $liveMatches = array_merge($liveMatches, $mundialLive);

        // Próximos partidos destacados (Liga Profesional + Mundial)
        $upcomingArg = $api->getNextMatches('arg.1', 5);
        foreach ($upcomingArg as &$m) {
            $m['league_name'] = 'Liga Profesional';
            $m['league_flag'] = '🇦🇷';
        }
        $upcomingMundial = $api->getMundialUpcoming(5);
        foreach ($upcomingMundial as &$m) {
            $m['league_name'] = 'Mundial 2026';
            $m['league_flag'] = '🏆';
        }
        $upcomingMatches = array_merge($upcomingArg, $upcomingMundial);
        usort($upcomingMatches, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
        $upcomingMatches = array_slice($upcomingMatches, 0, 8);

        // Últimos resultados
        $recentArg = $api->getMatches('arg.1', 5);
        foreach ($recentArg as &$m) {
            $m['league_name'] = 'Liga Profesional';
            $m['league_flag'] = '🇦🇷';
        }

        require __DIR__ . '/../Views/home.php';
    }
}
