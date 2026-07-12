<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApiRapid;

class EuropaController
{
    public function index(): void
    {
        $db   = Database::getInstance();
        $api  = new FootballApiRapid();
        $tab  = $_GET['tab']  ?? 'tabla';
        $liga = $_GET['liga'] ?? 'premier';

        // Validar liga: excluir 'argentina' y ligas no europeas
        if (!isset($api->leagues[$liga]) || $liga === 'argentina') {
            $liga = 'premier';
        }
        $lg   = $api->leagues[$liga];
        $slug = $lg['slug'];   // ← usar 'slug', no 'id'

        // Noticias europeas: categoría 'Europa' + fuentes conocidas
        $news = $db->query("
            SELECT * FROM news
            WHERE category = 'Europa'
               OR source_name IN (
                   'Marca Fútbol','Marca Champions','AS Fútbol',
                   'Mundo Deportivo','BBC Fútbol','Sky Sports',
                   'Fútbol Argentino – Champions League'
               )
               OR title LIKE '%Champions%'
               OR title LIKE '%Premier%'
               OR title LIKE '%La Liga%'
               OR title LIKE '%Serie A%'
               OR title LIKE '%Bundesliga%'
            ORDER BY scraped_at DESC
            LIMIT 12
        ")->fetchAll(\PDO::FETCH_ASSOC);

        $standings  = [];
        $recent     = [];
        $upcoming   = [];
        $topScorers = [];
        $topAssists = [];
        $live       = [];

        if ($tab === 'tabla') {
            $standings = $api->getStandings($slug);
        }
        if ($tab === 'resultados') {
            $recent = $api->getMatches($slug, 10);
            $live   = $api->getLiveMatches($slug);
        }
        if ($tab === 'proximos') {
            $upcoming = $api->getNextMatches($slug, 10);
        }
        if ($tab === 'jugadores') {
            $topScorers = $api->getTopScorers($slug);
            $topAssists = $api->getTopAssists($slug);
        }

        require __DIR__ . '/../Views/europa.php';
    }
}