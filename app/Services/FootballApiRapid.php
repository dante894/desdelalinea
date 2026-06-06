<?php

namespace App\Services;

/**
 * Servicio de datos de fútbol usando la API pública de FotMob.
 * Base: https://www.fotmob.com/api
 * No requiere API key. Misma fuente que usa la app oficial.
 */
class FootballApiRapid
{
    private string $base = 'https://www.fotmob.com/api';

    // FotMob league IDs (verificados en las URLs del sitio)
    public array $argLeagues = [
        'argentina'        => ['id' => 112,  'name' => 'Liga Profesional', 'flag' => '🇦🇷', 'has_standings' => true],
        'copa_argentina'   => ['id' => 359,  'name' => 'Copa Argentina',   'flag' => '🏆',  'has_standings' => false],
        'primera_nacional' => ['id' => 7442, 'name' => 'Primera Nacional', 'flag' => '🥈',  'has_standings' => true],
        'copa_liga'        => ['id' => 1341, 'name' => 'Copa de la Liga',  'flag' => '⚽',  'has_standings' => false],
    ];

    public array $leagues = [
        'argentina'  => ['id' => 112,  'name' => 'Liga Profesional', 'flag' => '🇦🇷'],
        'premier'    => ['id' => 47,   'name' => 'Premier League',   'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿'],
        'laliga'     => ['id' => 87,   'name' => 'La Liga',          'flag' => '🇪🇸'],
        'seriea'     => ['id' => 55,   'name' => 'Serie A',          'flag' => '🇮🇹'],
        'bundesliga' => ['id' => 54,   'name' => 'Bundesliga',       'flag' => '🇩🇪'],
        'ligue1'     => ['id' => 53,   'name' => 'Ligue 1',          'flag' => '🇫🇷'],
    ];

    // ─── HTTP helper ─────────────────────────────────────────────────────────
    private function get(string $endpoint): ?array
    {
        $url = $this->base . $endpoint;
        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 15,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'header'     => implode("\r\n", [
                    'Accept: application/json, text/plain, */*',
                    'Accept-Language: es-AR,es;q=0.9',
                    'Referer: https://www.fotmob.com/',
                    'Origin: https://www.fotmob.com',
                ]),
            ],
            'ssl' => ['verify_peer' => false],
        ]);

        $resp = @file_get_contents($url, false, $ctx);
        if (!$resp) {
            error_log('[FotMob] Sin respuesta: ' . $url);
            return null;
        }

        $data = json_decode($resp, true);
        if (!is_array($data)) {
            error_log('[FotMob] JSON inválido en: ' . $url);
            return null;
        }

        return $data;
    }

    // ─── Liga completa (standings + fixtures) ────────────────────────────────
    // GET /api/leagues?id=112&cacheMaxAge=10800
    private function getLeagueData(int $leagueId): ?array
    {
        return $this->get("/leagues?id={$leagueId}&cacheMaxAge=10800");
    }

    // ─── Standings ───────────────────────────────────────────────────────────
    public function getStandings(int $leagueId): array
    {
        $data = $this->getLeagueData($leagueId);
        if (!$data) return [];

        // FotMob: data->table->data->table[0]->tableData->all (array de equipos)
        $tables = $data['table']['data']['table'] ?? [];

        // A veces hay zonas (Apertura/Clausura), tomamos la primera/general
        $rows = [];
        if (!empty($tables)) {
            $first = $tables[0];
            $rows  = $first['tableData']['all'] ?? $first['tableRows'] ?? [];
        }

        return $rows;
    }

    // ─── Resultados recientes ────────────────────────────────────────────────
    // GET /api/matches?date=YYYYMMDD — filtramos por liga
    public function getMatches(int $leagueId, int $count = 10): array
    {
        $data = $this->getLeagueData($leagueId);
        if (!$data) return [];

        $matches = $data['matches']['allMatches'] ?? [];

        // Filtrar los terminados y tomar los últimos $count
        $finished = array_filter($matches, fn($m) =>
            ($m['status']['finished'] ?? false) === true
        );
        $finished = array_reverse(array_values($finished));
        return array_slice($finished, 0, $count);
    }

    // ─── Próximos partidos ───────────────────────────────────────────────────
    public function getNextMatches(int $leagueId, int $count = 10): array
    {
        $data = $this->getLeagueData($leagueId);
        if (!$data) return [];

        $matches = $data['matches']['allMatches'] ?? [];

        $upcoming = array_filter($matches, fn($m) =>
            ($m['status']['started'] ?? false) === false &&
            ($m['status']['finished'] ?? false) === false
        );
        return array_slice(array_values($upcoming), 0, $count);
    }

    // ─── Partidos en vivo ────────────────────────────────────────────────────
    public function getLiveMatches(int $leagueId): array
    {
        $data = $this->getLeagueData($leagueId);
        if (!$data) return [];

        $matches = $data['matches']['allMatches'] ?? [];

        return array_values(array_filter($matches, fn($m) =>
            ($m['status']['started'] ?? false) === true &&
            ($m['status']['finished'] ?? false) === false
        ));
    }

    // ─── Goleadores ──────────────────────────────────────────────────────────
    // GET /api/leagueseasondeepstats?id=112&season=...&type=topscorers
    public function getTopScorers(int $leagueId): array
    {
        $data = $this->get("/leagueseasondeepstats?id={$leagueId}&type=topscorers");
        return array_slice($data['stats']['players'] ?? [], 0, 10);
    }

    public function getTopAssists(int $leagueId): array
    {
        $data = $this->get("/leagueseasondeepstats?id={$leagueId}&type=topassists");
        return array_slice($data['stats']['players'] ?? [], 0, 10);
    }
}
