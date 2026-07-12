<?php

namespace App\Services;

use App\Core\Database;

/**
 * Scraper de noticias deportivas via RSS.
 *
 * Fuentes: SOLO diarios y medios argentinos (nacionales y de cada provincia).
 * Cada noticia se clasifica automaticamente en 2 categorias unicas:
 *   - "Mundial 2026"  -> si el titulo/copete menciona el Mundial / la Seleccion en el Mundial
 *   - "Argentina"     -> el resto de las noticias de futbol argentino (clubes, AFA, torneos locales)
 *
 * Nota: los diarios de provincia suelen cambiar la URL de su feed RSS con el tiempo.
 * Si alguna fuente empieza a fallar, revisa el log en /admin (boton "Ejecutar Scraper Ahora")
 * y actualiza esa URL puntual; el resto de las fuentes sigue funcionando igual (cada una
 * corre en su propio try/catch).
 */
class Scraper
{
    private array $sources = [
        // MEDIOS NACIONALES (Buenos Aires)
        [
            'name' => 'Ole',
            'url'  => 'https://www.ole.com.ar/rss/ultimas-noticias/',
            'lang' => 'es',
        ],
        [
            'name' => 'TyC Sports',
            'url'  => 'https://www.tycsports.com/rss/portada.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Infobae Deportes',
            'url'  => 'https://www.infobae.com/arc/outboundfeeds/rss/category/deportes/?outputType=xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Clarin Deportes',
            'url'  => 'https://www.clarin.com/rss/deportes/',
            'lang' => 'es',
        ],
        [
            'name' => 'La Nacion Deportes',
            'url'  => 'https://www.lanacion.com.ar/arc/outboundfeeds/rss/category/deportes/?outputType=xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Pagina/12 Deportes',
            'url'  => 'https://www.pagina12.com.ar/rss/secciones/deportes/notas',
            'lang' => 'es',
        ],
        [
            'name' => 'Ambito Deportes',
            'url'  => 'https://www.ambito.com/contenidos/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Perfil Deportes',
            'url'  => 'https://www.perfil.com/feed/deportes',
            'lang' => 'es',
        ],
        [
            'name' => 'Diario Popular Deportes',
            'url'  => 'https://www.diariopopular.com.ar/rss/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'ESPN Argentina',
            'url'  => 'https://www.espn.com.ar/rss/futbol/noticias',
            'lang' => 'es',
        ],
        // DIARIOS DE PROVINCIA (federalizamos las fuentes)
        [
            'name' => 'La Voz del Interior (Cordoba)',
            'url'  => 'https://www.lavoz.com.ar/arc/outboundfeeds/rss/category/deportes/?outputType=xml',
            'lang' => 'es',
        ],
        [
            'name' => 'La Capital (Rosario)',
            'url'  => 'https://www.lacapital.com.ar/rss/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Los Andes (Mendoza)',
            'url'  => 'https://www.losandes.com.ar/rss/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'La Gaceta (Tucuman)',
            'url'  => 'https://www.lagaceta.com.ar/rss/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Rio Negro',
            'url'  => 'https://www.rionegro.com.ar/deportes/feed/',
            'lang' => 'es',
        ],
        [
            'name' => 'El Litoral (Santa Fe)',
            'url'  => 'https://www.ellitoral.com/rss/deportes.xml',
            'lang' => 'es',
        ],
    ];

    /**
     * Palabras que indican que la noticia es sobre el Mundial 2026 (o Mundiales en general).
     * Si el titulo/copete matchea alguna, la noticia se guarda con category = 'Mundial 2026'.
     * Si no matchea ninguna, se guarda como 'Argentina' (futbol local/Seleccion en general).
     */
    private array $mundialWords = [
        'mundial 2026','mundial de futbol','mundial de futbol','copa del mundo',
        'world cup','fifa world cup','mundial de la fifa',
        'eliminatorias sudamericanas','eliminatorias al mundial','repechaje mundial',
        'sorteo del mundial','sede mundialista','estadio mundialista',
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
                $this->log[] = "OK {$src['name']}: " . count($items) . " items";

                foreach ($items as $item) {
                    $total++;
                    $slug = $this->slugify($item['title']);

                    $check = $db->prepare("SELECT id FROM news WHERE slug = ? OR source_url = ? LIMIT 1");
                    $check->execute([$slug, $item['link']]);
                    if ($check->fetch()) continue;

                    if (!$this->isFootballNews($item['title'] . ' ' . $item['description'])) {
                        continue;
                    }

                    $title   = $item['title'];
                    $summary = $item['description'];
                    if (($src['lang'] ?? 'es') === 'en') {
                        $title   = $this->translate($title);
                        $summary = $this->translate($summary);
                    }

                    $category = $this->isMundialNews($title . ' ' . $summary)
                        ? 'Mundial 2026'
                        : 'Argentina';

                    $stmt = $db->prepare("
                        INSERT INTO news (title, slug, summary, image_url, source_url, source_name, category, published_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $title,
                        $slug,
                        $summary,
                        $item['image'] ?? null,
                        $item['link'],
                        $src['name'],
                        $category,
                        $item['pubDate'] ?? date('Y-m-d H:i:s'),
                    ]);
                    $new++;
                }
            } catch (\Throwable $e) {
                $errors[] = "{$src['name']}: " . $e->getMessage();
                $this->log[] = "ERROR {$src['name']}: " . $e->getMessage();
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
            throw new \RuntimeException("XML invalido en: {$url}");
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

            $image = null;
            if ($media && isset($media->content)) {
                $image = (string)$media->content->attributes()['url'] ?? null;
            }
            if (!$image && isset($entry->enclosure)) {
                $image = (string)$entry->enclosure->attributes()['url'] ?? null;
            }
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
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            return $text;
        }
    }

    private function isFootballNews(string $text): bool
    {
        $text = mb_strtolower($text);

        $footballWords = [
            'futbol','football','soccer',
            'gol','golazo','penalti','penal','penalty','offside','fuera de juego',
            'liga','premier','champions','bundesliga','serie a','ligue 1','mls',
            'copa','torneo','superliga','libertadores','sudamericana',
            'partido','match','clasico','derbi','derby',
            'entrenador','tecnico','manager','coach','plantilla','squad',
            'transferencia','fichaje','traspaso','mercado','contrato',
            'delantero','defensor','mediocampista','portero','arquero',
            'forward','midfielder','defender','goalkeeper','keeper',
            'seleccion','mundial','eurocopa','euro ','copa del rey',
            'amistoso','friendly',
            'laliga','la liga','premier league',
            'boca','river','racing','independiente','san lorenzo','huracan',
            'barcelona','real madrid','atletico','atletico de madrid',
            'manchester','arsenal','chelsea','liverpool','city','united',
            'juventus','inter','milan','napoli','roma',
            'psg','paris saint','marseille',
            'bayern','dortmund','leverkusen',
        ];

        $excludeWords = [
            'nba','nfl','nhl','mlb','wnba','ncaa',
            'tenis','tennis','wimbledon','roland garros','us open','australian open',
            'formula 1','formula1','f1','gran prix','motogp','nascar',
            'boxeo','boxing','ufc','mma','wbc','wba',
            'baloncesto','basketball','basquetbol',
            'rugby','golf','cricket','beisbol','baseball','softball',
            'atletismo','natacion','ciclismo',
            'wrestling','wwe',
        ];

        foreach ($excludeWords as $word) {
            if (str_contains($text, $word)) return false;
        }

        foreach ($footballWords as $word) {
            if (str_contains($text, $word)) return true;
        }

        return false;
    }

    private function isMundialNews(string $text): bool
    {
        $text = mb_strtolower($text);
        foreach ($this->mundialWords as $word) {
            if (str_contains($text, mb_strtolower($word))) return true;
        }
        return false;
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = strtr($text, [
            'a'=>'a','e'=>'e','i'=>'i','o'=>'o','u'=>'u','n'=>'n',
        ]);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        $slug = mb_substr($text, 0, 180);
        return $slug . '-' . substr(md5($text . microtime()), 0, 6);
    }
}
