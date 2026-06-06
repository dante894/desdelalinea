<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\FootballApiRapid;
use App\Services\FootballApi;

class MundialController
{
    public function index(): void
    {
        $db      = Database::getInstance();
        $apiEspn = new FootballApiRapid();

        // También intentamos football-data.org como fallback para grupos
        $apiFd = null;
        try { $apiFd = new FootballApi(); } catch (\Throwable $e) {}

        // Noticias del mundial
        $news = $db->query(
            "SELECT * FROM news
             WHERE category = 'Mundial 2026'
                OR title LIKE '%mundial%'
                OR title LIKE '%World Cup%'
                OR title LIKE '%Copa del Mundo%'
                OR title LIKE '%FIFA%'
             ORDER BY scraped_at DESC LIMIT 20"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $tab = $_GET['tab'] ?? 'partidos';

        // Datos vía ESPN (sin key)
        $live     = $apiEspn->getMundialLive();
        $recent   = [];
        $upcoming = [];
        $standings = [];
        $topScorers = [];

        if ($tab === 'partidos' || $tab === 'resultados') {
            $recent = $apiEspn->getMundialMatches(20);
        }
        if ($tab === 'proximos') {
            $upcoming = $apiEspn->getMundialUpcoming(20);
        }
        if ($tab === 'tabla') {
            // Intentamos ESPN primero, luego football-data como fallback
            $standings = $apiEspn->getMundialStandings();
            if (empty($standings) && $apiFd) {
                $standingsFd = $apiFd->getStandings();
                // Normalizar formato de football-data
                foreach ($standingsFd as $group) {
                    $groupRows = [];
                    $groupName = $group['group'] ?? 'Grupo';
                    foreach ($group['table'] ?? [] as $i => $row) {
                        $groupRows[] = [
                            'rank'   => $row['position'],
                            'name'   => $row['team']['name'] ?? '—',
                            'logo'   => $row['team']['crest'] ?? null,
                            'played' => $row['playedGames'] ?? 0,
                            'wins'   => $row['won'] ?? 0,
                            'draws'  => $row['draw'] ?? 0,
                            'losses' => $row['lost'] ?? 0,
                            'gf'     => $row['goalsFor'] ?? 0,
                            'gc'     => $row['goalsAgainst'] ?? 0,
                            'gd'     => $row['goalDifference'] ?? 0,
                            'points' => $row['points'] ?? 0,
                            'form'   => '',
                            'group'  => $groupName,
                        ];
                    }
                    $standings = array_merge($standings, $groupRows);
                }
            }
        }
        if ($tab === 'goleadores') {
            $topScorers = $apiEspn->getMundialTopScorers();
        }

        // Grupos agrupados para vista de grupos
        $standingsByGroup = [];
        foreach ($standings as $row) {
            $standingsByGroup[$row['group'] ?? 'General'][] = $row;
        }

        require __DIR__ . '/../Views/mundial/index.php';
    }
}