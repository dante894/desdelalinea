<?php

namespace App\Services;

/**
 * Servicio de datos de fútbol usando la API pública de ESPN.
 * Sin API key. Fuente: site.api.espn.com
 */
class FootballApiRapid
{
    private string $base      = 'https://site.api.espn.com/apis/site/v2/sports/soccer';
    private string $baseV2    = 'https://site.api.espn.com/apis/v2/sports/soccer';

    // ESPN league slugs
    public array $argLeagues = [
        'argentina'        => ['slug' => 'arg.1',   'name' => 'Liga Profesional', 'flag' => '🇦🇷', 'has_standings' => true],
        'copa_argentina'   => ['slug' => 'arg.copa', 'name' => 'Copa Argentina',  'flag' => '🏆',  'has_standings' => false],
        'primera_nacional' => ['slug' => 'arg.2',   'name' => 'Primera Nacional', 'flag' => '🥈',  'has_standings' => true],
    ];

    public array $leagues = [
        'argentina'  => ['slug' => 'arg.1',  'name' => 'Liga Profesional', 'flag' => '🇦🇷'],
        'premier'    => ['slug' => 'eng.1',  'name' => 'Premier League',   'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿'],
        'laliga'     => ['slug' => 'esp.1',  'name' => 'La Liga',          'flag' => '🇪🇸'],
        'seriea'     => ['slug' => 'ita.1',  'name' => 'Serie A',          'flag' => '🇮🇹'],
        'bundesliga' => ['slug' => 'ger.1',  'name' => 'Bundesliga',       'flag' => '🇩🇪'],
        'ligue1'     => ['slug' => 'fra.1',  'name' => 'Ligue 1',          'flag' => '🇫🇷'],
    ];

    // ─── HTTP helper ──────────────────────────────────────────────────────────
    private function get(string $url): ?array
    {
        $ctx = stream_context_create([
            'http' => ['timeout' => 15, 'ignore_errors' => true],
            'ssl'  => ['verify_peer' => false],
        ]);
        $resp = @file_get_contents($url, false, $ctx);
        if (!$resp) return null;
        $data = json_decode($resp, true);
        return is_array($data) ? $data : null;
    }

    // ─── Helper: extrae stat de array ESPN por nombre ─────────────────────────
    private function stat(array $stats, string $name): mixed
    {
        foreach ($stats as $s) {
            if (($s['name'] ?? '') === $name) return $s['value'] ?? $s['displayValue'] ?? null;
        }
        return null;
    }

    // ─── Standings ────────────────────────────────────────────────────────────
    // ESPN devuelve grupos (Apertura A, Apertura B, etc.) — combinamos todos
    public function getStandings(string $slug): array
    {
        $data = $this->get("{$this->baseV2}/{$slug}/standings");
        if (!$data) return [];

        $rows = [];
        foreach ($data['children'] ?? [] as $group) {
            $groupName = $group['name'] ?? '';
            foreach ($group['standings']['entries'] ?? [] as $entry) {
                $stats = $entry['stats'] ?? [];
                $team  = $entry['team'] ?? [];
                $logo  = $team['logos'][0]['href'] ?? null;
                $rows[] = [
                    'rank'     => (int)$this->stat($stats, 'rank'),
                    'name'     => $team['displayName'] ?? '—',
                    'logo'     => $logo,
                    'played'   => (int)$this->stat($stats, 'gamesPlayed'),
                    'wins'     => (int)$this->stat($stats, 'wins'),
                    'draws'    => (int)$this->stat($stats, 'ties'),
                    'losses'   => (int)$this->stat($stats, 'losses'),
                    'gf'       => (int)$this->stat($stats, 'pointsFor'),
                    'gc'       => (int)$this->stat($stats, 'pointsAgainst'),
                    'gd'       => (int)$this->stat($stats, 'pointDifferential'),
                    'points'   => (int)$this->stat($stats, 'points'),
                    'form'     => '',   // ESPN no provee forma en standings
                    'group'    => $groupName,
                ];
            }
        }

        // Ordenar por puntos desc
        usort($rows, fn($a, $b) => $b['points'] <=> $a['points'] ?: $b['gd'] <=> $a['gd']);

        // Re-numerar rank
        foreach ($rows as $i => &$r) $r['rank'] = $i + 1;

        return $rows;
    }

    // ─── Scoreboard: partidos recientes + próximos + en vivo ─────────────────
    // ESPN scoreboard solo da la fecha actual. Para más fechas usamos el param dates
    public function getScoreboard(string $slug, string $dates = ''): array
    {
        $url = "{$this->base}/{$slug}/scoreboard";
        if ($dates) $url .= "?dates={$dates}";
        $data = $this->get($url);
        return $data['events'] ?? [];
    }

    // Partidos terminados (busca últimas 4 semanas)
    public function getMatches(string $slug, int $count = 10): array
    {
        $finished = [];
        for ($weeks = 1; $weeks <= 4 && count($finished) < $count; $weeks++) {
            $from = date('Ymd', strtotime("-{$weeks} weeks"));
            $to   = date('Ymd', strtotime('-' . ($weeks - 1) . ' weeks'));
            $events = $this->getScoreboard($slug, "{$from}-{$to}");
            foreach (array_reverse($events) as $ev) {
                $comp = $ev['competitions'][0] ?? [];
                if (($comp['status']['type']['completed'] ?? false) === true) {
                    $finished[] = $this->normalizeEvent($ev);
                    if (count($finished) >= $count) break;
                }
            }
        }
        return $finished;
    }

    // Próximos partidos (busca próximas 4 semanas)
    public function getNextMatches(string $slug, int $count = 10): array
    {
        $upcoming = [];
        for ($weeks = 0; $weeks < 4 && count($upcoming) < $count; $weeks++) {
            $from = date('Ymd', strtotime("+{$weeks} weeks"));
            $to   = date('Ymd', strtotime('+' . ($weeks + 1) . ' weeks'));
            $events = $this->getScoreboard($slug, "{$from}-{$to}");
            foreach ($events as $ev) {
                $comp = $ev['competitions'][0] ?? [];
                $state = $comp['status']['type']['state'] ?? '';
                if ($state === 'pre') {
                    $upcoming[] = $this->normalizeEvent($ev);
                    if (count($upcoming) >= $count) break;
                }
            }
        }
        return $upcoming;
    }

    // Partidos en vivo
    public function getLiveMatches(string $slug): array
    {
        $events = $this->getScoreboard($slug);
        $live = [];
        foreach ($events as $ev) {
            $comp  = $ev['competitions'][0] ?? [];
            $state = $comp['status']['type']['state'] ?? '';
            if ($state === 'in') $live[] = $this->normalizeEvent($ev);
        }
        return $live;
    }

    // ─── Normalize event → estructura unificada ───────────────────────────────
    private function normalizeEvent(array $ev): array
    {
        $comp        = $ev['competitions'][0] ?? [];
        $competitors = $comp['competitors']   ?? [];
        $status      = $comp['status']        ?? [];

        $home = $away = null;
        foreach ($competitors as $c) {
            if ($c['homeAway'] === 'home') $home = $c;
            else                           $away = $c;
        }

        return [
            'id'        => $ev['id'] ?? '',
            'date'      => $ev['date'] ?? '',
            'name'      => $ev['name'] ?? '',
            'state'     => $status['type']['state']       ?? 'pre',
            'completed' => $status['type']['completed']   ?? false,
            'detail'    => $status['type']['detail']      ?? '',
            'clock'     => $status['displayClock']        ?? '',
            'venue'     => $comp['venue']['fullName']      ?? ($ev['venue']['displayName'] ?? ''),
            'round'     => $ev['season']['slug']          ?? '',
            'home' => [
                'id'     => $home['team']['id']          ?? '',
                'name'   => $home['team']['displayName'] ?? '?',
                'logo'   => $home['team']['logo']        ?? null,
                'score'  => $home['score']               ?? null,
                'winner' => $home['winner']              ?? false,
                'form'   => $home['form']                ?? '',
            ],
            'away' => [
                'id'     => $away['team']['id']          ?? '',
                'name'   => $away['team']['displayName'] ?? '?',
                'logo'   => $away['team']['logo']        ?? null,
                'score'  => $away['score']               ?? null,
                'winner' => $away['winner']              ?? false,
                'form'   => $away['form']                ?? '',
            ],
        ];
    }

    // ─── Top Scorers via ESPN athletes stats ─────────────────────────────────
    // ESPN no tiene un endpoint de goleadores en su API pública para estas ligas,
    // usamos el endpoint de athletes
    public function getTopScorers(string $slug): array
    {
        $data = $this->get("{$this->base}/{$slug}/leaders");
        if (!$data) return [];
        foreach ($data['categories'] ?? [] as $cat) {
            if (stripos($cat['name'] ?? '', 'goal') !== false || ($cat['abbreviation'] ?? '') === 'G') {
                return array_slice($cat['leaders'] ?? [], 0, 10);
            }
        }
        return array_slice($data['categories'][0]['leaders'] ?? [], 0, 10);
    }

    public function getTopAssists(string $slug): array
    {
        $data = $this->get("{$this->base}/{$slug}/leaders");
        if (!$data) return [];
        foreach ($data['categories'] ?? [] as $cat) {
            if (stripos($cat['name'] ?? '', 'assist') !== false || ($cat['abbreviation'] ?? '') === 'A') {
                return array_slice($cat['leaders'] ?? [], 0, 10);
            }
        }
        return array_slice($data['categories'][1]['leaders'] ?? [], 0, 10);
    }
}
