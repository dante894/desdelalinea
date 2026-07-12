<?php

namespace App\Services;

use App\Core\Database;

/**
 * Scraper de noticias deportivas via RSS.
<<<<<<< HEAD
 *
 * Fuentes: SOLO diarios y medios argentinos (nacionales y de cada provincia).
 * Cada noticia se clasifica automáticamente en 2 categorías únicas:
 *   - "Mundial 2026"  → si el título/copete menciona el Mundial / la Selección en el Mundial
 *   - "Argentina"     → el resto de las noticias de fútbol argentino (clubes, AFA, torneos locales)
 *
 * Nota: los diarios de provincia suelen cambiar la URL de su feed RSS con el tiempo.
 * Si alguna fuente empieza a fallar, revisá el log en /admin (botón "Ejecutar Scraper Ahora")
 * y actualizá esa URL puntual; el resto de las fuentes sigue funcionando igual (cada una
 * corre en su propio try/catch).
=======
 * Fuentes: Infobae Deportes, TyC Sports, Ole, ESPN Argentina, Marca.
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
 */
class Scraper
{
    private array $sources = [
<<<<<<< HEAD
        // ── MEDIOS NACIONALES (Buenos Aires) ─────────────────────────────
        [
            'name' => 'Olé',
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
            'name' => 'Clarín Deportes',
            'url'  => 'https://www.clarin.com/rss/deportes/',
            'lang' => 'es',
        ],
        [
            'name' => 'La Nación Deportes',
            'url'  => 'https://www.lanacion.com.ar/arc/outboundfeeds/rss/category/deportes/?outputType=xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Página/12 Deportes',
            'url'  => 'https://www.pagina12.com.ar/rss/secciones/deportes/notas',
            'lang' => 'es',
        ],
        [
            'name' => 'Ámbito Deportes',
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
        // ── DIARIOS DE PROVINCIA (federalizamos las fuentes) ─────────────
        [
            'name' => 'La Voz del Interior (Córdoba)',
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
            'name' => 'La Gaceta (Tucumán)',
            'url'  => 'https://www.lagaceta.com.ar/rss/deportes.xml',
            'lang' => 'es',
        ],
        [
            'name' => 'Río Negro',
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
     * Si el título/copete matchea alguna, la noticia se guarda con category = 'Mundial 2026'.
     * Si no matchea ninguna, se guarda como 'Argentina' (fútbol local/Selección en general).
     */
    private array $mundialWords = [
        'mundial 2026','mundial de futbol','mundial de fútbol','copa del mundo',
        'world cup','fifa world cup','mundial de la fifa',
        'eliminatorias sudamericanas','eliminatorias al mundial','repechaje mundial',
        'sorteo del mundial','sede mundialista','estadio mundialista',
=======
        // ── ARGENTINA & SUDAMÉRICA ──────────────────────────────────────
        [
            'name'     => 'Marca Argentina',
            'url'      => 'https://e00-marca.uecdn.es/rss/futbol/futbol-internacional.xml',
            'category' => 'Argentina',
            'lang'     => 'es',
            'keywords' => ['argentina','boca','river','racing','independiente','san lorenzo','belgrano','estudiantes','velez','huracan','newells','central','lanus','defensa','arsenal','talleres','atletico tucuman','gimnasia','platense'],
        ],
        [
            'name'     => 'Mundo Deportivo Internacional',
            'url'      => 'https://www.mundodeportivo.com/rss/futbol/america-del-sur.xml',
            'category' => 'Argentina',
            'lang'     => 'es',
            'keywords' => ['argentina','sudamerica','america del sur','libertadores','sudamericana','conmebol'],
        ],
        [
            'name'     => 'BBC Fútbol Sudamérica',
            'url'      => 'https://feeds.bbci.co.uk/sport/football/rss.xml',
            'category' => 'Argentina',
            'lang'     => 'en',
            'keywords' => ['argentina','south america','copa libertadores','sudamericana','boca','river','messi'],
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
        [
            'name'     => 'Marca Internacional',
            'url'      => 'https://e00-marca.uecdn.es/rss/futbol/futbol-internacional.xml',
            'category' => 'Internacional',
            'lang'     => 'es',
        ],
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
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

<<<<<<< HEAD
=======
                    // ── Filtro por keywords de fuente (ej: feeds generales usados para Argentina) ──
                    $srcKeywords = $src['keywords'] ?? [];
                    if (!empty($srcKeywords)) {
                        $haystack = mb_strtolower($item['title'] . ' ' . $item['description']);
                        $match = false;
                        foreach ($srcKeywords as $kw) {
                            if (str_contains($haystack, mb_strtolower($kw))) { $match = true; break; }
                        }
                        if (!$match) continue;
                    }

>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
                    // ── Filtro: solo noticias de fútbol ──────────────────
                    if (!$this->isFootballNews($item['title'] . ' ' . $item['description'])) {
                        continue;
                    }

<<<<<<< HEAD
                    // Traducir si la fuente está en inglés (por si se agrega alguna a futuro)
=======
                    $stmt = $db->prepare("
                        INSERT INTO news (title, slug, summary, image_url, source_url, source_name, category, published_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    // Traducir si la fuente está en inglés
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
                    $title   = $item['title'];
                    $summary = $item['description'];
                    if (($src['lang'] ?? 'es') === 'en') {
                        $title   = $this->translate($title);
                        $summary = $this->translate($summary);
                    }

<<<<<<< HEAD
                    // ── Clasificación: Mundial 2026 vs Argentina (local) ──
                    $category = $this->isMundialNews($title . ' ' . $summary)
                        ? 'Mundial 2026'
                        : 'Argentina';

                    $stmt = $db->prepare("
                        INSERT INTO news (title, slug, summary, image_url, source_url, source_name, category, published_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
=======
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
                    $stmt->execute([
                        $title,
                        $slug,
                        $summary,
                        $item['image'] ?? null,
                        $item['link'],
                        $src['name'],
<<<<<<< HEAD
                        $category,
=======
                        $src['category'],
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
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

<<<<<<< HEAD
    /**
     * Devuelve true si el texto habla del Mundial 2026 / Copa del Mundo.
     */
    private function isMundialNews(string $text): bool
    {
        $text = mb_strtolower($text);
        foreach ($this->mundialWords as $word) {
            if (str_contains($text, mb_strtolower($word))) return true;
        }
        return false;
    }

=======
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
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