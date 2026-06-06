<?php

namespace App\Services;

/**
 * Servicio de datos de fútbol usando la API pública de ESPN.
 * Sin API key. Fuente: site.api.espn.com
 * 
 * Versión mejorada: agrega resultados históricos, búsqueda por fecha,
 * estadísticas de equipos y soporte ampliado de ligas argentinas.
 */
class FootballApiRapid
{
    private string $base   = 'https://site.api.espn.com/apis/site/v2/sports/soccer';
    private string $baseV2 = 'https://site.api.espn.com/apis/v2/sports/soccer';

    // Liga Profesional Argentina temporadas históricas
    public array $historicalSeasons = [
        '2025' => 'Temporada 2025',
        '2024' => 'Torneo Clausura 2024 / Apertura 2024',
        '2023' => 'Torneo Clausura 2023 / Apertura 2023',
        '2022' => 'Torneo Clausura 2022 / Apertura 2022',
        '2021' => 'Torneo Clausura 2021 / Apertura 2021',
    ];

    // ESPN league slugs — Argentina
    public array $argLeagues = [
        'argentina'        => ['slug' => 'arg.1',    'name' => 'Liga Profesional',  'flag' => '🇦🇷', 'has_standings' => true],
        'copa_argentina'   => ['slug' => 'arg.copa', 'name' => 'Copa Argentina',    'flag' => '🏆',  'has_standings' => false],
        'primera_nacional' => ['slug' => 'arg.2',    'name' => 'Primera Nacional',  'flag' => '🥈',  'has_standings' => true],
        'copa_libertadores'=> ['slug' => 'conmebol.libertadores', 'name' => 'Copa Libertadores', 'flag' => '🌎', 'has_standings' => true],
        'copa_sudamericana'=> ['slug' => 'conmebol.sudamericana', 'name' => 'Copa Sudamericana', 'flag' => '🌍', 'has_standings' => true],
    ];

    // Ligas europeas y mundiales
    public array $leagues = [
        'argentina'   => ['slug' => 'arg.1',  'name' => 'Liga Profesional', 'flag' => '🇦🇷'],
        'premier'     => ['slug' => 'eng.1',  'name' => 'Premier League',   'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿'],
        'laliga'      => ['slug' => 'esp.1',  'name' => 'La Liga',          'flag' => '🇪🇸'],
        'seriea'      => ['slug' => 'ita.1',  'name' => 'Serie A',          'flag' => '🇮🇹'],
        'bundesliga'  => ['slug' => 'ger.1',  'name' => 'Bundesliga',       'flag' => '🇩🇪'],
        'ligue1'      => ['slug' => 'fra.1',  'name' => 'Ligue 1',          'flag' => '🇫🇷'],
    ];

    // Mundial 2026
    public string $mundialSlug = 'fifa.world';

    // ─── HTTP helper ──────────────────────────────────────────────────────────
    private function get(string $url): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 15,
                'ignore_errors' => true,
                'user_agent' => 'DesdeLaLinea/2.0',
            ],
            'ssl' => ['verify_peer' => false],
        ]);
        $resp = @file_get_contents($url, false, $ctx);
        if (!$resp) return null;
        $data = json_decode($resp, true);
        return is_array($data) ? $data : null;
    }

    // ─── Cache helper ─────────────────────────────────────────────────────────
    private function cached(string $key, callable $fn, int $ttl = 300): mixed
    {
        $dir  = sys_get_temp_dir() . '/desdelalinea';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $file = $dir . '/' . md5($key) . '.cache';
        if (file_exists($file) && (time() - filemtime($file)) < $ttl) {
            $data = @unserialize(file_get_contents($file));
            if ($data !== false) return $data;
        }
        $result = $fn();
        @file_put_contents($file, serialize($result));
        return $result;
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
    public function getStandings(string $slug): array
    {
        return $this->cached("standings_{$slug}", function() use ($slug) {
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
                        'rank'   => (int)$this->stat($stats, 'rank'),
                        'name'   => $team['displayName'] ?? '—',
                        'logo'   => $logo,
                        'played' => (int)$this->stat($stats, 'gamesPlayed'),
                        'wins'   => (int)$this->stat($stats, 'wins'),
                        'draws'  => (int)$this->stat($stats, 'ties'),
                        'losses' => (int)$this->stat($stats, 'losses'),
                        'gf'     => (int)$this->stat($stats, 'pointsFor'),
                        'gc'     => (int)$this->stat($stats, 'pointsAgainst'),
                        'gd'     => (int)$this->stat($stats, 'pointDifferential'),
                        'points' => (int)$this->stat($stats, 'points'),
                        'form'   => $this->stat($stats, 'streak') ?? '',
                        'group'  => $groupName,
                    ];
                }
            }

            usort($rows, fn($a, $b) => $b['points'] <=> $a['points'] ?: $b['gd'] <=> $a['gd']);
            foreach ($rows as $i => &$r) $r['rank'] = $i + 1;
            return $rows;
        }, 600);
    }

    // ─── Scoreboard ───────────────────────────────────────────────────────────
    public function getScoreboard(string $slug, string $dates = ''): array
    {
        $url = "{$this->base}/{$slug}/scoreboard";
        if ($dates) $url .= "?dates={$dates}";
        $data = $this->get($url);
        return $data['events'] ?? [];
    }

    // ─── Partidos terminados (últimas 8 semanas) ──────────────────────────────
    public function getMatches(string $slug, int $count = 10): array
    {
        return $this->cached("matches_{$slug}_{$count}", function() use ($slug, $count) {
            $finished = [];
            for ($weeks = 1; $weeks <= 8 && count($finished) < $count; $weeks++) {
                $from   = date('Ymd', strtotime("-{$weeks} weeks"));
                $to     = date('Ymd', strtotime('-' . ($weeks - 1) . ' weeks'));
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
        }, 300);
    }

    // ─── Resultados históricos por año/mes ────────────────────────────────────
    public function getHistoricalMatches(string $slug, int $year, ?int $month = null, int $count = 30): array
    {
        $cacheKey = "hist_{$slug}_{$year}_{$month}_{$count}";
        return $this->cached($cacheKey, function() use ($slug, $year, $month, $count) {
            $finished = [];

            if ($month) {
                // Busca un mes específico
                $from = date('Ymd', mktime(0, 0, 0, $month, 1, $year));
                $to   = date('Ymd', mktime(0, 0, 0, $month + 1, 0, $year));
                $events = $this->getScoreboard($slug, "{$from}-{$to}");
                foreach ($events as $ev) {
                    $comp = $ev['competitions'][0] ?? [];
                    if (($comp['status']['type']['completed'] ?? false) === true) {
                        $finished[] = $this->normalizeEvent($ev);
                    }
                }
            } else {
                // Busca todo el año por trimestres
                $quarters = [
                    ["{$year}0101", "{$year}0401"],
                    ["{$year}0401", "{$year}0701"],
                    ["{$year}0701", "{$year}1001"],
                    ["{$year}1001", ($year+1) . "0101"],
                ];
                foreach ($quarters as [$from, $to]) {
                    if (count($finished) >= $count) break;
                    $events = $this->getScoreboard($slug, "{$from}-{$to}");
                    foreach ($events as $ev) {
                        $comp = $ev['competitions'][0] ?? [];
                        if (($comp['status']['type']['completed'] ?? false) === true) {
                            $finished[] = $this->normalizeEvent($ev);
                        }
                    }
                }
            }

            // Más recientes primero
            usort($finished, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));
            return array_slice($finished, 0, $count);
        }, 3600); // Cache 1 hora para históricos
    }

    // ─── Próximos partidos ────────────────────────────────────────────────────
    public function getNextMatches(string $slug, int $count = 10): array
    {
        return $this->cached("upcoming_{$slug}_{$count}", function() use ($slug, $count) {
            $upcoming = [];
            for ($weeks = 0; $weeks < 6 && count($upcoming) < $count; $weeks++) {
                $from   = date('Ymd', strtotime("+{$weeks} weeks"));
                $to     = date('Ymd', strtotime('+' . ($weeks + 1) . ' weeks'));
                $events = $this->getScoreboard($slug, "{$from}-{$to}");
                foreach ($events as $ev) {
                    $comp  = $ev['competitions'][0] ?? [];
                    $state = $comp['status']['type']['state'] ?? '';
                    if ($state === 'pre') {
                        $upcoming[] = $this->normalizeEvent($ev);
                        if (count($upcoming) >= $count) break;
                    }
                }
            }
            return $upcoming;
        }, 300);
    }

    // ─── Partidos en vivo ─────────────────────────────────────────────────────
    public function getLiveMatches(string $slug): array
    {
        // No cache para live
        $events = $this->getScoreboard($slug);
        $live = [];
        foreach ($events as $ev) {
            $comp  = $ev['competitions'][0] ?? [];
            $state = $comp['status']['type']['state'] ?? '';
            if ($state === 'in') $live[] = $this->normalizeEvent($ev);
        }
        return $live;
    }

    // ─── TODOS los partidos en vivo (todas las ligas argentinas) ──────────────
    public function getAllLiveMatches(): array
    {
        $live = [];
        foreach ($this->argLeagues as $key => $lg) {
            $matches = $this->getLiveMatches($lg['slug']);
            foreach ($matches as &$m) {
                $m['league_name'] = $lg['name'];
                $m['league_flag'] = $lg['flag'];
                $m['league_key']  = $key;
            }
            $live = array_merge($live, $matches);
        }
        return $live;
    }

    // ─── Normalize event ──────────────────────────────────────────────────────
    private function normalizeEvent(array $ev): array
    {
        $comp        = $ev['competitions'][0] ?? [];
        $competitors = $comp['competitors']   ?? [];
        $status      = $comp['status']        ?? [];
        $venue       = $comp['venue']         ?? [];

        $home = $away = null;
        foreach ($competitors as $c) {
            if ($c['homeAway'] === 'home') $home = $c;
            else                           $away = $c;
        }

        // Goles del partido
        $homeGoals = [];
        $awayGoals = [];
        foreach ($comp['details'] ?? [] as $detail) {
            $type      = $detail['type']['text'] ?? '';
            $athleteId = $detail['athletesInvolved'][0]['id'] ?? '';
            $name      = $detail['athletesInvolved'][0]['displayName'] ?? '';
            $clock     = $detail['clock']['displayValue'] ?? '';
            $teamId    = $detail['team']['id'] ?? '';
            if (in_array($type, ['Goal', 'Penalty', 'Own Goal']) ) {
                $entry = ['name' => $name, 'clock' => $clock, 'type' => $type];
                if ($teamId === ($home['team']['id'] ?? '')) $homeGoals[] = $entry;
                else $awayGoals[] = $entry;
            }
        }

        return [
            'id'        => $ev['id']  ?? '',
            'date'      => $ev['date'] ?? '',
            'name'      => $ev['name'] ?? '',
            'state'     => $status['type']['state']     ?? 'pre',
            'completed' => $status['type']['completed'] ?? false,
            'detail'    => $status['type']['detail']    ?? '',
            'clock'     => $status['displayClock']      ?? '',
            'period'    => $status['period']            ?? 0,
            'venue'     => $venue['fullName']           ?? ($ev['venue']['displayName'] ?? ''),
            'venue_city'=> $venue['address']['city']    ?? '',
            'round'     => $ev['season']['slug']        ?? '',
            'broadcast' => $comp['broadcasts'][0]['names'][0] ?? '',
            'home'      => [
                'id'       => $home['team']['id']          ?? '',
                'name'     => $home['team']['displayName'] ?? '?',
                'abbr'     => $home['team']['abbreviation'] ?? '',
                'logo'     => $home['team']['logo']        ?? null,
                'score'    => $home['score']               ?? null,
                'winner'   => $home['winner']              ?? false,
                'form'     => $home['form']                ?? '',
                'goals'    => $homeGoals,
            ],
            'away'      => [
                'id'       => $away['team']['id']          ?? '',
                'name'     => $away['team']['displayName'] ?? '?',
                'abbr'     => $away['team']['abbreviation'] ?? '',
                'logo'     => $away['team']['logo']        ?? null,
                'score'    => $away['score']               ?? null,
                'winner'   => $away['winner']              ?? false,
                'form'     => $away['form']                ?? '',
                'goals'    => $awayGoals,
            ],
        ];
    }

    // ─── Top Scorers / Asistencias ────────────────────────────────────────────
    public function getTopScorers(string $slug): array
    {
        return $this->cached("scorers_{$slug}", function() use ($slug) {
            $data = $this->get("{$this->base}/{$slug}/leaders");
            if (!$data) return [];
            foreach ($data['categories'] ?? [] as $cat) {
                if (stripos($cat['name'] ?? '', 'goal') !== false || ($cat['abbreviation'] ?? '') === 'G') {
                    return array_slice($cat['leaders'] ?? [], 0, 10);
                }
            }
            return array_slice($data['categories'][0]['leaders'] ?? [], 0, 10);
        }, 1800);
    }

    public function getTopAssists(string $slug): array
    {
        return $this->cached("assists_{$slug}", function() use ($slug) {
            $data = $this->get("{$this->base}/{$slug}/leaders");
            if (!$data) return [];
            foreach ($data['categories'] ?? [] as $cat) {
                if (stripos($cat['name'] ?? '', 'assist') !== false || ($cat['abbreviation'] ?? '') === 'A') {
                    return array_slice($cat['leaders'] ?? [], 0, 10);
                }
            }
            return array_slice($data['categories'][1]['leaders'] ?? [], 0, 10);
        }, 1800);
    }

    // ─── Mundial 2026 via ESPN ─────────────────────────────────────────────────
    public function getMundialLive(): array
    {
        return $this->getLiveMatches($this->mundialSlug);
    }

    public function getMundialMatches(int $count = 20): array
    {
        return $this->getMatches($this->mundialSlug, $count);
    }

    public function getMundialUpcoming(int $count = 20): array
    {
        return $this->getNextMatches($this->mundialSlug, $count);
    }

    public function getMundialStandings(): array
    {
        return $this->getStandings($this->mundialSlug);
    }

    public function getMundialTopScorers(): array
    {
        return $this->getTopScorers($this->mundialSlug);
    }

    // ─── Noticias ESPN (RSS) ──────────────────────────────────────────────────
    public function getEspnNews(string $category = 'argentina', int $limit = 10): array
    {
        $urls = [
            'argentina' => 'https://www.espn.com.ar/rss/futbol/news',
            'mundial'   => 'https://www.espn.com/espn/rss/soccer/news',
            'europa'    => 'https://www.espn.com/espn/rss/soccer/news',
        ];
        $url  = $urls[$category] ?? $urls['argentina'];
        $cKey = "espn_rss_{$category}";

        return $this->cached($cKey, function() use ($url, $limit) {
            $ctx = stream_context_create([
                'http' => ['timeout' => 10, 'user_agent' => 'DesdeLaLinea/2.0'],
                'ssl'  => ['verify_peer' => false],
            ]);
            $xml = @file_get_contents($url, false, $ctx);
            if (!$xml) return [];

            $items  = [];
            $feed   = @simplexml_load_string($xml);
            if (!$feed) return [];

            foreach ($feed->channel->item ?? [] as $item) {
                if (count($items) >= $limit) break;
                $items[] = [
                    'title'   => (string)$item->title,
                    'link'    => (string)$item->link,
                    'date'    => (string)$item->pubDate,
                    'summary' => strip_tags((string)$item->description),
                    'source'  => 'ESPN',
                ];
            }
            return $items;
        }, 600);
    }
}