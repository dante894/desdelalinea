<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApiRapid;

class EuropaController
{
    public function index(): void
    {
        $db      = Database::getInstance();
        $api     = new FootballApiRapid();
        $tab     = $_GET['tab']    ?? 'tabla';
        $liga    = $_GET['liga']   ?? 'premier';

        // Validar liga
        if (!isset($api->leagues[$liga]) || $liga === 'argentina') {
            $liga = 'premier';
        }
        $lg = $api->leagues[$liga];

        // Noticias europeas
        $news = $db->query("SELECT * FROM news WHERE source_name IN ('BBC Sport','BBC Fútbol','Sky Sports','ESPN Fútbol') ORDER BY scraped_at DESC LIMIT 8")->fetchAll(\PDO::FETCH_ASSOC);

        $standings  = [];
        $recent     = [];
        $upcoming   = [];
        $topScorers = [];
        $topAssists = [];
        $live       = [];

        if ($tab === 'tabla') {
            $standings = $api->getStandings($lg['id'], $lg['season']);
        }
        if ($tab === 'resultados') {
            $recent = $api->getMatches($lg['id'], $lg['season'], 'FT', 10);
            $live   = $api->getLiveMatches($lg['id']);
        }
        if ($tab === 'proximos') {
            $upcoming = $api->getNextMatches($lg['id'], $lg['season'], 10);
        }
        if ($tab === 'jugadores') {
            $topScorers = $api->getTopScorers($lg['id'], $lg['season']);
            $topAssists = $api->getTopAssists($lg['id'], $lg['season']);
        }

        require __DIR__ . '/../Views/europa.php';
    }
}
