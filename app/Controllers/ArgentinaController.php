<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApiRapid;

class ArgentinaController
{
    private int $perPage = 12;

    public function index(): void
    {
        $db  = Database::getInstance();
        $api = new FootballApiRapid();

        // Liga seleccionada
        $leagueKey = $_GET['liga'] ?? 'argentina';
        if (!array_key_exists($leagueKey, $api->argLeagues)) $leagueKey = 'argentina';
        $lg   = $api->argLeagues[$leagueKey];
        $slug = $lg['slug'];

        $tab  = $_GET['tab'] ?? 'noticias';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        // Noticias argentinas
        $whereArg = "category = 'Argentina'
             OR title LIKE '%Argentina%' OR title LIKE '%Boca%' OR title LIKE '%River%'
             OR title LIKE '%selección%' OR title LIKE '%AFA%'
             OR title LIKE '%Racing%' OR title LIKE '%Independiente%'
             OR title LIKE '%San Lorenzo%' OR title LIKE '%Vélez%'
             OR title LIKE '%Estudiantes%' OR title LIKE '%Talleres%'
             OR title LIKE '%Huracán%' OR title LIKE '%Lanús%'";

        $total = (int)$db->query("SELECT COUNT(*) FROM news WHERE {$whereArg}")->fetchColumn();

        $stmt = $db->prepare("SELECT * FROM news WHERE {$whereArg} ORDER BY scraped_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit',  $this->perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,        \PDO::PARAM_INT);
        $stmt->execute();
        $news       = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $totalPages = (int)ceil($total / $this->perPage);

        // Datos de API — solo la tab activa
        $standings       = [];
        $recent          = [];
        $upcoming        = [];
        $topScorers      = [];
        $topAssists      = [];
        $live            = [];
        $historicalMatches = [];
        $histYear        = (int)($_GET['year'] ?? date('Y'));
        $histMonth       = isset($_GET['month']) ? (int)$_GET['month'] : null;

        if ($tab === 'tabla') {
            $standings = $api->getStandings($slug);
        }
        if ($tab === 'resultados') {
            $recent = $api->getMatches($slug, 20);
            $live   = $api->getLiveMatches($slug);
        }
        if ($tab === 'historicos') {
            $historicalMatches = $api->getHistoricalMatches($slug, $histYear, $histMonth, 50);
        }
        if ($tab === 'proximos') {
            $upcoming = $api->getNextMatches($slug, 15);
        }
        if ($tab === 'jugadores') {
            $topScorers = $api->getTopScorers($slug);
            $topAssists = $api->getTopAssists($slug);
        }

        // Todos los partidos en vivo de todas las ligas arg (para badge)
        $allLive    = $api->getAllLiveMatches();
        $argLeagues = $api->argLeagues;
        $historicalSeasons = $api->historicalSeasons;

        require __DIR__ . '/../Views/argentina.php';
    }
}