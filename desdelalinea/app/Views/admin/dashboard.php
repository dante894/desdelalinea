<?php if (empty($_SESSION['user_id'])) { header('Location: /login'); exit; } ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--dark:#0a0f0a;--dark2:#111711;--dark3:#1a231a;--card:#141c14;--border:#1e2e1e;--text:#e8f5e8;--muted:#6a8f6a;--red:#ef5350}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.95);border-bottom:1px solid var(--border)}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.5rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-right{display:flex;align-items:center;gap:1rem}
.nav-right a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px}
.nav-right a:hover{color:var(--green)}
.user-badge{background:var(--card);border:1px solid var(--border);padding:.3rem .8rem;border-radius:4px;font-size:.8rem;color:var(--green)}
.container{max-width:1200px;margin:0 auto;padding:2rem 1.5rem}
.page-title{font-family:'Barlow Condensed',sans-serif;font-size:2rem;font-weight:900;margin-bottom:2rem}
.page-title span{color:var(--green)}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem}
.stat-card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1.25rem 1.5rem}
.stat-label{font-size:.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:.4rem}
.stat-value{font-family:'Barlow Condensed',sans-serif;font-size:2.2rem;font-weight:900;color:var(--green)}
.stat-sub{font-size:.75rem;color:var(--muted);margin-top:.25rem}

/* Scraper box */
.scraper-box{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1.5rem;margin-bottom:2rem}
.scraper-box h2{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green);margin-bottom:1rem}
.scrape-btn{background:var(--green);color:var(--dark);border:none;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1rem;text-transform:uppercase;letter-spacing:.5px;padding:.7rem 2rem;border-radius:4px;cursor:pointer;transition:opacity .2s}
.scrape-btn:hover{opacity:.85}
.scrape-result{margin-top:1rem;background:var(--dark3);border-radius:6px;padding:1rem;font-size:.85rem;font-family:monospace;white-space:pre-wrap;color:#b0ccb0;max-height:200px;overflow-y:auto;border:1px solid var(--border)}
.result-new{color:var(--green);font-weight:700}
.result-err{color:var(--red)}

/* Sources */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem}
@media(max-width:700px){.two-col{grid-template-columns:1fr}}
.panel{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1.25rem}
.panel h2{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green);margin-bottom:1rem;border-left:3px solid var(--green);padding-left:.6rem}
.source-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid var(--border);font-size:.85rem}
.source-row:last-child{border-bottom:none}
.source-cnt{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.1rem;color:var(--green)}

/* Table */
.table-section{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden}
.table-header{display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border)}
.table-header h2{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green)}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.85rem}
th{background:var(--dark3);padding:.75rem 1rem;text-align:left;font-family:'Barlow Condensed',sans-serif;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);white-space:nowrap}
td{padding:.75rem 1rem;border-bottom:1px solid var(--border);color:var(--text)}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(0,200,83,.04)}
.td-title{max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.badge{display:inline-block;background:var(--dark3);border:1px solid var(--border);color:var(--muted);font-size:.65rem;padding:.15rem .5rem;border-radius:3px;font-family:'Barlow Condensed',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
.badge-green{border-color:var(--green);color:var(--green)}
.view-link{color:var(--green);text-decoration:none;font-size:.75rem;font-weight:600}
.view-link:hover{text-decoration:underline}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <div class="nav-right">
    <span class="user-badge">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
    <a href="/">Ver sitio</a>
    <a href="/logout">Salir</a>
  </div>
</nav>

<div class="container">
  <h1 class="page-title">Panel <span>Admin</span></h1>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-label">Total noticias</div>
      <div class="stat-value"><?= number_format($totalNews) ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Hoy</div>
      <div class="stat-value"><?= $todayNews ?></div>
      <div class="stat-sub">noticias scrapeadas</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Usuarios</div>
      <div class="stat-value"><?= $totalUsers ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Fuentes activas</div>
      <div class="stat-value"><?= count($sources) ?></div>
    </div>
  </div>

  <!-- Scraper -->
  <div class="scraper-box">
    <h2>🕷 Scraper de Noticias</h2>
    <p style="color:var(--muted);font-size:.85rem;margin-bottom:1rem">Trae noticias de Infobae, TyC Sports, Ole, ESPN y Marca. También podés correr <code style="background:var(--dark3);padding:.1rem .4rem;border-radius:3px;color:var(--green)">php scrape.php</code> por cron.</p>
    <form method="POST" action="/admin/scrape" onsubmit="this.querySelector('button').textContent='Scrapeando…';this.querySelector('button').disabled=true">
      <button class="scrape-btn" type="submit">▶ Ejecutar Scraper Ahora</button>
    </form>

    <?php
    $sr = $_SESSION['scrape_result'] ?? null;
    unset($_SESSION['scrape_result']);
    if ($sr): ?>
    <div class="scrape-result">
<?php foreach ($sr['log'] as $l) echo htmlspecialchars($l) . "\n"; ?>

<span class="result-new">✨ Nuevas noticias guardadas: <?= $sr['new'] ?> / <?= $sr['total'] ?> procesadas</span>
<?php if ($sr['errors']): ?>
<?php foreach ($sr['errors'] as $e): ?><span class="result-err">⚠ <?= htmlspecialchars($e) ?></span>
<?php endforeach; endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Sources & Categories -->
  <div class="two-col">
    <div class="panel">
      <h2>Fuentes</h2>
      <?php foreach ($sources as $s): ?>
      <div class="source-row">
        <span><?= htmlspecialchars($s['source_name']) ?></span>
        <span class="source-cnt"><?= $s['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="panel">
      <h2>Categorías</h2>
      <?php foreach ($categories as $c): ?>
      <div class="source-row">
        <a href="/noticias?cat=<?= urlencode($c['category']) ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($c['category']) ?></a>
        <span class="source-cnt"><?= $c['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- News table -->
  <div class="table-section">
    <div class="table-header">
      <h2>Últimas Noticias Scrapeadas</h2>
      <a href="/noticias" style="color:var(--green);font-size:.85rem;font-weight:600;text-decoration:none">Ver todas →</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Título</th>
            <th>Fuente</th>
            <th>Categoría</th>
            <th>Fecha</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($latestNews as $n): ?>
          <tr>
            <td style="color:var(--muted)"><?= $n['id'] ?></td>
            <td class="td-title" title="<?= htmlspecialchars($n['title']) ?>"><?= htmlspecialchars($n['title']) ?></td>
            <td><span class="badge badge-green"><?= htmlspecialchars($n['source_name'] ?? '—') ?></span></td>
            <td><span class="badge"><?= htmlspecialchars($n['category']) ?></span></td>
            <td style="color:var(--muted);white-space:nowrap"><?= date('d/m H:i', strtotime($n['scraped_at'])) ?></td>
            <td><a href="/noticia?id=<?= $n['id'] ?>" class="view-link" target="_blank">Ver →</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
