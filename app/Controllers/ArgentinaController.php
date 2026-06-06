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
        $validKeys = array_keys($api->argLeagues);
        if (!in_array($leagueKey, $validKeys)) $leagueKey = 'argentina';

        $lg  = $api->argLeagues[$leagueKey];
        $tab = $_GET['tab'] ?? 'noticias';

        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        // Noticias argentinas (siempre cargadas)
        $total = (int)$db->query("SELECT COUNT(*) FROM news WHERE source_name = 'ESPN Argentina' OR title LIKE '%Argentina%' OR title LIKE '%Boca%' OR title LIKE '%River%' OR title LIKE '%selección%' OR title LIKE '%AFA%'")->fetchColumn();
        $stmt = $db->prepare("SELECT * FROM news WHERE source_name = 'ESPN Argentina' OR title LIKE '%Argentina%' OR title LIKE '%Boca%' OR title LIKE '%River%' OR title LIKE '%selección%' OR title LIKE '%AFA%' ORDER BY scraped_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit',  $this->perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,        \PDO::PARAM_INT);
        $stmt->execute();
        $news = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $totalPages = (int)ceil($total / $this->perPage);

        // API data — solo cargar la pestaña activa
        $standings  = [];
        $recent     = [];
        $upcoming   = [];
        $topScorers = [];
        $topAssists = [];
        $live       = [];

        if ($tab === 'tabla' || $tab === 'todo') {
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

        $argLeagues = $api->argLeagues;

        require __DIR__ . '/../Views/argentina.php';
    }
}
