<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mundial 2026 — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--gold:#ffd600;--dark:#0a0f0a;--dark2:#111711;--dark3:#1a231a;--card:#141c14;--border:#1e2e1e;--text:#e8f5e8;--muted:#6a8f6a}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.95);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--green)}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}

/* HERO MUNDIAL */
.mundial-hero{background:linear-gradient(135deg,#0a1f0a,#1a3a0a,#0d2a0d);border-bottom:2px solid var(--gold);padding:2.5rem 0}
.mundial-hero h1{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2.5rem,5vw,4rem);font-weight:900;color:var(--gold);text-transform:uppercase;letter-spacing:2px}
.mundial-hero p{color:var(--muted);margin-top:.5rem;font-size:1rem}
.live-badge{display:inline-flex;align-items:center;gap:.4rem;background:#e53935;color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;padding:.3rem .7rem;border-radius:3px;margin-left:1rem;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.6}}

/* TABS */
.tabs{display:flex;gap:0;border-bottom:2px solid var(--border);margin:2rem 0 0}
.tab{padding:.75rem 1.5rem;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.95rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;text-decoration:none}
.tab:hover{color:var(--text)}
.tab.active{color:var(--gold);border-bottom-color:var(--gold)}
.tab-content{display:none;padding:1.5rem 0}
.tab-content.active{display:block}

/* MATCHES */
.matches-grid{display:grid;gap:.75rem}
.match-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1rem 1.5rem;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:1rem}
.match-card.live{border-color:#e53935;box-shadow:0 0 12px rgba(229,57,53,.2)}
.match-team{display:flex;align-items:center;gap:.75rem;font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:700}
.match-team.away{flex-direction:row-reverse;text-align:right}
.team-flag{font-size:1.5rem}
.match-score{text-align:center}
.score-nums{font-family:'Barlow Condensed',sans-serif;font-size:2rem;font-weight:900;color:var(--gold);letter-spacing:2px}
.score-dash{font-family:'Barlow Condensed',sans-serif;font-size:1.5rem;font-weight:700;color:var(--muted)}
.match-info{font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:.25rem}
.match-date{font-size:.75rem;color:var(--muted);text-align:center}
.status-live{color:#e53935;font-weight:700;font-size:.75rem;text-transform:uppercase;animation:pulse 1.5s infinite}
.status-fin{color:var(--muted);font-size:.75rem}
.status-prox{color:var(--green);font-size:.75rem}

/* STANDINGS */
.standings-group{margin-bottom:2rem}
.group-title{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--gold);border-left:3px solid var(--gold);padding-left:.6rem;margin-bottom:.75rem}
.standings-table{width:100%;border-collapse:collapse;font-size:.85rem}
.standings-table th{background:var(--dark3);padding:.6rem .75rem;text-align:left;font-family:'Barlow Condensed',sans-serif;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);white-space:nowrap}
.standings-table th:not(:first-child){text-align:center}
.standings-table td{padding:.6rem .75rem;border-bottom:1px solid var(--border);color:var(--text)}
.standings-table td:not(:first-child){text-align:center}
.standings-table tr:last-child td{border-bottom:none}
.standings-table tr:hover td{background:rgba(0,200,83,.04)}
.pos-qualify{color:var(--green);font-weight:700}
.team-name-row{display:flex;align-items:center;gap:.5rem}

/* NEWS */
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.news-card:hover{transform:translateY(-3px);border-color:var(--gold)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column}
.card-img{height:150px;background:var(--dark3);overflow:hidden}
.card-img img{width:100%;height:100%;object-fit:cover}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:.9rem;flex:1;display:flex;flex-direction:column;gap:.4rem}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;line-height:1.3;flex:1}
.card-meta{font-size:.72rem;color:var(--muted)}
.section-title{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--gold);border-left:3px solid var(--gold);padding-left:.75rem;margin-bottom:1.25rem}
.empty{text-align:center;padding:3rem;color:var(--muted)}
footer{background:var(--dark2);border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem;margin-top:3rem}
footer strong{color:var(--green)}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina">🇦🇷 Argentina</a></li>
    <li><a href="/mundial" class="active">🏆 Mundial</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" style="background:var(--green);color:var(--dark);padding:.3rem .8rem;border-radius:4px">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<div class="mundial-hero">
  <div class="container">
    <h1>🏆 Mundial 2026
      <?php if (!empty($live)): ?>
      <span class="live-badge">● EN VIVO</span>
      <?php endif; ?>
    </h1>
    <p>Resultados, tabla de posiciones y noticias del Mundial USA · México · Canadá</p>
  </div>
</div>

<div class="container">
  <div class="tabs">
    <a href="#resultados" class="tab active" onclick="showTab('resultados',this)">Resultados</a>
    <a href="#proximos"   class="tab"        onclick="showTab('proximos',this)">Próximos</a>
    <a href="#tabla"      class="tab"        onclick="showTab('tabla',this)">Tabla</a>
    <a href="#noticias"   class="tab"        onclick="showTab('noticias',this)">Noticias</a>
  </div>

  <!-- RESULTADOS -->
  <div id="tab-resultados" class="tab-content active">
    <?php if (!empty($live)): ?>
    <p class="section-title" style="margin-bottom:1rem">⚡ En vivo</p>
    <div class="matches-grid" style="margin-bottom:2rem">
      <?php foreach ($live as $m): ?>
      <div class="match-card live">
        <div class="match-team">
          <span class="team-flag"><?= getFlagEmoji($m['homeTeam']['tla'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['homeTeam']['name'] ?? '—') ?></span>
        </div>
        <div class="match-score">
          <div class="score-nums"><?= $m['score']['fullTime']['home'] ?? '0' ?> — <?= $m['score']['fullTime']['away'] ?? '0' ?></div>
          <div class="status-live">● En vivo</div>
        </div>
        <div class="match-team away">
          <span><?= htmlspecialchars($m['awayTeam']['name'] ?? '—') ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['awayTeam']['tla'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <p class="section-title">Últimos resultados</p>
    <?php if (!empty($recent)): ?>
    <div class="matches-grid">
      <?php foreach ($recent as $m): ?>
      <div class="match-card">
        <div class="match-team">
          <span class="team-flag"><?= getFlagEmoji($m['homeTeam']['tla'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['homeTeam']['name'] ?? '—') ?></span>
        </div>
        <div class="match-score">
          <div class="score-nums"><?= $m['score']['fullTime']['home'] ?? '—' ?> — <?= $m['score']['fullTime']['away'] ?? '—' ?></div>
          <div class="match-date"><?= date('d/m H:i', strtotime($m['utcDate'])) ?></div>
          <div class="status-fin">Finalizado</div>
        </div>
        <div class="match-team away">
          <span><?= htmlspecialchars($m['awayTeam']['name'] ?? '—') ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['awayTeam']['tla'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">No hay resultados disponibles aún</div>
    <?php endif; ?>
  </div>

  <!-- PRÓXIMOS -->
  <div id="tab-proximos" class="tab-content">
    <p class="section-title">Próximos partidos</p>
    <?php if (!empty($upcoming)): ?>
    <div class="matches-grid">
      <?php foreach ($upcoming as $m): ?>
      <div class="match-card">
        <div class="match-team">
          <span class="team-flag"><?= getFlagEmoji($m['homeTeam']['tla'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['homeTeam']['name'] ?? '—') ?></span>
        </div>
        <div class="match-score">
          <div class="score-dash">VS</div>
          <div class="match-date"><?= date('d/m H:i', strtotime($m['utcDate'])) ?></div>
          <div class="status-prox">Próximo</div>
        </div>
        <div class="match-team away">
          <span><?= htmlspecialchars($m['awayTeam']['name'] ?? '—') ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['awayTeam']['tla'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">No hay partidos programados</div>
    <?php endif; ?>
  </div>

  <!-- TABLA -->
  <div id="tab-tabla" class="tab-content">
    <p class="section-title">Tabla de posiciones</p>
    <?php if (!empty($standings)): ?>
      <?php foreach ($standings as $group): ?>
      <div class="standings-group">
        <div class="group-title"><?= htmlspecialchars($group['group'] ?? 'Grupo') ?></div>
        <table class="standings-table">
          <thead>
            <tr>
              <th>#</th><th>Equipo</th><th>PJ</th><th>G</th><th>E</th><th>P</th><th>GF</th><th>GC</th><th>DG</th><th>Pts</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($group['table'] as $row): ?>
            <tr>
              <td class="<?= $row['position'] <= 2 ? 'pos-qualify' : '' ?>"><?= $row['position'] ?></td>
              <td>
                <div class="team-name-row">
                  <?php if ($row['team']['crest'] ?? false): ?>
                  <img src="<?= htmlspecialchars($row['team']['crest']) ?>" width="18" height="18" alt="" style="object-fit:contain">
                  <?php endif; ?>
                  <?= htmlspecialchars($row['team']['name']) ?>
                </div>
              </td>
              <td><?= $row['playedGames'] ?></td>
              <td><?= $row['won'] ?></td>
              <td><?= $row['draw'] ?></td>
              <td><?= $row['lost'] ?></td>
              <td><?= $row['goalsFor'] ?></td>
              <td><?= $row['goalsAgainst'] ?></td>
              <td><?= $row['goalDifference'] ?></td>
              <td style="font-weight:700;color:var(--gold)"><?= $row['points'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endforeach; ?>
      <p style="font-size:.75rem;color:var(--muted);margin-top:1rem">🟢 Clasifican a siguiente ronda</p>
    <?php else: ?>
    <div class="empty">La tabla de posiciones estará disponible cuando comience el torneo</div>
    <?php endif; ?>
  </div>

  <!-- NOTICIAS -->
  <div id="tab-noticias" class="tab-content">
    <p class="section-title">Noticias del Mundial</p>
    <?php if (!empty($news)): ?>
    <div class="news-grid">
      <?php foreach ($news as $n): ?>
      <article class="news-card">
        <a href="/noticia?id=<?= $n['id'] ?>">
          <div class="card-img">
            <?php if ($n['image_url']): ?>
              <img src="<?= htmlspecialchars($n['image_url']) ?>" alt="" loading="lazy">
            <?php else: ?>
              <div class="card-no-img">🏆</div>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($n['title']) ?></h2>
            <span class="card-meta"><?= htmlspecialchars($n['source_name'] ?? '') ?> · <?= date('d/m H:i', strtotime($n['scraped_at'])) ?></span>
          </div>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">No hay noticias del Mundial todavía.<br>Ejecutá el scraper para traer noticias.</div>
    <?php endif; ?>
  </div>
</div>

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?> · Datos: football-data.org</footer>

<script>
function showTab(name, el) {
    event.preventDefault();
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    el.classList.add('active');
}
</script>
</body>
</html>
<?php
function getFlagEmoji(string $tla): string {
    $flags = [
        'ARG'=>'🇦🇷','BRA'=>'🇧🇷','URU'=>'🇺🇾','COL'=>'🇨🇴','ECU'=>'🇪🇨','PER'=>'🇵🇪','CHI'=>'🇨🇱','VEN'=>'🇻🇪',
        'MEX'=>'🇲🇽','USA'=>'🇺🇸','CAN'=>'🇨🇦','CRC'=>'🇨🇷','PAN'=>'🇵🇦','HON'=>'🇭🇳','JAM'=>'🇯🇲',
        'ESP'=>'🇪🇸','FRA'=>'🇫🇷','ALE'=>'🇩🇪','GER'=>'🇩🇪','ING'=>'🏴󠁧󠁢󠁥󠁮󠁧󠁿','ENG'=>'🏴󠁧󠁢󠁥󠁮󠁧󠁿','POR'=>'🇵🇹',
        'ITA'=>'🇮🇹','HOL'=>'🇳🇱','NED'=>'🇳🇱','BEL'=>'🇧🇪','CRO'=>'🇭🇷','SUI'=>'🇨🇭','DIN'=>'🇩🇰',
        'SEN'=>'🇸🇳','MAR'=>'🇲🇦','CMR'=>'🇨🇲','GHA'=>'🇬🇭','NGR'=>'🇳🇬','CIV'=>'🇨🇮',
        'JPN'=>'🇯🇵','KOR'=>'🇰🇷','AUS'=>'🇦🇺','IRA'=>'🇮🇷','SAU'=>'🇸🇦','QAT'=>'🇶🇦',
    ];
    return $flags[strtoupper($tla)] ?? '🏳';
}
