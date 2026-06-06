<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Deportes Argentina — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--cel:#74b9ff;--cel2:#0984e3;--dark:#08101a;--dark2:#0d1829;--card:#0f1e30;--border:#1a3050;--text:#e8f0f8;--muted:#4a7a9f;--gold:#ffd600}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh}
<?php include __DIR__ . '/partials/nav.css.php'; ?>
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(8,16,26,.96);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:#00c853;text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--cel)}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
.hero{background:linear-gradient(135deg,#08101a,#0d1f3a,#081525);border-bottom:3px solid var(--cel);padding:2.5rem 0;position:relative;overflow:hidden}
.hero::after{content:'🇦🇷';position:absolute;right:4rem;top:50%;transform:translateY(-50%);font-size:9rem;opacity:.06;pointer-events:none}
.hero h1{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2.5rem,5vw,4rem);font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:2px}
.hero h1 span{color:var(--cel)}
.hero p{color:var(--muted);margin-top:.5rem}
/* LEAGUE SELECTOR */
.league-selector{display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1.2rem}
.league-btn{display:flex;align-items:center;gap:.4rem;padding:.45rem 1rem;border-radius:6px;border:1px solid var(--border);background:var(--card);color:var(--muted);text-decoration:none;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;transition:all .2s}
.league-btn:hover{border-color:var(--cel);color:var(--text)}
.league-btn.active{background:var(--cel2);border-color:var(--cel);color:#fff}
.league-btn .flag{font-size:1rem}
.tabs{display:flex;gap:0;border-bottom:2px solid var(--border);margin:0}
.tab{padding:.8rem 1.4rem;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;text-decoration:none}
.tab:hover{color:var(--text)}
.tab.active{color:var(--cel);border-bottom-color:var(--cel)}
.tab-content{display:none;padding:2rem 0}
.tab-content.active{display:block}

/* STANDINGS */
.standings-wrap{overflow-x:auto}
.standings-table{width:100%;border-collapse:collapse;font-size:.85rem;min-width:600px}
.standings-table th{background:var(--dark2);padding:.65rem .75rem;text-align:center;font-family:'Barlow Condensed',sans-serif;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
.standings-table th:first-child,.standings-table th:nth-child(2){text-align:left}
.standings-table td{padding:.65rem .75rem;border-bottom:1px solid var(--border);text-align:center}
.standings-table td:first-child,.standings-table td:nth-child(2){text-align:left}
.standings-table tr:last-child td{border-bottom:none}
.standings-table tr:hover td{background:rgba(116,185,255,.04)}
.team-cell{display:flex;align-items:center;gap:.6rem}
.team-logo{width:22px;height:22px;object-fit:contain}
.pos{font-weight:700;color:var(--muted);width:28px}
.pos-q1{color:var(--cel);font-weight:800}
.pos-q2{color:#55efc4;font-weight:700}
.pos-rel{color:#e17055;font-weight:700}
.pts{font-weight:800;color:var(--cel);font-size:1rem}
.form-badge{display:inline-block;width:18px;height:18px;border-radius:3px;font-size:.6rem;font-weight:800;line-height:18px;text-align:center;color:#fff}
.form-W{background:#00b894}
.form-D{background:#636e72}
.form-L{background:#d63031}

/* MATCHES */
.matches-list{display:grid;gap:.6rem}
.match-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:.9rem 1.2rem;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:.75rem;transition:border-color .2s}
.match-card:hover{border-color:var(--cel)}
.match-card.live{border-color:#e53935;box-shadow:0 0 10px rgba(229,57,53,.15)}
.team-home{display:flex;align-items:center;gap:.6rem}
.team-away{display:flex;align-items:center;gap:.6rem;flex-direction:row-reverse;text-align:right}
.team-logo-sm{width:24px;height:24px;object-fit:contain}
.team-name{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem}
.score-box{text-align:center;min-width:80px}
.score-num{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--cel);letter-spacing:3px}
.score-vs{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:700;color:var(--muted)}
.match-status{font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;margin-top:.2rem}
.status-live{color:#e53935;animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.4}}
.status-ft{color:var(--muted)}
.status-ns{color:#55efc4}
.match-round{font-size:.65rem;color:var(--muted)}

/* PLAYERS */
.players-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem}
.player-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.player-card:hover{transform:translateY(-3px);border-color:var(--cel)}
.player-photo{width:100%;height:140px;object-fit:cover;object-position:top;background:var(--dark2)}
.player-photo-placeholder{width:100%;height:140px;display:flex;align-items:center;justify-content:center;background:var(--dark2);font-size:3rem}
.player-body{padding:.75rem}
.player-name{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1rem;color:#fff}
.player-club{display:flex;align-items:center;gap:.4rem;font-size:.75rem;color:var(--muted);margin:.25rem 0}
.player-club img{width:16px;height:16px;object-fit:contain}
.player-stats{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;margin-top:.5rem}
.stat-box{background:var(--dark2);border-radius:4px;padding:.35rem .5rem;text-align:center}
.stat-num{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:900;color:var(--cel)}
.stat-lbl{font-size:.6rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)}
.rank-num{display:inline-block;background:var(--cel);color:var(--dark);font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:.8rem;width:22px;height:22px;border-radius:50%;line-height:22px;text-align:center;margin-right:.4rem}

/* NEWS */
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.news-card:hover{transform:translateY(-3px);border-color:var(--cel)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column}
.card-img{height:150px;overflow:hidden;background:var(--dark2)}
.card-img img{width:100%;height:100%;object-fit:cover}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:.9rem;flex:1;display:flex;flex-direction:column;gap:.3rem}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;line-height:1.3;color:#fff;flex:1}
.card-meta{font-size:.7rem;color:var(--muted)}
.section-hdr{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--cel);border-left:3px solid var(--cel);padding-left:.6rem;margin-bottom:1rem}
.empty{text-align:center;padding:3rem;color:var(--muted)}
.pagination{display:flex;gap:.5rem;justify-content:center;padding:2rem 0}
.page-btn{background:var(--card);border:1px solid var(--border);color:var(--text);padding:.45rem .9rem;border-radius:4px;text-decoration:none;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;transition:all .2s}
.page-btn:hover,.page-btn.active{background:var(--cel);border-color:var(--cel);color:var(--dark)}
footer{background:#060e18;border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem;margin-top:2rem}
footer strong{color:#00c853}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina" class="active">🇦🇷 Argentina</a></li>
    <li><a href="/europa">🌍 Europa</a></li>
    <li><a href="/mundial">🏆 Mundial</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" style="background:#00c853;color:#0a0f0a;padding:.3rem .8rem;border-radius:4px">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<div class="hero">
  <div class="container">
    <h1>🇦🇷 Fútbol <span>Argentino</span></h1>
    <p>Ligas, posiciones, fechas y goleadores del fútbol argentino</p>
    <div class="league-selector">
      <?php foreach ($argLeagues as $key => $l): ?>
      <a href="?liga=<?= $key ?>&tab=<?= $tab ?>"
         class="league-btn <?= $leagueKey === $key ? 'active' : '' ?>">
        <span class="flag"><?= $l['flag'] ?></span><?= htmlspecialchars($l['name']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="container">
  <div class="tabs">
    <a href="?liga=<?= $leagueKey ?>&tab=tabla"      class="tab <?= $tab==='tabla'?'active':''      ?>">Tabla</a>
    <a href="?liga=<?= $leagueKey ?>&tab=resultados" class="tab <?= $tab==='resultados'?'active':'' ?>">Resultados</a>
    <a href="?liga=<?= $leagueKey ?>&tab=proximos"   class="tab <?= $tab==='proximos'?'active':''   ?>">Próximos</a>
    <a href="?liga=<?= $leagueKey ?>&tab=jugadores"  class="tab <?= $tab==='jugadores'?'active':''  ?>">Jugadores</a>
    <a href="?liga=<?= $leagueKey ?>&tab=noticias"   class="tab <?= $tab==='noticias'?'active':''   ?>">Noticias</a>
  </div>

  <!-- TABLA -->
  <div id="tab-tabla" class="tab-content <?= $tab==='tabla'?'active':'' ?>">
    <p class="section-hdr"><?= $lg['flag'] ?> Tabla de posiciones — <?= htmlspecialchars($lg['name']) ?></p>
    <?php if (!$lg['has_standings']): ?>
    <div class="empty">📋 <?= htmlspecialchars($lg['name']) ?> es una copa por eliminación directa — no tiene tabla de posiciones.<br><small>Mirá los partidos en "Resultados" o "Próximos".</small></div>
    <?php elseif (!empty($standings)): ?>
    <div class="standings-wrap">
      <table class="standings-table">
        <thead><tr>
          <th>#</th><th>Equipo</th><th>PJ</th><th>G</th><th>E</th><th>P</th>
          <th>GF</th><th>GC</th><th>DG</th><th>Pts</th>
        </tr></thead>
        <tbody>
        <?php foreach ($standings as $row):
          $pos      = (int)($row['standing_place'] ?? 0);
          $total    = count($standings);
          $posClass = $pos <= 4 ? 'pos-q1' : ($pos >= $total - 2 ? 'pos-rel' : 'pos');
          $gf = (int)($row['standing_F'] ?? 0);
          $gc = (int)($row['standing_A'] ?? 0);
        ?>
        <tr>
          <td class="<?= $posClass ?>"><?= $pos ?></td>
          <td>
            <div class="team-cell">
              <?php if ($row['team_badge'] ?? false): ?>
              <img class="team-logo" src="<?= htmlspecialchars($row['team_badge']) ?>" alt="" onerror="this.style.display='none'">
              <?php endif; ?>
              <?= htmlspecialchars($row['team_name'] ?? '') ?>
            </div>
          </td>
          <td><?= $row['standing_PL']  ?? 0 ?></td>
          <td><?= $row['standing_W']   ?? 0 ?></td>
          <td><?= $row['standing_D']   ?? 0 ?></td>
          <td><?= $row['standing_L']   ?? 0 ?></td>
          <td><?= $gf ?></td>
          <td><?= $gc ?></td>
          <td><?= $gf - $gc ?></td>
          <td class="pts"><?= $row['standing_PTS'] ?? 0 ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p style="font-size:.72rem;color:var(--muted);margin-top:1rem">
      <span style="color:var(--cel)">■</span> Clasifican a Copa Libertadores &nbsp;
      <span style="color:#55efc4">■</span> Clasifican a Copa Sudamericana &nbsp;
      <span style="color:#e17055">■</span> Zona de descenso
    </p>
    <?php else: ?>
    <div class="empty">Tabla no disponible. Verificá la API key.</div>
    <?php endif; ?>
  </div>

  <!-- RESULTADOS -->
  <div id="tab-resultados" class="tab-content <?= $tab==='resultados'?'active':'' ?>">
    <?php if (!empty($live)): ?>
    <p class="section-hdr" style="margin-bottom:1rem;color:#e53935">⚡ En vivo</p>
    <div class="matches-list" style="margin-bottom:2rem">
      <?php foreach ($live as $m): ?>
      <div class="match-card live">
        <div class="team-home">
          <?php if ($m['team_home_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_home_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_hometeam_name'] ?? '') ?></span>
        </div>
        <div class="score-box">
          <div class="score-num"><?= $m['match_hometeam_score'] ?? '0' ?> - <?= $m['match_awayteam_score'] ?? '0' ?></div>
          <div class="match-status status-live"><?= htmlspecialchars($m['match_status'] ?? '') ?></div>
        </div>
        <div class="team-away">
          <?php if ($m['team_away_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_away_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_awayteam_name'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <p class="section-hdr">Últimos resultados — <?= htmlspecialchars($lg['name']) ?></p>
    <?php if (!empty($recent)): ?>
    <div class="matches-list">
      <?php foreach ($recent as $m): ?>
      <div class="match-card">
        <div class="team-home">
          <?php if ($m['team_home_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_home_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_hometeam_name'] ?? '') ?></span>
        </div>
        <div class="score-box">
          <div class="score-num"><?= $m['match_hometeam_score'] ?? '—' ?> - <?= $m['match_awayteam_score'] ?? '—' ?></div>
          <div class="match-status status-ft">Final</div>
          <div class="match-round"><?= htmlspecialchars($m['league_round'] ?? '') ?></div>
        </div>
        <div class="team-away">
          <?php if ($m['team_away_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_away_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_awayteam_name'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?><div class="empty">No hay resultados recientes</div><?php endif; ?>
  </div>

  <!-- PRÓXIMOS -->
  <div id="tab-proximos" class="tab-content <?= $tab==='proximos'?'active':'' ?>">
    <p class="section-hdr">Próximos partidos — <?= htmlspecialchars($lg['name']) ?></p>
    <?php if (!empty($upcoming)): ?>
    <div class="matches-list">
      <?php foreach ($upcoming as $m): ?>
      <div class="match-card">
        <div class="team-home">
          <?php if ($m['team_home_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_home_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_hometeam_name'] ?? '') ?></span>
        </div>
        <div class="score-box">
          <div class="score-vs">VS</div>
          <div class="match-status status-ns"><?= htmlspecialchars(($m['match_date'] ?? '') . ' ' . ($m['match_time'] ?? '')) ?></div>
          <div class="match-round"><?= htmlspecialchars($m['league_round'] ?? '') ?></div>
        </div>
        <div class="team-away">
          <?php if ($m['team_away_badge'] ?? false): ?>
          <img class="team-logo-sm" src="<?= htmlspecialchars($m['team_away_badge']) ?>" alt="" onerror="this.style.display='none'">
          <?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['match_awayteam_name'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?><div class="empty">No hay partidos programados</div><?php endif; ?>
  </div>

  <!-- JUGADORES -->
  <div id="tab-jugadores" class="tab-content <?= $tab==='jugadores'?'active':'' ?>">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">
      <div>
        <p class="section-hdr">⚽ Goleadores</p>
        <?php if (!empty($topScorers)): ?>
        <div class="players-grid" style="grid-template-columns:1fr">
          <?php foreach ($topScorers as $i => $p): ?>
          <div class="player-card" style="display:flex;align-items:center;gap:.75rem;padding:.75rem">
            <span class="rank-num"><?= $i+1 ?></span>
            <?php if ($p['player_image'] ?? false): ?>
            <img src="<?= htmlspecialchars($p['player_image']) ?>" style="width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:top;background:var(--dark2)" alt="" onerror="this.style.display='none'">
            <?php else: ?>
            <div style="width:44px;height:44px;border-radius:50%;background:var(--dark2);display:flex;align-items:center;justify-content:center;font-size:1.2rem">👤</div>
            <?php endif; ?>
            <div style="flex:1">
              <div class="player-name"><?= htmlspecialchars($p['player_name'] ?? '') ?></div>
              <div class="player-club"><?= htmlspecialchars($p['team_name'] ?? '') ?></div>
            </div>
            <div style="text-align:center">
              <div class="stat-num"><?= $p['player_goals'] ?? 0 ?></div>
              <div class="stat-lbl">Goles</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?><div class="empty">No disponible</div><?php endif; ?>
      </div>
      <div>
        <p class="section-hdr">🎯 Asistencias</p>
        <?php if (!empty($topAssists)): ?>
        <div class="players-grid" style="grid-template-columns:1fr">
          <?php foreach ($topAssists as $i => $p): ?>
          <div class="player-card" style="display:flex;align-items:center;gap:.75rem;padding:.75rem">
            <span class="rank-num" style="background:var(--cel)"><?= $i+1 ?></span>
            <?php if ($p['player_image'] ?? false): ?>
            <img src="<?= htmlspecialchars($p['player_image']) ?>" style="width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:top;background:var(--dark2)" alt="" onerror="this.style.display='none'">
            <?php else: ?>
            <div style="width:44px;height:44px;border-radius:50%;background:var(--dark2);display:flex;align-items:center;justify-content:center;font-size:1.2rem">👤</div>
            <?php endif; ?>
            <div style="flex:1">
              <div class="player-name"><?= htmlspecialchars($p['player_name'] ?? '') ?></div>
              <div class="player-club"><?= htmlspecialchars($p['team_name'] ?? '') ?></div>
            </div>
            <div style="text-align:center">
              <div class="stat-num" style="color:var(--cel)"><?= $p['player_assists'] ?? 0 ?></div>
              <div class="stat-lbl">Asist.</div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?><div class="empty">No disponible</div><?php endif; ?>
      </div>
    </div>
  </div>

  <!-- NOTICIAS -->
  <div id="tab-noticias" class="tab-content <?= $tab==='noticias'?'active':'' ?>">
    <p class="section-hdr">Noticias Argentina (<?= $total ?>)</p>
    <?php if (!empty($news)): ?>
    <div class="news-grid">
      <?php foreach ($news as $n): ?>
      <article class="news-card">
        <a href="/noticia?id=<?= $n['id'] ?>">
          <div class="card-img">
            <?php if ($n['image_url']): ?><img src="<?= htmlspecialchars($n['image_url']) ?>" alt="" loading="lazy">
            <?php else: ?><div class="card-no-img">🇦🇷</div><?php endif; ?>
          </div>
          <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($n['title']) ?></h2>
            <span class="card-meta"><?= htmlspecialchars($n['source_name']??'') ?> · <?= date('d/m H:i',strtotime($n['scraped_at'])) ?></span>
          </div>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?><a href="?liga=<?= $leagueKey ?>&tab=noticias&page=<?= $page-1 ?>" class="page-btn">← Anterior</a><?php endif; ?>
      <?php for ($p=max(1,$page-2);$p<=min($totalPages,$page+2);$p++): ?>
      <a href="?liga=<?= $leagueKey ?>&tab=noticias&page=<?= $p ?>" class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?><a href="?liga=<?= $leagueKey ?>&tab=noticias&page=<?= $page+1 ?>" class="page-btn">Siguiente →</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php else: ?><div class="empty">No hay noticias argentinas. Ejecutá el scraper.</div><?php endif; ?>
  </div>
</div>

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?> · Datos: API-Football</footer>
<script>
// navegación por tabs via links GET
</script>
</body>
</html>
