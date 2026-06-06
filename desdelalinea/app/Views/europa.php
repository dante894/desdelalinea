<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Fútbol Europeo — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--gold:#ffd600;--dark:#0a0a0f;--dark2:#12121e;--card:#16162a;--border:#2a2a4a;--text:#e8e8f8;--muted:#6a6a9f}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,10,15,.96);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--gold)}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
.hero{background:linear-gradient(135deg,#0a0a1a,#1a1a3a,#0a0a2a);border-bottom:3px solid var(--gold);padding:2rem 0}
.hero h1{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2.2rem,4vw,3.5rem);font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:2px}
.hero h1 span{color:var(--gold)}

/* Liga selector */
.liga-bar{display:flex;gap:.5rem;flex-wrap:wrap;padding:1.5rem 0 0}
.liga-btn{display:flex;align-items:center;gap:.5rem;background:var(--card);border:1px solid var(--border);color:var(--muted);padding:.5rem 1.2rem;border-radius:6px;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;text-decoration:none;transition:all .2s;cursor:pointer}
.liga-btn:hover{border-color:var(--gold);color:var(--text)}
.liga-btn.active{background:var(--gold);border-color:var(--gold);color:#0a0a0f}
.liga-name{font-size:.85rem}

/* Tabs */
.tabs{display:flex;gap:0;border-bottom:2px solid var(--border);margin-top:1.5rem}
.tab{padding:.75rem 1.4rem;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;text-decoration:none}
.tab:hover{color:var(--text)}
.tab.active{color:var(--gold);border-bottom-color:var(--gold)}
.tab-content{display:none;padding:2rem 0}
.tab-content.active{display:block}

/* Standings */
.standings-wrap{overflow-x:auto}
.standings-table{width:100%;border-collapse:collapse;font-size:.85rem;min-width:600px}
.standings-table th{background:var(--dark2);padding:.65rem .75rem;text-align:center;font-family:'Barlow Condensed',sans-serif;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
.standings-table th:first-child,.standings-table th:nth-child(2){text-align:left}
.standings-table td{padding:.65rem .75rem;border-bottom:1px solid var(--border);text-align:center}
.standings-table td:first-child,.standings-table td:nth-child(2){text-align:left}
.standings-table tr:hover td{background:rgba(255,214,0,.03)}
.team-cell{display:flex;align-items:center;gap:.6rem}
.team-logo{width:22px;height:22px;object-fit:contain}
.pos-cl{color:var(--gold);font-weight:800}
.pos-el{color:#74b9ff;font-weight:700}
.pos-rel{color:#e17055;font-weight:700}
.pts{font-weight:800;color:var(--gold)}
.form-badge{display:inline-block;width:18px;height:18px;border-radius:3px;font-size:.6rem;font-weight:800;line-height:18px;text-align:center;color:#fff;margin:1px}
.form-W{background:#00b894}.form-D{background:#636e72}.form-L{background:#d63031}

/* Matches */
.matches-list{display:grid;gap:.6rem}
.match-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:.9rem 1.2rem;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:.75rem;transition:border-color .2s}
.match-card:hover{border-color:var(--gold)}
.match-card.live{border-color:#e53935;box-shadow:0 0 10px rgba(229,57,53,.15)}
.team-home{display:flex;align-items:center;gap:.6rem}
.team-away{display:flex;align-items:center;gap:.6rem;flex-direction:row-reverse;text-align:right}
.team-logo-sm{width:24px;height:24px;object-fit:contain}
.team-name{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem}
.score-box{text-align:center;min-width:80px}
.score-num{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--gold);letter-spacing:3px}
.score-vs{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:700;color:var(--muted)}
.match-status{font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;margin-top:.2rem}
.status-live{color:#e53935;animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.4}}
.status-ft{color:var(--muted)}.status-ns{color:#55efc4}
.match-round{font-size:.65rem;color:var(--muted)}

/* Players */
.players-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem}
.player-card{background:var(--card);border:1px solid var(--border);border-radius:8px;display:flex;align-items:center;gap:.75rem;padding:.75rem;transition:border-color .2s}
.player-card:hover{border-color:var(--gold)}
.rank-num{display:inline-flex;align-items:center;justify-content:center;background:var(--gold);color:#0a0a0f;font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:.8rem;width:24px;height:24px;border-radius:50%;flex-shrink:0}
.player-name{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1rem;color:#fff}
.player-club{display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:var(--muted);margin:.2rem 0}
.stat-num{font-family:'Barlow Condensed',sans-serif;font-size:1.3rem;font-weight:900;color:var(--gold)}
.stat-lbl{font-size:.6rem;text-transform:uppercase;color:var(--muted)}
.section-hdr{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--gold);border-left:3px solid var(--gold);padding-left:.6rem;margin-bottom:1rem}
.empty{text-align:center;padding:3rem;color:var(--muted)}
footer{background:#08080f;border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem;margin-top:2rem}
footer strong{color:var(--green)}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina">🇦🇷 Argentina</a></li>
    <li><a href="/europa" class="active">🌍 Europa</a></li>
    <li><a href="/mundial">🏆 Mundial</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" style="background:var(--green);color:#0a0f0a;padding:.3rem .8rem;border-radius:4px">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<?php
$api2 = new \App\Services\FootballApiRapid();
$ligas = array_filter($api2->leagues, fn($k) => $k !== 'argentina', ARRAY_FILTER_USE_KEY);
?>

<div class="hero">
  <div class="container">
    <h1>🌍 Fútbol <span>Europeo</span></h1>
    <p>Premier League · La Liga · Serie A · Bundesliga · Ligue 1</p>

    <div class="liga-bar">
      <?php foreach ($ligas as $key => $l): ?>
      <a href="?liga=<?= $key ?>&tab=tabla" class="liga-btn <?= $liga===$key?'active':'' ?>">
        <?= $l['flag'] ?> <span class="liga-name"><?= $l['name'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="container">
  <div class="tabs">
    <a href="?liga=<?= $liga ?>&tab=tabla"      class="tab <?= $tab==='tabla'?'active':''?>">Tabla</a>
    <a href="?liga=<?= $liga ?>&tab=resultados" class="tab <?= $tab==='resultados'?'active':''?>">Resultados</a>
    <a href="?liga=<?= $liga ?>&tab=proximos"   class="tab <?= $tab==='proximos'?'active':''?>">Próximos</a>
    <a href="?liga=<?= $liga ?>&tab=jugadores"  class="tab <?= $tab==='jugadores'?'active':''?>">Jugadores</a>
  </div>

  <!-- TABLA -->
  <div id="tab-tabla" class="tab-content <?= $tab==='tabla'?'active':''?>">
    <?php if (!empty($standings)): ?>
    <div class="standings-wrap">
      <table class="standings-table">
        <thead><tr>
          <th>#</th><th>Equipo</th><th>PJ</th><th>G</th><th>E</th><th>P</th>
          <th>GF</th><th>GC</th><th>DG</th><th>Pts</th><th>Forma</th>
        </tr></thead>
        <tbody>
        <?php foreach ($standings as $row):
          $pos = $row['rank'];
          $tot = count($standings);
          $posClass = $pos <= 4 ? 'pos-cl' : ($pos <= 6 ? 'pos-el' : ($pos > $tot-3 ? 'pos-rel' : ''));
        ?>
        <tr>
          <td class="<?= $posClass ?>"><?= $pos ?></td>
          <td><div class="team-cell">
            <?php if ($row['team']['logo']??false): ?>
            <img class="team-logo" src="<?= htmlspecialchars($row['team']['logo']) ?>" alt="">
            <?php endif; ?>
            <?= htmlspecialchars($row['team']['name']) ?>
          </div></td>
          <td><?= $row['all']['played'] ?></td>
          <td><?= $row['all']['win'] ?></td>
          <td><?= $row['all']['draw'] ?></td>
          <td><?= $row['all']['lose'] ?></td>
          <td><?= $row['all']['goals']['for'] ?></td>
          <td><?= $row['all']['goals']['against'] ?></td>
          <td><?= $row['goalsDiff'] ?></td>
          <td class="pts"><?= $row['points'] ?></td>
          <td><?php foreach(str_split($row['form']??'') as $f): ?>
            <span class="form-badge form-<?= $f ?>"><?= $f ?></span>
          <?php endforeach; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p style="font-size:.72rem;color:var(--muted);margin-top:1rem">
      <span style="color:var(--gold)">■</span> Champions League &nbsp;
      <span style="color:#74b9ff">■</span> Europa League &nbsp;
      <span style="color:#e17055">■</span> Descenso
    </p>
    <?php else: ?><div class="empty">Tabla no disponible</div><?php endif; ?>
  </div>

  <!-- RESULTADOS -->
  <div id="tab-resultados" class="tab-content <?= $tab==='resultados'?'active':''?>">
    <?php if (!empty($live)): ?>
    <p class="section-hdr" style="color:#e53935;margin-bottom:1rem">⚡ En vivo</p>
    <div class="matches-list" style="margin-bottom:2rem">
      <?php foreach ($live as $m): ?>
      <div class="match-card live">
        <div class="team-home">
          <?php if ($m['teams']['home']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['home']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['home']['name']) ?></span>
        </div>
        <div class="score-box">
          <div class="score-num"><?= $m['goals']['home']??'0' ?> - <?= $m['goals']['away']??'0' ?></div>
          <div class="match-status status-live">● En vivo <?= $m['fixture']['status']['elapsed']??'' ?>'</div>
        </div>
        <div class="team-away">
          <?php if ($m['teams']['away']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['away']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['away']['name']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <p class="section-hdr">Últimos resultados</p>
    <?php if (!empty($recent)): ?>
    <div class="matches-list">
      <?php foreach (array_reverse($recent) as $m): ?>
      <div class="match-card">
        <div class="team-home">
          <?php if ($m['teams']['home']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['home']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['home']['name']) ?></span>
        </div>
        <div class="score-box">
          <div class="score-num"><?= $m['goals']['home']??'—' ?> - <?= $m['goals']['away']??'—' ?></div>
          <div class="match-status status-ft">Final</div>
          <div class="match-round"><?= htmlspecialchars($m['league']['round']??'') ?></div>
        </div>
        <div class="team-away">
          <?php if ($m['teams']['away']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['away']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['away']['name']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?><div class="empty">No hay resultados recientes</div><?php endif; ?>
  </div>

  <!-- PRÓXIMOS -->
  <div id="tab-proximos" class="tab-content <?= $tab==='proximos'?'active':''?>">
    <p class="section-hdr">Próximos partidos</p>
    <?php if (!empty($upcoming)): ?>
    <div class="matches-list">
      <?php foreach ($upcoming as $m): ?>
      <div class="match-card">
        <div class="team-home">
          <?php if ($m['teams']['home']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['home']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['home']['name']) ?></span>
        </div>
        <div class="score-box">
          <div class="score-vs">VS</div>
          <div class="match-status status-ns"><?= date('d/m H:i', strtotime($m['fixture']['date'])) ?></div>
          <div class="match-round"><?= htmlspecialchars($m['league']['round']??'') ?></div>
        </div>
        <div class="team-away">
          <?php if ($m['teams']['away']['logo']??false): ?><img class="team-logo-sm" src="<?= htmlspecialchars($m['teams']['away']['logo']) ?>" alt=""><?php endif; ?>
          <span class="team-name"><?= htmlspecialchars($m['teams']['away']['name']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?><div class="empty">No hay partidos programados</div><?php endif; ?>
  </div>

  <!-- JUGADORES -->
  <div id="tab-jugadores" class="tab-content <?= $tab==='jugadores'?'active':''?>">
    <div class="players-grid">
      <div>
        <p class="section-hdr">⚽ Goleadores</p>
        <?php if (!empty($topScorers)): ?>
        <?php foreach ($topScorers as $i => $p):
          $pl=$p['player']; $st=$p['statistics'][0]??[];
        ?>
        <div class="player-card" style="margin-bottom:.5rem">
          <span class="rank-num"><?= $i+1 ?></span>
          <?php if ($pl['photo']??false): ?>
          <img src="<?= htmlspecialchars($pl['photo']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;object-position:top" alt="">
          <?php else: ?>
          <div style="width:40px;height:40px;border-radius:50%;background:var(--dark2);display:flex;align-items:center;justify-content:center">👤</div>
          <?php endif; ?>
          <div style="flex:1">
            <div class="player-name"><?= htmlspecialchars($pl['name']) ?></div>
            <div class="player-club">
              <?php if ($st['team']['logo']??false): ?><img src="<?= htmlspecialchars($st['team']['logo']) ?>" style="width:14px;height:14px;object-fit:contain" alt=""><?php endif; ?>
              <?= htmlspecialchars($st['team']['name']??'') ?>
              · <?= htmlspecialchars($pl['nationality']??'') ?>
            </div>
          </div>
          <div style="text-align:center">
            <div class="stat-num"><?= $st['goals']['total']??0 ?></div>
            <div class="stat-lbl">Goles</div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?><div class="empty">No disponible</div><?php endif; ?>
      </div>
      <div>
        <p class="section-hdr">🎯 Asistencias</p>
        <?php if (!empty($topAssists)): ?>
        <?php foreach ($topAssists as $i => $p):
          $pl=$p['player']; $st=$p['statistics'][0]??[];
        ?>
        <div class="player-card" style="margin-bottom:.5rem">
          <span class="rank-num" style="background:#74b9ff"><?= $i+1 ?></span>
          <?php if ($pl['photo']??false): ?>
          <img src="<?= htmlspecialchars($pl['photo']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;object-position:top" alt="">
          <?php else: ?>
          <div style="width:40px;height:40px;border-radius:50%;background:var(--dark2);display:flex;align-items:center;justify-content:center">👤</div>
          <?php endif; ?>
          <div style="flex:1">
            <div class="player-name"><?= htmlspecialchars($pl['name']) ?></div>
            <div class="player-club">
              <?php if ($st['team']['logo']??false): ?><img src="<?= htmlspecialchars($st['team']['logo']) ?>" style="width:14px;height:14px;object-fit:contain" alt=""><?php endif; ?>
              <?= htmlspecialchars($st['team']['name']??'') ?>
            </div>
          </div>
          <div style="text-align:center">
            <div class="stat-num" style="color:#74b9ff"><?= $st['goals']['assists']??0 ?></div>
            <div class="stat-lbl">Asist.</div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?><div class="empty">No disponible</div><?php endif; ?>
      </div>
    </div>
  </div>
</div>

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?> · Datos: API-Football</footer>
</body>
</html>
