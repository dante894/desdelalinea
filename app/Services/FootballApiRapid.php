<?php

namespace App\Services;

class FootballApiRapid
{
    private string $key  = '7427d2b6abmsh740da8a6c7bd8b1p1cac25jsnf4292e162a89';
    private string $host = 'api-football-v1.p.rapidapi.com';
    private string $base = 'https://api-football-v1.p.rapidapi.com/v3';

    // IDs de ligas
    public array $leagues = [
        'argentina' => ['id' => 128, 'name' => 'Liga Profesional', 'flag' => '🇦🇷', 'season' => 2024],
        'premier'   => ['id' => 39,  'name' => 'Premier League',   'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'season' => 2024],
        'laliga'    => ['id' => 140, 'name' => 'La Liga',          'flag' => '🇪🇸', 'season' => 2024],
        'seriea'    => ['id' => 135, 'name' => 'Serie A',          'flag' => '🇮🇹', 'season' => 2024],
        'bundesliga'=> ['id' => 78,  'name' => 'Bundesliga',       'flag' => '🇩🇪', 'season' => 2024],
        'ligue1'    => ['id' => 61,  'name' => 'Ligue 1',          'flag' => '🇫🇷', 'season' => 2024],
    ];

    private function get(string $endpoint): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 12,
                'user_agent' => 'DesdeLaLinea/1.0',
                'header'     => implode("\r\n", [
                    "X-RapidAPI-Key: {$this->key}",
                    "X-RapidAPI-Host: {$this->host}",
                ]),
            ],
            'ssl' => ['verify_peer' => false],
        ]);
        $resp = @file_get_contents($this->base . $endpoint, false, $ctx);
        if (!$resp) return null;
        $data = json_decode($resp, true);
        return $data['response'] ?? null;
    }

    public function getStandings(int $leagueId, int $season): array
    {
        $data = $this->get("/standings?league={$leagueId}&season={$season}");
        return $data[0]['league']['standings'][0] ?? [];
    }

    public function getMatches(int $leagueId, int $season, string $status = 'FT', int $last = 10): array
    {
        $data = $this->get("/fixtures?league={$leagueId}&season={$season}&status={$status}&last={$last}");
        return $data ?? [];
    }

    public function getNextMatches(int $leagueId, int $season, int $next = 10): array
    {
        $data = $this->get("/fixtures?league={$leagueId}&season={$season}&next={$next}");
        return $data ?? [];
    }

    public function getTopScorers(int $leagueId, int $season): array
    {
        $data = $this->get("/players/topscorers?league={$leagueId}&season={$season}");
        return array_slice($data ?? [], 0, 10);
    }

    public function getTopAssists(int $leagueId, int $season): array
    {
        $data = $this->get("/players/topassists?league={$leagueId}&season={$season}");
        return array_slice($data ?? [], 0, 10);
    }

    public function getLiveMatches(int $leagueId): array
    {
        $data = $this->get("/fixtures?league={$leagueId}&live=all");
        return $data ?? [];
    }

    public function getSquad(int $teamId): array
    {
        $data = $this->get("/players/squads?team={$teamId}");
        return $data[0]['players'] ?? [];
    }

    public function getTeamStats(int $teamId, int $leagueId, int $season): ?array
    {
        $data = $this->get("/teams/statistics?team={$teamId}&league={$leagueId}&season={$season}");
        return $data[0] ?? null;
    }
}
