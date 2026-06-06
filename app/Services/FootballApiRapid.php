<?php

namespace App\Services;

class FootballApiRapid
{
    private string $key  = '2aae56c367a376f78bb3048cad2c33007a0d0c03d5fe7eac98b6a75f8ab8e417';
    private string $base = 'https://apiv3.apifootball.com';

    // ─── Ligas argentinas ────────────────────────────────────────────────────
    // IDs según apifootball.com coverage (#44 Liga Prof, #515 Copa Arg, #1189 Primera Nacional)
    public array $argLeagues = [
        'argentina'        => ['id' => 44,   'name' => 'Liga Profesional', 'flag' => '🇦🇷', 'has_standings' => true],
        'copa_argentina'   => ['id' => 515,  'name' => 'Copa Argentina',   'flag' => '🏆',  'has_standings' => false],
        'primera_nacional' => ['id' => 1189, 'name' => 'Primera Nacional', 'flag' => '🥈',  'has_standings' => true],
    ];

    // Para compatibilidad con EuropaController
    public array $leagues = [
        'argentina'  => ['id' => 44,  'name' => 'Liga Profesional', 'flag' => '🇦🇷'],
        'premier'    => ['id' => 152, 'name' => 'Premier League',   'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿'],
        'laliga'     => ['id' => 302, 'name' => 'La Liga',          'flag' => '🇪🇸'],
        'seriea'     => ['id' => 207, 'name' => 'Serie A',          'flag' => '🇮🇹'],
        'bundesliga' => ['id' => 175, 'name' => 'Bundesliga',       'flag' => '🇩🇪'],
        'ligue1'     => ['id' => 168, 'name' => 'Ligue 1',          'flag' => '🇫🇷'],
    ];

    // ─── HTTP helper ─────────────────────────────────────────────────────────
    private function get(string $action, array $params = []): ?array
    {
        $params['action'] = $action;
        $params['APIkey'] = $this->key;
        $url = $this->base . '/?' . http_build_query($params);

        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 15,
                'user_agent' => 'DesdeLaLinea/1.0',
            ],
            'ssl' => ['verify_peer' => false],
        ]);

        $resp = @file_get_contents($url, false, $ctx);
        if (!$resp) {
            error_log('[FootballAPI] Sin respuesta: ' . $url);
            return null;
        }

        $data = json_decode($resp, true);

        // apifootball devuelve {"error":...} cuando hay problema
        if (isset($data['error'])) {
            error_log('[FootballAPI] Error: ' . json_encode($data));
            return null;
        }

        return is_array($data) ? $data : null;
    }

    // ─── Standings ───────────────────────────────────────────────────────────
    // apifootball devuelve: [{standing_place, team_name, team_badge, overall_*...}]
    public function getStandings(int $leagueId, int $season = 0): array
    {
        $params = ['league_id' => $leagueId];
        $data = $this->get('get_standings', $params);
        return $data ?? [];
    }

    // ─── Fixtures / Results ──────────────────────────────────────────────────
    // apifootball: action=get_events, parámetros: from, to, league_id
    public function getMatches(int $leagueId, int $season = 0, string $status = 'FT', int $last = 10): array
    {
        $to   = date('Y-m-d');
        $from = date('Y-m-d', strtotime('-60 days'));
        $data = $this->get('get_events', [
            'from'      => $from,
            'to'        => $to,
            'league_id' => $leagueId,
        ]);
        if (!$data) return [];
        // Filtrar solo partidos terminados y tomar los últimos $last
        $finished = array_filter($data, fn($m) => ($m['match_status'] ?? '') === 'Finished');
        $finished = array_values($finished);
        return array_slice(array_reverse($finished), 0, $last);
    }

    public function getNextMatches(int $leagueId, int $season = 0, int $next = 10): array
    {
        $from = date('Y-m-d');
        $to   = date('Y-m-d', strtotime('+60 days'));
        $data = $this->get('get_events', [
            'from'      => $from,
            'to'        => $to,
            'league_id' => $leagueId,
        ]);
        if (!$data) return [];
        $upcoming = array_filter($data, fn($m) => ($m['match_status'] ?? '') === '');
        return array_slice(array_values($upcoming), 0, $next);
    }

    public function getLiveMatches(int $leagueId): array
    {
        $data = $this->get('get_livescore', ['league_id' => $leagueId]);
        return $data ?? [];
    }

    // ─── Top Scorers ─────────────────────────────────────────────────────────
    // apifootball: action=get_topscorers, league_id
    public function getTopScorers(int $leagueId, int $season = 0): array
    {
        $data = $this->get('get_topscorers', ['league_id' => $leagueId]);
        return array_slice($data ?? [], 0, 10);
    }

    public function getTopAssists(int $leagueId, int $season = 0): array
    {
        // apifootball no tiene endpoint separado de asistencias, usamos topscorers y ordenamos
        $data = $this->get('get_topscorers', ['league_id' => $leagueId]);
        if (!$data) return [];
        usort($data, fn($a, $b) => (int)($b['player_assists'] ?? 0) - (int)($a['player_assists'] ?? 0));
        return array_slice($data, 0, 10);
    }
}
