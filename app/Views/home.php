<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Desde la Línea — Portal Deportivo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green:#00c853;--green2:#69f0ae;--dark:#0a0f0a;--dark2:#111711;--dark3:#1a231a;
  --card:#141c14;--border:#1e2e1e;--text:#e8f5e8;--muted:#6a8f6a;--white:#fff;
  --live:#e53935;--gold:#ffd600;
}
html{scroll-behavior:smooth}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh}

/* NAV */
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.97);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none;letter-spacing:-.5px}
.nav-brand span{color:var(--white)}
.nav-links{display:flex;gap:1.5rem;list-style:none;align-items:center}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover{color:var(--green)}
.nav-admin{background:var(--green);color:var(--dark)!important;padding:.35rem .9rem;border-radius:4px;font-weight:700!important}
.nav-mundial{color:var(--gold)!important}

/* HERO */
.hero{position:relative;height:480px;overflow:hidden;border-bottom:2px solid var(--green)}
.hero-img{width:100%;height:100%;object-fit:cover;filter:brightness(.3)}
.hero-placeholder{width:100%;height:100%;background:linear-gradient(135deg,#0d1f0d 0%,#0a2a0a 50%,#051505 100%)}
.hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:2.5rem 3rem;background:linear-gradient(to top,rgba(10,15,10,.95) 0%,transparent 60%)}
.hero-cat{display:inline-block;background:var(--green);color:var(--dark);font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.72rem;letter-spacing:1.5px;text-transform:uppercase;padding:.25rem .7rem;border-radius:2px;margin-bottom:.75rem}
.hero-title{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;line-height:1.05;max-width:750px;margin-bottom:.75rem}
.hero-meta{color:var(--muted);font-size:.85rem}
.hero-meta strong{color:var(--green2)}
.hero a{text-decoration:none;color:inherit}

/* LIVE STRIP */
.live-strip{background:#1a0a0a;border-bottom:1px solid #3a1111;padding:.6rem 0;overflow:hidden}
.live-strip-inner{display:flex;gap:2rem;align-items:center;padding:0 2rem;overflow-x:auto;scrollbar-width:none}
.live-strip-inner::-webkit-scrollbar{display:none}
.live-badge{display:inline-flex;align-items:center;gap:.35rem;background:var(--live);color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:.7rem;letter-spacing:1px;text-transform:uppercase;padding:.2rem .6rem;border-radius:3px;white-space:nowrap;animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.5}}
.live-match{display:flex;align-items:center;gap:.6rem;white-space:nowrap;font-family:'Barlow Condensed',sans-serif;font-size:.9rem;font-weight:700;color:#fff;text-decoration:none}
.live-match:hover{color:var(--live)}
.live-score{color:var(--live);font-weight:900;letter-spacing:2px}
.live-sep{color:#3a1111;font-size:1rem}
.live-clock{font-size:.65rem;color:#e57373;font-weight:600}

/* CONTAINER */
.container{max-width:1280px;margin:0 auto;padding:0 1.5rem}
.section{padding:2.5rem 0}
.section-title{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green);border-left:3px solid var(--green);padding-left:.75rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem}
.section-title a{color:var(--muted);font-size:.75rem;font-weight:600;margin-left:auto;text-decoration:none;text-transform:none;letter-spacing:0}
.section-title a:hover{color:var(--green)}

/* MAIN LAYOUT */
.main-layout{display:grid;grid-template-columns:1fr 340px;gap:2rem;padding:2.5rem 0}
@media(max-width:900px){.main-layout{grid-template-columns:1fr}}

/* NEWS GRID */
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.25rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s;display:flex;flex-direction:column}
.news-card:hover{transform:translateY(-3px);border-color:var(--green)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}
.card-img{height:155px;overflow:hidden;background:var(--dark3);position:relative}
.card-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.news-card:hover .card-img img{transform:scale(1.04)}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:.9rem;flex:1;display:flex;flex-direction:column;gap:.4rem}
.card-cat{font-family:'Barlow Condensed',sans-serif;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--green)}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;line-height:1.3;flex:1}
.card-meta{font-size:.72rem;color:var(--muted);margin-top:auto}
.card-source{font-size:.68rem;color:var(--green2);font-weight:600}

/* SIDEBAR */
.sidebar{display:flex;flex-direction:column;gap:1.5rem}

/* MATCH WIDGET */
.widget{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden}
.widget-hdr{padding:.75rem 1rem;background:var(--dark3);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.widget-title{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;color:var(--green)}
.widget-link{font-size:.72rem;color:var(--muted);text-decoration:none}
.widget-link:hover{color:var(--green)}
.match-item{padding:.65rem 1rem;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:.5rem}
.match-item:last-child{border-bottom:none}
.match-item:hover{background:rgba(0,200,83,.03)}
.mi-team{font-family:'Barlow Condensed',sans-serif;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:.4rem}
.mi-team.away{flex-direction:row-reverse;text-align:right}
.mi-logo{width:18px;height:18px;object-fit:contain}
.mi-center{text-align:center}
.mi-score{font-family:'Barlow Condensed',sans-serif;font-size:1rem;font-weight:900;color:var(--green);letter-spacing:2px}
.mi-score.live{color:var(--live)}
.mi-vs{font-family:'Barlow Condensed',sans-serif;font-size:.85rem;font-weight:700;color:var(--muted)}
.mi-time{font-size:.62rem;color:var(--muted);text-transform:uppercase}
.mi-live{font-size:.62rem;color:var(--live);font-weight:700;animation:blink 1.5s infinite}
.mi-league{font-size:.6rem;color:var(--muted);display:flex;align-items:center;gap:.2rem}

/* CATEGORIES BAR */
.cats-bar{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem}
.cat-btn{background:var(--card);border:1px solid var(--border);color:var(--muted);padding:.35rem .9rem;border-radius:20px;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;text-decoration:none;transition:all .2s}
.cat-btn:hover,.cat-btn.active{background:var(--green);border-color:var(--green);color:var(--dark)}

/* RESULTS STRIP */
.results-grid{display:grid;gap:.5rem}

/* MUNDIAL PROMO */
.mundial-promo{background:linear-gradient(135deg,#0a1f0a,#1a380a);border:1px solid #2a4a0a;border-radius:8px;padding:1.5rem;position:relative;overflow:hidden}
.mundial-promo::after{content:'🏆';position:absolute;right:1rem;top:50%;transform:translateY(-50%);font-size:5rem;opacity:.1;pointer-events:none}
.mundial-promo h3{font-family:'Barlow Condensed',sans-serif;font-size:1.4rem;font-weight:900;color:var(--gold);margin-bottom:.35rem}
.mundial-promo p{font-size:.82rem;color:var(--muted);margin-bottom:1rem}
.mundial-promo a{display:inline-block;background:var(--gold);color:#0a0f0a;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;padding:.5rem 1.2rem;border-radius:4px;text-decoration:none}

/* FOOTER */
footer{background:var(--dark2);border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem}
footer strong{color:var(--green)}
.empty{text-align:center;padding:3rem;color:var(--muted)}
.empty h3{font-family:'Barlow Condensed',sans-serif;font-size:1.5rem;margin-bottom:.5rem}
.divider{border:none;border-top:1px solid var(--border);margin:0}
</style>
</head>
<body>

<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina">🇦🇷 Argentina</a></li>
    <li><a href="/europa">🌍 Europa</a></li>
    <li><a href="/mundial" class="nav-mundial">🏆 Mundial 2026</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" class="nav-admin">Admin</a></li>
    <?php else: ?>
    <li><a href="/login">Ingresar</a></li>
    <?php endif; ?>
  </ul>
</nav>

<?php if (!empty($liveMatches)): ?>
<div class="live-strip">
  <div class="live-strip-inner">
    <span class="live-badge">● EN VIVO</span>
    <?php foreach ($liveMatches as $i => $m): ?>
    <?php if ($i > 0): ?><span class="live-sep">|</span><?php endif; ?>
    <a href="/<?= $m['league_key'] === 'mundial' ? 'mundial' : 'argentina' ?>" class="live-match">
      <?= htmlspecialchars($m['home']['name']) ?>
      <span class="live-score"><?= $m['home']['score'] ?? 0 ?> - <?= $m['away']['score'] ?? 0 ?></span>
      <?= htmlspecialchars($m['away']['name']) ?>
      <span class="live-clock"><?= htmlspecialchars($m['clock'] ?? '') ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php if (!empty($featured)): ?>
<div class="hero">
  <?php if ($featured['image_url']): ?>
    <img class="hero-img" src="<?= htmlspecialchars($featured['image_url']) ?>" alt="">
  <?php else: ?>
    <div class="hero-placeholder"></div>
  <?php endif; ?>
  <div class="hero-content">
    <span class="hero-cat"><?= htmlspecialchars($featured['category']) ?></span>
    <a href="/noticia?id=<?= $featured['id'] ?>">
      <h1 class="hero-title"><?= htmlspecialchars($featured['title']) ?></h1>
    </a>
    <p class="hero-meta">
      <strong><?= htmlspecialchars($featured['source_name'] ?? '') ?></strong>
      &nbsp;·&nbsp;
      <?= date('d/m/Y H:i', strtotime($featured['scraped_at'])) ?>
    </p>
  </div>
</div>
<?php endif; ?>

<div class="container">
  <div class="main-layout">

    <!-- NOTICIAS -->
    <div>
      <?php if (!empty($categories)): ?>
      <div style="padding-top:2rem">
        <div class="cats-bar">
          <a href="/" class="cat-btn active">Todas</a>
          <?php foreach ($categories as $cat): ?>
          <a href="/noticias?cat=<?= urlencode($cat) ?>" class="cat-btn"><?= htmlspecialchars($cat) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <p class="section-title">
        Últimas Noticias
        <a href="/noticias">Ver todas →</a>
      </p>

      <?php if (!empty($latest)): ?>
      <div class="news-grid">
        <?php foreach ($latest as $n): ?>
        <article class="news-card">
          <a href="/noticia?id=<?= $n['id'] ?>">
            <div class="card-img">
              <?php if ($n['image_url']): ?>
                <img src="<?= htmlspecialchars($n['image_url']) ?>" alt="" loading="lazy">
              <?php else: ?>
                <div class="card-no-img">⚽</div>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <span class="card-cat"><?= htmlspecialchars($n['category']) ?></span>
              <h2 class="card-title"><?= htmlspecialchars($n['title']) ?></h2>
              <span class="card-source"><?= htmlspecialchars($n['source_name'] ?? '') ?></span>
              <span class="card-meta"><?= date('d/m/Y H:i', strtotime($n['scraped_at'])) ?></span>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:2rem;text-align:center">
        <a href="/noticias" style="display:inline-block;background:var(--green);color:var(--dark);font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1rem;text-transform:uppercase;letter-spacing:1px;padding:.7rem 2rem;border-radius:4px;text-decoration:none">Ver todas las noticias →</a>
      </div>
      <?php else: ?>
      <div class="empty">
        <h3>No hay noticias todavía</h3>
        <p>Ejecutá <code>php scrape.php</code> para traer noticias.</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar">

      <!-- MUNDIAL PROMO -->
      <div class="mundial-promo">
        <h3>🏆 Mundial 2026</h3>
        <p>USA · México · Canadá<br>Resultados, tabla y goleadores en vivo</p>
        <a href="/mundial">Ver todo el Mundial →</a>
      </div>

      <!-- PARTIDOS EN VIVO / PRÓXIMOS -->
      <?php if (!empty($liveMatches)): ?>
      <div class="widget">
        <div class="widget-hdr">
          <span class="widget-title" style="color:var(--live)">⚡ En Vivo Ahora</span>
          <a href="/argentina" class="widget-link">Ver todo</a>
        </div>
        <?php foreach ($liveMatches as $m): ?>
        <div class="match-item">
          <div class="mi-team">
            <?php if ($m['home']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['home']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <?= htmlspecialchars($m['home']['name']) ?>
          </div>
          <div class="mi-center">
            <div class="mi-score live"><?= $m['home']['score'] ?? 0 ?> - <?= $m['away']['score'] ?? 0 ?></div>
            <div class="mi-live">● <?= htmlspecialchars($m['clock'] ?? 'LIVE') ?></div>
            <div class="mi-league"><?= $m['league_flag'] ?? '' ?> <?= htmlspecialchars($m['league_name'] ?? '') ?></div>
          </div>
          <div class="mi-team away">
            <?php if ($m['away']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['away']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <?= htmlspecialchars($m['away']['name']) ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- PRÓXIMOS PARTIDOS -->
      <?php if (!empty($upcomingMatches)): ?>
      <div class="widget">
        <div class="widget-hdr">
          <span class="widget-title">📅 Próximos Partidos</span>
          <a href="/argentina" class="widget-link">Ver calendario</a>
        </div>
        <?php foreach ($upcomingMatches as $m): ?>
        <?php $horaArg = $m['date'] ? date('d/m H:i', strtotime($m['date']) - 3*3600) : '—'; ?>
        <div class="match-item">
          <div class="mi-team">
            <?php if ($m['home']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['home']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <?= htmlspecialchars($m['home']['name']) ?>
          </div>
          <div class="mi-center">
            <div class="mi-vs">VS</div>
            <div class="mi-time"><?= $horaArg ?></div>
            <div class="mi-league"><?= $m['league_flag'] ?? '' ?> <?= htmlspecialchars($m['league_name'] ?? '') ?></div>
          </div>
          <div class="mi-team away">
            <?php if ($m['away']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['away']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <?= htmlspecialchars($m['away']['name']) ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- ÚLTIMOS RESULTADOS -->
      <?php if (!empty($recentArg)): ?>
      <div class="widget">
        <div class="widget-hdr">
          <span class="widget-title">🇦🇷 Últimos Resultados</span>
          <a href="/argentina?tab=resultados" class="widget-link">Ver más</a>
        </div>
        <?php foreach ($recentArg as $m): ?>
        <div class="match-item">
          <div class="mi-team">
            <?php if ($m['home']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['home']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <span style="<?= ($m['home']['winner'] ?? false) ? 'color:#fff;font-weight:800' : '' ?>"><?= htmlspecialchars($m['home']['name']) ?></span>
          </div>
          <div class="mi-center">
            <div class="mi-score"><?= $m['home']['score'] ?> - <?= $m['away']['score'] ?></div>
            <div class="mi-time">Final</div>
          </div>
          <div class="mi-team away">
            <?php if ($m['away']['logo']): ?><img class="mi-logo" src="<?= htmlspecialchars($m['away']['logo']) ?>" alt="" onerror="this.style.display='none'"><?php endif; ?>
            <span style="<?= ($m['away']['winner'] ?? false) ? 'color:#fff;font-weight:800' : '' ?>"><?= htmlspecialchars($m['away']['name']) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div><!-- /sidebar -->
  </div><!-- /main-layout -->
</div>

<footer>
  <strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?> · Datos: ESPN / football-data.org
</footer>
</body>
</html>
