<?php

namespace App\Services;

use App\Core\Database;

/**
 * Scraper de noticias deportivas via RSS.
 * Fuentes: Infobae Deportes, TyC Sports, Ole, ESPN Argentina, Marca.
 */
class Scraper
{
    private array $sources = [
        // ── ARGENTINA ──────────────────────────────────────────────────
        [
            'name'     => 'Olé',
            'url'      => 'https://www.ole.com.ar/rss/futbol.xml',
            'category' => 'Argentina',
            'lang'     => 'es',
        ],
        [
            'name'     => 'TyC Sports',
            'url'      => 'https://www.tycsports.com/rss.xml',
            'category' => 'Argentina',
            'lang'     => 'es',
        ],
        [
            'name'     => 'Infobae Fútbol',
            'url'      => 'https://www.infobae.com/feeds/rss/deportes/futbol-argentino/',
            'category' => 'Argentina',
            'lang'     => 'es',
        ],
        [
            'name'     => 'ESPN Argentina',
            'url'      => 'https://www.espn.com.ar/espn/rss/futbol/news',
            'category' => 'Argentina',
            'lang'     => 'es',
        ],
        // ── EUROPA ─────────────────────────────────────────────────────
        [
            'name'     => 'Marca',
            'url'      => 'https://e00-marca.uecdn.es/rss/futbol/primera-division.xml',
            'category' => 'Europa',
            'lang'     => 'es',
        ],
        [
            'name'     => 'Marca Champions',
            'url'      => 'https://e00-marca.uecdn.es/rss/futbol/champions-league.xml',
            'category' => 'Europa',
            'lang'     => 'es',
        ],
        [
            'name'     => 'AS Fútbol',
            'url'      => 'https://rss.as.com/rss/futbol.xml',
            'category' => 'Europa',
            'lang'     => 'es',
        ],
        [
            'name'     => 'Mundo Deportivo',
            'url'      => 'https://www.mundodeportivo.com/rss/futbol.xml',
            'category' => 'Europa',
            'lang'     => 'es',
        ],
        [
            'name'     => 'BBC Fútbol',
            'url'      => 'https://feeds.bbci.co.uk/sport/football/rss.xml',
            'category' => 'Europa',
            'lang'     => 'en',
        ],
        [
            'name'     => 'Sky Sports Fútbol',
            'url'      => 'https://www.skysports.com/rss/12040',
            'category' => 'Europa',
            'lang'     => 'en',
        ],
        // ── FICHAJES & MERCADO ──────────────────────────────────────────
        [
            'name'     => 'Transfermarkt',
            'url'      => 'https://www.transfermarkt.es/rss/news',
            'category' => 'Fichajes',
            'lang'     => 'es',
        ],
        // ── INTERNACIONAL / SELECCIONES ─────────────────────────────────
        [
            'name'     => 'ESPN Fútbol Internacional',
            'url'      => 'https://www.espn.com/espn/rss/soccer/news',
            'category' => 'Internacional',
            'lang'     => 'en',
        ],
    ];

    private int $maxPerSource = 20;
    private array $log = [];

    public function run(): array
    {
        $db      = Database::getInstance();
        $total   = 0;
        $new     = 0;
        $errors  = [];

        foreach ($this->sources as $src) {
            try {
                $items = $this->fetchRSS($src['url']);
                $this->log[] = "✅ {$src['name']}: " . count($items) . " ítems";

                foreach ($items as $item) {
                    $total++;
                    $slug = $this->slugify($item['title']);

                    // Evitar duplicados por slug o source_url
                    $check = $db->prepare("SELECT id FROM news WHERE slug = ? OR source_url = ? LIMIT 1");
                    $check->execute([$slug, $item['link']]);
                    if ($check->fetch()) continue;

                    // ── Filtro: solo noticias de fútbol ──────────────────
                    if (!$this->isFootballNews($item['title'] . ' ' . $item['description'])) {
                        continue;
                    }

                    $stmt = $db->prepare("
                        INSERT INTO news (title, slug, summary, image_url, source_url, source_name, category, published_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    // Traducir si la fuente está en inglés
                    $title   = $item['title'];
                    $summary = $item['description'];
                    if (($src['lang'] ?? 'es') === 'en') {
                        $title   = $this->translate($title);
                        $summary = $this->translate($summary);
                    }

                    $stmt->execute([
                        $title,
                        $slug,
                        $summary,
                        $item['image'] ?? null,
                        $item['link'],
                        $src['name'],
                        $src['category'],
                        $item['pubDate'] ?? date('Y-m-d H:i:s'),
                    ]);
                    $new++;
                }
            } catch (\Throwable $e) {
                $errors[] = "{$src['name']}: " . $e->getMessage();
                $this->log[] = "❌ {$src['name']}: " . $e->getMessage();
            }
        }

        return [
            'total'   => $total,
            'new'     => $new,
            'errors'  => $errors,
            'log'     => $this->log,
        ];
    }

    private function fetchRSS(string $url): array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout'     => 12,
                'user_agent'  => 'Mozilla/5.0 (compatible; DesdeLaLineaBot/1.0)',
                'follow_location' => true,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        $xml = @file_get_contents($url, false, $ctx);
        if ($xml === false) {
            throw new \RuntimeException("No se pudo obtener el feed: {$url}");
        }

        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml);
        if ($feed === false) {
            throw new \RuntimeException("XML inválido en: {$url}");
        }

        $items   = [];
        $channel = $feed->channel ?? $feed;
        $entries = $channel->item ?? ($feed->entry ?? []);

        $count = 0;
        foreach ($entries as $entry) {
            if ($count >= $this->maxPerSource) break;

            $ns    = $entry->getNamespaces(true);
            $media = isset($ns['media']) ? $entry->children($ns['media']) : null;

            $title       = trim((string)($entry->title ?? ''));
            $link        = trim((string)($entry->link ?? ($entry->guid ?? '')));
            $description = trim(strip_tags((string)($entry->description ?? ($entry->summary ?? ''))));
            $pubDate     = $this->parseDate((string)($entry->pubDate ?? ($entry->updated ?? '')));

            // Imagen: media:content o enclosure
            $image = null;
            if ($media && isset($media->content)) {
                $image = (string)$media->content->attributes()['url'] ?? null;
            }
            if (!$image && isset($entry->enclosure)) {
                $image = (string)$entry->enclosure->attributes()['url'] ?? null;
            }
            // Intentar sacar imagen del HTML de description
            if (!$image) {
                preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', (string)($entry->description ?? ''), $m);
                $image = $m[1] ?? null;
            }

            if (empty($title) || empty($link)) continue;

            $items[] = [
                'title'       => $title,
                'link'        => $link,
                'description' => mb_substr($description, 0, 500),
                'image'       => $image,
                'pubDate'     => $pubDate,
            ];
            $count++;
        }

        return $items;
    }

    private function parseDate(string $raw): string
    {
        if (empty($raw)) return date('Y-m-d H:i:s');
        try {
            return (new \DateTime($raw))->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return date('Y-m-d H:i:s');
        }
    }

    private function translate(string $text): string
    {
        if (empty(trim($text))) return $text;
        try {
            $url  = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=es&dt=t&q=' . rawurlencode($text);
            $ctx  = stream_context_create(['http' => ['timeout' => 5, 'user_agent' => 'Mozilla/5.0'], 'ssl' => ['verify_peer' => false]]);
            $resp = @file_get_contents($url, false, $ctx);
            if (!$resp) return $text;
            $data = json_decode($resp, true);
            if (!isset($data[0])) return $text;
            $translated = '';
            foreach ($data[0] as $part) {
                $translated .= $part[0] ?? '';
            }
            return $translated ?: $text;
        } catch (\Throwable) {
            return $text;
        }
    }

    /**
     * Devuelve true si el texto contiene términos de fútbol.
     * Descarta noticias de tenis, básquet, F1, boxeo, etc.
     */
    private function isFootballNews(string $text): bool
    {
        $text = mb_strtolower($text);

        // Palabras que confirman que ES fútbol
        $footballWords = [
            'fútbol','futbol','football','soccer',
            'gol','golazo','penalti','penal','penalty','offside','fuera de juego',
            'liga','premier','champions','bundesliga','serie a','ligue 1','mls',
            'copa','torneo','superliga','libertadores','sudamericana',
            'partido','match','clásico','clasico','derbi','derby',
            'entrenador','técnico','manager','coach','plantilla','squad',
            'transferencia','fichaje','traspaso','mercado','contrato',
            'delantero','defensor','mediocampista','portero','arquero',
            'forward','midfielder','defender','goalkeeper','keeper',
            'selección','seleccion','mundial','eurocopa','euro ','copa del rey',
            'amistoso','friendly',
            // Ligas específicas
            'laliga','la liga','premier league','serie a','bundesliga',
            // Clubes populares (cubren muchos casos)
            'boca','river','racing','independiente','san lorenzo','huracán',
            'barcelona','real madrid','atletico','atletico de madrid','atlético',
            'manchester','arsenal','chelsea','liverpool','city','united',
            'juventus','inter','milan','napoli','roma',
            'psg','paris saint','marseille',
            'bayern','dortmund','leverkusen',
        ];

        // Palabras que descartan (otras disciplinas)
        $excludeWords = [
            'nba','nfl','nhl','mlb','wnba','ncaa',
            'tenis','tennis','wimbledon','roland garros','us open','australian open',
            'formula 1','formula1','f1','gran prix','motogp','nascar',
            'boxeo','boxing','ufc','mma','wbc','wba',
            'baloncesto','basketball','basquetbol',
            'rugby','golf','cricket','béisbol','baseball','softball',
            'atletismo','natación','natacion','ciclismo',
            'wrestling','wwe',
        ];

        foreach ($excludeWords as $word) {
            if (str_contains($text, $word)) return false;
        }

        foreach ($footballWords as $word) {
            if (str_contains($text, $word)) return true;
        }

        // Si no hay ninguna pista, descartar
        return false;
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = strtr($text, [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            'Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u','Ü'=>'u','Ñ'=>'n',
        ]);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        $slug = mb_substr($text, 0, 180);
        // append random suffix to avoid collisions
        return $slug . '-' . substr(md5($text . microtime()), 0, 6);
    }
}