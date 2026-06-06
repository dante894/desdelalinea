<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mundial 2026 — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--gold:#ffd600;--dark:#07100a;--dark2:#0d1a0d;--dark3:#141f14;--card:#111c11;--border:#1c2e1c;--text:#e8f5e8;--muted:#5a7a5a;--live:#e53935}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(7,16,10,.97);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none;align-items:center}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover{color:var(--green)}
.nav-links a.active{color:var(--gold)}
.container{max-width:1280px;margin:0 auto;padding:0 1.5rem}

/* HERO MUNDIAL */
.mundial-hero{background:linear-gradient(160deg,#070f07,#0f2a0a,#071a07);border-bottom:3px solid var(--gold);padding:3rem 0;position:relative;overflow:hidden}
.mundial-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 70% 50%,rgba(255,214,0,.06) 0%,transparent 60%)}
.mundial-hero::after{content:'⚽';position:absolute;right:3rem;top:50%;transform:translateY(-50%);font-size:10rem;opacity:.05;pointer-events:none}
.mundial-hero h1{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2.5rem,5vw,4.5rem);font-weight:900;color:var(--gold);text-transform:uppercase;letter-spacing:3px;line-height:1}
.mundial-hero p{color:var(--muted);margin-top:.5rem;font-size:.95rem}
.hero-sedes{display:flex;gap:1rem;margin-top:1rem;flex-wrap:wrap}
.sede-badge{display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,214,0,.08);border:1px solid rgba(255,214,0,.2);padding:.3rem .75rem;border-radius:20px;font-size:.78rem;color:var(--gold);font-weight:600}
.live-badge{display:inline-flex;align-items:center;gap:.4rem;background:var(--live);color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;padding:.25rem .65rem;border-radius:3px;margin-left:1rem;vertical-align:middle;animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.5}}

/* TABS */
.tabs{display:flex;gap:0;border-bottom:2px solid var(--border);margin:2rem 0 0;overflow-x:auto;scrollbar-width:none}
.tabs::-webkit-scrollbar{display:none}
.tab{padding:.8rem 1.5rem;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;text-decoration:none;white-space:nowrap}
.tab:hover{color:var(--text)}
.tab.active{color:var(--gold);border-bottom-color:var(--gold)}
.tab-content{display:none;padding:2rem 0}
.tab-content.active{display:block}

/* SECTION TITLE */
.section-title{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--gold);border-left:3px solid var(--gold);padding-left:.75rem;margin-bottom:1.25rem}
.section-title.live{color:var(--live);border-left-color:var(--live)}

/* MATCHES */
.matches-grid{display:grid;gap:.75rem}
.match-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1rem 1.5rem;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:1rem;transition:border-color .2s}
.match-card:hover{border-color:rgba(255,214,0,.3)}
.match-card.live{border-color:var(--live);box-shadow:0 0 14px rgba(229,57,53,.2)}
.match-team{display:flex;align-items:center;gap:.75rem;font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:700}
.match-team.away{flex-direction:row-reverse;text-align:right}
.match-team.winner{color:#fff}
.team-flag{font-size:1.6rem}
.match-score{text-align:center}
.score-nums{font-family:'Barlow Condensed',sans-serif;font-size:2.2rem;font-weight:900;color:var(--gold);letter-spacing:3px}
.score-nums.live{color:var(--live)}
.score-dash{font-family:'Barlow Condensed',sans-serif;font-size:1.5rem;font-weight:700;color:var(--muted)}
.match-sub{font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:.25rem}
.match-sub.live-txt{color:var(--live);font-weight:700;animation:blink 1.5s infinite}
.match-sub.prox-txt{color:var(--green)}
.match-round-badge{display:inline-block;background:rgba(255,214,0,.1);border:1px solid rgba(255,214,0,.2);color:var(--gold);font-family:'Barlow Condensed',sans-serif;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;padding:.15rem .5rem;border-radius:3px;margin-bottom:.4rem}

/* GOAL PILLS */
.goals-row{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.4rem;justify-content:center}
.goal-pill{font-size:.62rem;background:rgba(255,214,0,.1);border-radius:3px;padding:.15rem .4rem;color:rgba(255,214,0,.8)}

/* STANDINGS */
.standings-layout{display:grid;grid-template-columns:repeat(auto-fill,minmax(580px,1fr));gap:1.5rem}
@media(max-width:700px){.standings-layout{grid-template-columns:1fr}}
.standings-group{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden}
.group-header{padding:.75rem 1rem;background:var(--dark3);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.5rem}
.group-name{font-family:'Barlow Condensed',sans-serif;font-size:1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--gold)}
.standings-table{width:100%;border-collapse:collapse;font-size:.83rem}
.standings-table th{background:var(--dark3);padding:.55rem .7rem;text-align:left;font-family:'Barlow Condensed',sans-serif;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);white-space:nowrap}
.standings-table th:not(:first-child):not(:nth-child(2)){text-align:center}
.standings-table td{padding:.6rem .7rem;border-bottom:1px solid var(--border);color:var(--text)}
.standings-table td:not(:first-child):not(:nth-child(2)){text-align:center}
.standings-table tr:last-child td{border-bottom:none}
.standings-table tr:hover td{background:rgba(255,214,0,.03)}
.pos-qualify{color:var(--green);font-weight:800}
.pos-third{color:rgba(255,214,0,.7);font-weight:700}
.pts-col{font-weight:800;color:var(--gold)}
.team-name-row{display:flex;align-items:center;gap:.5rem}
.team-crest{width:18px;height:18px;object-fit:contain}

/* TOP SCORERS */
.scorers-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}
.scorer-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1rem;display:flex;align-items:center;gap:.85rem;transition:border-color .2s}
.scorer-card:hover{border-color:rgba(255,214,0,.3)}
.scorer-rank{font-family:'Barlow Condensed',sans-serif;font-size:1.4rem;font-weight:900;color:var(--gold);width:28px;text-align:center;flex-shrink:0}
.scorer-rank.top{color:var(--gold)}
.scorer-photo{width:52px;height:52px;border-radius:50%;object-fit:cover;object-position:top;background:var(--dark3);flex-shrink:0}
.scorer-info{flex:1}
.scorer-name{font-family:'Barlow Condensed',sans-serif;font-size:1rem;font-weight:800;color:#fff}
.scorer-team{font-size:.72rem;color:var(--muted);margin:.15rem 0}
.scorer-val{text-align:right;flex-shrink:0}
.scorer-num{font-family:'Barlow Condensed',sans-serif;font-size:1.8rem;font-weight:900;color:var(--gold);line-height:1}
.scorer-lbl{font-size:.6rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)}

/* EQUIPOS */
.equipos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem}
.equipo-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1rem;text-align:center;transition:transform .2s,border-color .2s}
.equipo-card:hover{transform:translateY(-3px);border-color:rgba(255,214,0,.3)}
.equipo-flag{font-size:2.5rem;margin-bottom:.5rem}
.equipo-name{font-family:'Barlow Condensed',sans-serif;font-size:.9rem;font-weight:700;color:#fff;margin-bottom:.25rem}
.equipo-group{font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.equipo-crest{width:48px;height:48px;object-fit:contain;margin-bottom:.5rem}

/* NEWS */
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.news-card:hover{transform:translateY(-3px);border-color:rgba(255,214,0,.3)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column}
.card-img{height:150px;background:var(--dark3);overflow:hidden}
.card-img img{width:100%;height:100%;object-fit:cover}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:.9rem;flex:1;display:flex;flex-direction:column;gap:.4rem}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;line-height:1.3;flex:1}
.card-meta{font-size:.72rem;color:var(--muted)}
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
    <li><a href="/europa">🌍 Europa</a></li>
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
    <p>El torneo más grande del mundo en tres países sede</p>
    <div class="hero-sedes">
      <span class="sede-badge">🇺🇸 Estados Unidos</span>
      <span class="sede-badge">🇲🇽 México</span>
      <span class="sede-badge">🇨🇦 Canadá</span>
      <span class="sede-badge">🌍 48 selecciones</span>
      <span class="sede-badge">📅 Junio — Julio 2026</span>
    </div>
  </div>
</div>

<div class="container">
  <div class="tabs">
    <a href="?tab=partidos"   class="tab <?= ($tab==='partidos'||$tab==='resultados')?'active':'' ?>" onclick="showTab('partidos',this);return false">Resultados</a>
    <a href="?tab=proximos"   class="tab <?= $tab==='proximos'?'active':'' ?>"                        onclick="showTab('proximos',this);return false">Próximos</a>
    <a href="?tab=tabla"      class="tab <?= $tab==='tabla'?'active':'' ?>"                           onclick="showTab('tabla',this);return false">Grupos / Tabla</a>
    <a href="?tab=goleadores" class="tab <?= $tab==='goleadores'?'active':'' ?>"                      onclick="showTab('goleadores',this);return false">Goleadores</a>
    <a href="?tab=equipos"    class="tab <?= $tab==='equipos'?'active':'' ?>"                         onclick="showTab('equipos',this);return false">Equipos</a>
    <a href="?tab=noticias"   class="tab <?= $tab==='noticias'?'active':'' ?>"                        onclick="showTab('noticias',this);return false">Noticias</a>
  </div>

  <!-- RESULTADOS -->
  <div id="tab-partidos" class="tab-content <?= ($tab==='partidos'||$tab==='resultados')?'active':'' ?>">
    <?php if (!empty($live)): ?>
    <p class="section-title live" style="margin-bottom:1rem">⚡ Partidos En Vivo</p>
    <div class="matches-grid" style="margin-bottom:2.5rem">
      <?php foreach ($live as $m): ?>
      <div class="match-card live">
        <?php if ($m['home']['goals'] ?? []): ?>
        <div style="grid-column:1/-1;display:flex;justify-content:space-between;font-size:.65rem;color:var(--muted);margin-bottom:-.25rem">
          <div><?php foreach ($m['home']['goals']??[] as $g): ?><?= htmlspecialchars($g['name']) ?> <?= $g['clock'] ?>' &nbsp;<?php endforeach; ?></div>
          <div><?php foreach ($m['away']['goals']??[] as $g): ?>&nbsp; <?= htmlspecialchars($g['name']) ?> <?= $g['clock'] ?>'<?php endforeach; ?></div>
        </div>
        <?php endif; ?>
        <div class="match-team <?= ($m['home']['winner'] ?? false) ? 'winner' : '' ?>">
          <span class="team-flag"><?= getFlagEmoji($m['home']['abbr'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['home']['name']) ?></span>
        </div>
        <div class="match-score">
          <div class="score-nums live"><?= $m['home']['score'] ?? 0 ?> — <?= $m['away']['score'] ?? 0 ?></div>
          <div class="match-sub live-txt">● <?= htmlspecialchars($m['clock'] ?? 'LIVE') ?></div>
          <?php if ($m['venue']): ?><div class="match-sub"><?= htmlspecialchars($m['venue']) ?></div><?php endif; ?>
        </div>
        <div class="match-team away <?= ($m['away']['winner'] ?? false) ? 'winner' : '' ?>">
          <span><?= htmlspecialchars($m['away']['name']) ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['away']['abbr'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <p class="section-title">Últimos Resultados</p>
    <?php if (!empty($recent)): ?>
    <div class="matches-grid">
      <?php foreach ($recent as $m): ?>
      <div class="match-card">
        <div class="match-team <?= ($m['home']['winner'] ?? false) ? 'winner' : '' ?>">
          <span class="team-flag"><?= getFlagEmoji($m['home']['abbr'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['home']['name']) ?></span>
        </div>
        <div class="match-score">
          <div class="score-nums"><?= $m['home']['score'] ?? '—' ?> — <?= $m['away']['score'] ?? '—' ?></div>
          <div class="match-sub"><?= $m['date'] ? date('d/m H:i', strtotime($m['date']) - 3*3600) : '' ?></div>
          <div class="match-sub" style="color:var(--muted)">Finalizado</div>
          <?php if (!empty($m['home']['goals']) || !empty($m['away']['goals'])): ?>
          <div class="goals-row">
            <?php foreach ($m['home']['goals'] as $g): ?>
            <span class="goal-pill">⚽ <?= htmlspecialchars($g['name']) ?> <?= $g['clock'] ?>'</span>
            <?php endforeach; ?>
            <?php foreach ($m['away']['goals'] as $g): ?>
            <span class="goal-pill">⚽ <?= htmlspecialchars($g['name']) ?> <?= $g['clock'] ?>'</span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <div class="match-team away <?= ($m['away']['winner'] ?? false) ? 'winner' : '' ?>">
          <span><?= htmlspecialchars($m['away']['name']) ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['away']['abbr'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">No hay resultados disponibles aún.<br><small>El Mundial 2026 se juega en junio-julio 2026.</small></div>
    <?php endif; ?>
  </div>

  <!-- PRÓXIMOS -->
  <div id="tab-proximos" class="tab-content <?= $tab==='proximos'?'active':'' ?>">
    <p class="section-title">Próximos Partidos</p>
    <?php if (!empty($upcoming)): ?>
    <div class="matches-grid">
      <?php foreach ($upcoming as $m): ?>
      <div class="match-card">
        <div class="match-team">
          <span class="team-flag"><?= getFlagEmoji($m['home']['abbr'] ?? '') ?></span>
          <span><?= htmlspecialchars($m['home']['name']) ?></span>
        </div>
        <div class="match-score">
          <div class="score-dash">VS</div>
          <div class="match-sub"><?= $m['date'] ? date('d/m/Y', strtotime($m['date']) - 3*3600) : '' ?></div>
          <div class="match-sub prox-txt"><?= $m['date'] ? date('H:i', strtotime($m['date']) - 3*3600) : '' ?> ARG</div>
          <?php if ($m['venue']): ?><div class="match-sub"><?= htmlspecialchars($m['venue']) ?></div><?php endif; ?>
        </div>
        <div class="match-team away">
          <span><?= htmlspecialchars($m['away']['name']) ?></span>
          <span class="team-flag"><?= getFlagEmoji($m['away']['abbr'] ?? '') ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">No hay partidos programados disponibles.</div>
    <?php endif; ?>
  </div>

  <!-- TABLA POR GRUPOS -->
  <div id="tab-tabla" class="tab-content <?= $tab==='tabla'?'active':'' ?>">
    <p class="section-title">Tabla de Posiciones por Grupo</p>
    <?php if (!empty($standingsByGroup)): ?>
    <div class="standings-layout">
      <?php foreach ($standingsByGroup as $groupName => $rows): ?>
      <div class="standings-group">
        <div class="group-header">
          <span class="group-name">⚽ <?= htmlspecialchars($groupName) ?></span>
        </div>
        <table class="standings-table">
          <thead>
            <tr>
              <th>#</th><th>País</th><th>PJ</th><th>G</th><th>E</th><th>P</th><th>GF</th><th>GC</th><th>DG</th><th>Pts</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row):
              $pos = (int)($row['rank'] ?? 0);
            ?>
            <tr>
              <td class="<?= $pos <= 2 ? 'pos-qualify' : ($pos == 3 ? 'pos-third' : '') ?>"><?= $pos ?></td>
              <td>
                <div class="team-name-row">
                  <?= getFlagEmoji($row['abbr'] ?? '') ?>
                  <?php if ($row['logo']): ?>
                  <img class="team-crest" src="<?= htmlspecialchars($row['logo']) ?>" alt="" onerror="this.style.display='none'">
                  <?php endif; ?>
                  <?= htmlspecialchars($row['name']) ?>
                </div>
              </td>
              <td><?= $row['played'] ?></td>
              <td><?= $row['wins'] ?></td>
              <td><?= $row['draws'] ?></td>
              <td><?= $row['losses'] ?></td>
              <td><?= $row['gf'] ?></td>
              <td><?= $row['gc'] ?></td>
              <td><?= ($row['gd'] > 0 ? '+' : '') . $row['gd'] ?></td>
              <td class="pts-col"><?= $row['points'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endforeach; ?>
    </div>
    <p style="font-size:.75rem;color:var(--muted);margin-top:1.5rem">
      <span style="color:var(--green)">■</span> Clasifican a octavos de final &nbsp;
      <span style="color:rgba(255,214,0,.7)">■</span> Potencialmente los mejores terceros
    </p>
    <?php else: ?>
    <div class="empty">La tabla de posiciones estará disponible cuando comience el torneo.<br><small>Mundial 2026 · Junio-Julio 2026</small></div>
    <?php endif; ?>
  </div>

  <!-- GOLEADORES -->
  <div id="tab-goleadores" class="tab-content <?= $tab==='goleadores'?'active':'' ?>">
    <p class="section-title">⚽ Tabla de Goleadores</p>
    <?php if (!empty($topScorers)): ?>
    <div class="scorers-grid">
      <?php foreach ($topScorers as $i => $p):
        $athlete = $p['athlete'] ?? $p;
        $pname   = $athlete['displayName'] ?? $athlete['name'] ?? '?';
        $pteam   = $athlete['team']['displayName'] ?? $athlete['team']['name'] ?? '';
        $pphoto  = $athlete['headshot']['href'] ?? $athlete['headshot'] ?? null;
        $pgoals  = $p['value'] ?? $p['displayValue'] ?? 0;
      ?>
      <div class="scorer-card">
        <span class="scorer-rank top"><?= $i+1 ?></span>
        <?php if ($pphoto): ?>
        <img class="scorer-photo" src="<?= htmlspecialchars($pphoto) ?>" alt="" onerror="this.style.display='none'">
        <?php else: ?>
        <div class="scorer-photo" style="display:flex;align-items:center;justify-content:center;font-size:1.8rem">👤</div>
        <?php endif; ?>
        <div class="scorer-info">
          <div class="scorer-name"><?= htmlspecialchars($pname) ?></div>
          <div class="scorer-team"><?= htmlspecialchars($pteam) ?></div>
        </div>
        <div class="scorer-val">
          <div class="scorer-num"><?= $pgoals ?></div>
          <div class="scorer-lbl">Goles</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty">Los goleadores aparecerán cuando comience el torneo.</div>
    <?php endif; ?>
  </div>

  <!-- EQUIPOS CLASIFICADOS -->
  <div id="tab-equipos" class="tab-content <?= $tab==='equipos'?'active':'' ?>">
    <p class="section-title">🌍 Selecciones Clasificadas</p>
    <?php
    // Equipos del Mundial 2026 (48 selecciones)
    $mundialTeams = [
      ['name'=>'Argentina','flag'=>'🇦🇷','group'=>'Conmebol'],
      ['name'=>'Brasil','flag'=>'🇧🇷','group'=>'Conmebol'],
      ['name'=>'Uruguay','flag'=>'🇺🇾','group'=>'Conmebol'],
      ['name'=>'Colombia','flag'=>'🇨🇴','group'=>'Conmebol'],
      ['name'=>'Ecuador','flag'=>'🇪🇨','group'=>'Conmebol'],
      ['name'=>'Venezuela','flag'=>'🇻🇪','group'=>'Conmebol'],
      ['name'=>'Paraguay','flag'=>'🇵🇾','group'=>'Conmebol'],
      ['name'=>'Bolivia','flag'=>'🇧🇴','group'=>'Conmebol'],
      ['name'=>'Chile','flag'=>'🇨🇱','group'=>'Conmebol'],
      ['name'=>'Perú','flag'=>'🇵🇪','group'=>'Conmebol'],
      ['name'=>'España','flag'=>'🇪🇸','group'=>'UEFA'],
      ['name'=>'Francia','flag'=>'🇫🇷','group'=>'UEFA'],
      ['name'=>'Alemania','flag'=>'🇩🇪','group'=>'UEFA'],
      ['name'=>'Inglaterra','flag'=>'🏴󠁧󠁢󠁥󠁮󠁧󠁿','group'=>'UEFA'],
      ['name'=>'Portugal','flag'=>'🇵🇹','group'=>'UEFA'],
      ['name'=>'Países Bajos','flag'=>'🇳🇱','group'=>'UEFA'],
      ['name'=>'Bélgica','flag'=>'🇧🇪','group'=>'UEFA'],
      ['name'=>'Italia','flag'=>'🇮🇹','group'=>'UEFA'],
      ['name'=>'Croacia','flag'=>'🇭🇷','group'=>'UEFA'],
      ['name'=>'Austria','flag'=>'🇦🇹','group'=>'UEFA'],
      ['name'=>'Dinamarca','flag'=>'🇩🇰','group'=>'UEFA'],
      ['name'=>'Escocia','flag'=>'🏴󠁧󠁢󠁳󠁣󠁴󠁿','group'=>'UEFA'],
      ['name'=>'Hungría','flag'=>'🇭🇺','group'=>'UEFA'],
      ['name'=>'Eslovaquia','flag'=>'🇸🇰','group'=>'UEFA'],
      ['name'=>'Serbia','flag'=>'🇷🇸','group'=>'UEFA'],
      ['name'=>'Turquía','flag'=>'🇹🇷','group'=>'UEFA'],
      ['name'=>'Suiza','flag'=>'🇨🇭','group'=>'UEFA'],
      ['name'=>'Ucrania','flag'=>'🇺🇦','group'=>'UEFA'],
      ['name'=>'Rumania','flag'=>'🇷🇴','group'=>'UEFA'],
      ['name'=>'República Checa','flag'=>'🇨🇿','group'=>'UEFA'],
      ['name'=>'Albania','flag'=>'🇦🇱','group'=>'UEFA'],
      ['name'=>'Eslovenia','flag'=>'🇸🇮','group'=>'UEFA'],
      ['name'=>'Georgia','flag'=>'🇬🇪','group'=>'UEFA'],
      ['name'=>'Estados Unidos','flag'=>'🇺🇸','group'=>'CONCACAF'],
      ['name'=>'México','flag'=>'🇲🇽','group'=>'CONCACAF'],
      ['name'=>'Canadá','flag'=>'🇨🇦','group'=>'CONCACAF'],
      ['name'=>'Panamá','flag'=>'🇵🇦','group'=>'CONCACAF'],
      ['name'=>'Honduras','flag'=>'🇭🇳','group'=>'CONCACAF'],
      ['name'=>'Costa Rica','flag'=>'🇨🇷','group'=>'CONCACAF'],
      ['name'=>'Jamaica','flag'=>'🇯🇲','group'=>'CONCACAF'],
      ['name'=>'Guatemala','flag'=>'🇬🇹','group'=>'CONCACAF'],
      ['name'=>'Marruecos','flag'=>'🇲🇦','group'=>'CAF'],
      ['name'=>'Senegal','flag'=>'🇸🇳','group'=>'CAF'],
      ['name'=>'Egipto','flag'=>'🇪🇬','group'=>'CAF'],
      ['name'=>'Nigeria','flag'=>'🇳🇬','group'=>'CAF'],
      ['name'=>'Costa de Marfil','flag'=>'🇨🇮','group'=>'CAF'],
      ['name'=>'Japón','flag'=>'🇯🇵','group'=>'AFC'],
      ['name'=>'Corea del Sur','flag'=>'🇰🇷','group'=>'AFC'],
      ['name'=>'Australia','flag'=>'🇦🇺','group'=>'AFC'],
    ];
    $byConf = [];
    foreach ($mundialTeams as $t) $byConf[$t['group']][] = $t;
    ?>
    <?php foreach ($byConf as $conf => $teams): ?>
    <div style="margin-bottom:2rem">
      <p style="font-family:'Barlow Condensed',sans-serif;font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:.75rem;border-bottom:1px solid var(--border);padding-bottom:.4rem"><?= $conf ?></p>
      <div class="equipos-grid">
        <?php foreach ($teams as $t): ?>
        <div class="equipo-card">
          <div class="equipo-flag"><?= $t['flag'] ?></div>
          <div class="equipo-name"><?= htmlspecialchars($t['name']) ?></div>
          <div class="equipo-group"><?= $conf ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- NOTICIAS -->
  <div id="tab-noticias" class="tab-content <?= $tab==='noticias'?'active':'' ?>">
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

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?> · Datos: ESPN / football-data.org</footer>

<script>
function showTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    el.classList.add('active');
    history.replaceState(null,'','?tab='+name);
}
// Activar tab por URL
const urlTab = new URLSearchParams(location.search).get('tab');
if (urlTab) {
    const el = document.querySelector(`.tab[href*="tab=${urlTab}"]`);
    if (el) { showTab(urlTab, el); }
}
</script>
</body>
</html>
<?php
function getFlagEmoji(string $tla): string {
    $flags = [
        'ARG'=>'🇦🇷','BRA'=>'🇧🇷','URU'=>'🇺🇾','COL'=>'🇨🇴','ECU'=>'🇪🇨','PER'=>'🇵🇪','CHI'=>'🇨🇱','VEN'=>'🇻🇪',
        'BOL'=>'🇧🇴','PAR'=>'🇵🇾',
        'MEX'=>'🇲🇽','USA'=>'🇺🇸','CAN'=>'🇨🇦','CRC'=>'🇨🇷','PAN'=>'🇵🇦','HON'=>'🇭🇳','JAM'=>'🇯🇲','GUA'=>'🇬🇹',
        'ESP'=>'🇪🇸','FRA'=>'🇫🇷','ALE'=>'🇩🇪','GER'=>'🇩🇪','ENG'=>'🏴󠁧󠁢󠁥󠁮󠁧󠁿','POR'=>'🇵🇹',
        'ITA'=>'🇮🇹','HOL'=>'🇳🇱','NED'=>'🇳🇱','BEL'=>'🇧🇪','CRO'=>'🇭🇷','SUI'=>'🇨🇭','DIN'=>'🇩🇰','DEN'=>'🇩🇰',
        'AUT'=>'🇦🇹','SCO'=>'🏴󠁧󠁢󠁳󠁣󠁴󠁿','HUN'=>'🇭🇺','SVK'=>'🇸🇰','SRB'=>'🇷🇸','TUR'=>'🇹🇷','UKR'=>'🇺🇦',
        'ROU'=>'🇷🇴','CZE'=>'🇨🇿','ALB'=>'🇦🇱','SVN'=>'🇸🇮','GEO'=>'🇬🇪',
        'SEN'=>'🇸🇳','MAR'=>'🇲🇦','CMR'=>'🇨🇲','GHA'=>'🇬🇭','NGR'=>'🇳🇬','CIV'=>'🇨🇮','EGY'=>'🇪🇬',
        'JPN'=>'🇯🇵','KOR'=>'🇰🇷','AUS'=>'🇦🇺','IRA'=>'🇮🇷','SAU'=>'🇸🇦','QAT'=>'🇶🇦',
    ];
    return $flags[strtoupper($tla)] ?? '🏳';
}
