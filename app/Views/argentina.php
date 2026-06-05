<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Deportes Argentina — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--celeste:#74b9ff;--dark:#0a0f0a;--dark2:#111711;--dark3:#0a1525;--card:#0f1c2e;--border:#1a2e4a;--text:#e8f5e8;--muted:#6a8f6a}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.95);border-bottom:1px solid #1e2e1e;position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--green)}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}

.arg-hero{background:linear-gradient(135deg,#0a0f1a,#0d1f3a,#0a1525);border-bottom:3px solid var(--celeste);padding:2.5rem 0;position:relative;overflow:hidden}
.arg-hero::before{content:'🇦🇷';position:absolute;right:3rem;top:50%;transform:translateY(-50%);font-size:8rem;opacity:.08}
.arg-hero h1{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2.5rem,5vw,4rem);font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:2px}
.arg-hero h1 span{color:var(--celeste)}
.arg-hero p{color:#6a9fbf;margin-top:.5rem}

.section{padding:2.5rem 0}
.section-title{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--celeste);border-left:3px solid var(--celeste);padding-left:.75rem;margin-bottom:1.5rem}
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.news-card:hover{transform:translateY(-3px);border-color:var(--celeste)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column}
.card-img{height:160px;background:var(--dark3);overflow:hidden}
.card-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.news-card:hover .card-img img{transform:scale(1.04)}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:1rem;flex:1;display:flex;flex-direction:column;gap:.4rem}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:700;line-height:1.3;flex:1;color:#fff}
.card-summary{font-size:.8rem;color:#6a9fbf;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.card-meta{font-size:.72rem;color:#4a7a9f;margin-top:auto}
.card-source{font-size:.7rem;color:var(--celeste);font-weight:600}
.pagination{display:flex;gap:.5rem;justify-content:center;padding:3rem 0}
.page-btn{background:var(--card);border:1px solid var(--border);color:var(--text);padding:.5rem 1rem;border-radius:4px;text-decoration:none;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.95rem;transition:all .2s}
.page-btn:hover,.page-btn.active{background:var(--celeste);border-color:var(--celeste);color:#0a0f1a}
.empty{text-align:center;padding:4rem;color:var(--muted)}
footer{background:#070d0f;border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem}
footer strong{color:var(--green)}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina" class="active">🇦🇷 Argentina</a></li>
    <li><a href="/mundial">🏆 Mundial</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" style="background:var(--green);color:var(--dark);padding:.3rem .8rem;border-radius:4px">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<div class="arg-hero">
  <div class="container">
    <h1>🇦🇷 Deportes <span>Argentina</span></h1>
    <p>Fútbol, selección, Boca, River y todo el deporte argentino</p>
  </div>
</div>

<div class="container">
  <div class="section">
    <p class="section-title">Últimas noticias (<?= $total ?>)</p>
    <?php if (!empty($news)): ?>
    <div class="news-grid">
      <?php foreach ($news as $n): ?>
      <article class="news-card">
        <a href="/noticia?id=<?= $n['id'] ?>">
          <div class="card-img">
            <?php if ($n['image_url']): ?>
              <img src="<?= htmlspecialchars($n['image_url']) ?>" alt="" loading="lazy">
            <?php else: ?>
              <div class="card-no-img">🇦🇷</div>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($n['title']) ?></h2>
            <?php if ($n['summary']): ?>
            <p class="card-summary"><?= htmlspecialchars($n['summary']) ?></p>
            <?php endif; ?>
            <span class="card-source"><?= htmlspecialchars($n['source_name'] ?? '') ?></span>
            <span class="card-meta"><?= date('d/m/Y H:i', strtotime($n['scraped_at'])) ?></span>
          </div>
        </a>
      </article>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
      <a href="?page=<?= $page-1 ?>" class="page-btn">← Anterior</a>
      <?php endif; ?>
      <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="?page=<?= $p ?>" class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page+1 ?>" class="page-btn">Siguiente →</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="empty">
      <h3>No hay noticias argentinas todavía</h3>
      <p>Ejecutá el scraper desde el admin para traer noticias.</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?></footer>
</body>
</html>
