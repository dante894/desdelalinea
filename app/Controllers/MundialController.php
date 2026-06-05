<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApi;

class MundialController
{
    public function index(): void
    {
        $db  = Database::getInstance();
        $api = new FootballApi();

        // Noticias del mundial
        $news = $db->query("SELECT * FROM news WHERE category = 'Mundial 2026' OR title LIKE '%mundial%' OR title LIKE '%World Cup%' OR title LIKE '%Copa del Mundo%' ORDER BY scraped_at DESC LIMIT 20")->fetchAll(\PDO::FETCH_ASSOC);

        // Datos de la API
        $standings = $api->getStandings();
        $recent    = $api->getRecentMatches();
        $upcoming  = $api->getUpcomingMatches();
        $live      = $api->getLiveMatches();

        require __DIR__ . '/../Views/mundial/index.php';
    }
}
