<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias — Desde la Línea</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--green2:#69f0ae;--dark:#0a0f0a;--dark2:#111711;--dark3:#1a231a;--card:#141c14;--border:#1e2e1e;--text:#e8f5e8;--muted:#6a8f6a}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif}
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none}
.nav-brand span{color:#fff}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover{color:var(--green)}
.nav-admin{background:var(--green);color:var(--dark)!important;padding:.35rem .9rem;border-radius:4px;font-weight:700!important}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
.page-header{padding:2.5rem 0 1.5rem;border-bottom:1px solid var(--border);margin-bottom:2rem}
.page-header h1{font-family:'Barlow Condensed',sans-serif;font-size:2.2rem;font-weight:900;color:#fff}
.page-header h1 span{color:var(--green)}
.cats-bar{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem}
.cat-btn{background:var(--card);border:1px solid var(--border);color:var(--muted);padding:.4rem 1rem;border-radius:20px;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;text-decoration:none;transition:all .2s}
.cat-btn:hover,.cat-btn.active{background:var(--green);border-color:var(--green);color:var(--dark)}
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s}
.news-card:hover{transform:translateY(-3px);border-color:var(--green)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}
.card-img{height:160px;background:var(--dark3);overflow:hidden}
.card-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.news-card:hover .card-img img{transform:scale(1.04)}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:1rem;flex:1;display:flex;flex-direction:column;gap:.4rem}
.card-cat{font-family:'Barlow Condensed',sans-serif;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--green)}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:700;line-height:1.3;flex:1}
.card-summary{font-size:.8rem;color:var(--muted);line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.card-meta{font-size:.75rem;color:var(--muted);margin-top:auto}
.card-source{font-size:.7rem;color:var(--green2);font-weight:600}
.pagination{display:flex;gap:.5rem;justify-content:center;padding:3rem 0}
.page-btn{background:var(--card);border:1px solid var(--border);color:var(--text);padding:.5rem 1rem;border-radius:4px;text-decoration:none;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.95rem;transition:all .2s}
.page-btn:hover,.page-btn.active{background:var(--green);border-color:var(--green);color:var(--dark)}
footer{background:var(--dark2);border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem}
footer strong{color:var(--green)}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" class="nav-admin">Admin</a></li>
    <?php else: ?>
    <li><a href="/login">Ingresar</a></li>
    <?php endif; ?>
  </ul>
</nav>
<div class="container">
  <div class="page-header">
    <h1>Todas las <span>Noticias</span></h1>
    <p style="color:var(--muted);margin-top:.4rem;font-size:.9rem"><?= $total ?> noticias encontradas</p>
  </div>

  <div class="cats-bar">
    <a href="/noticias" class="cat-btn <?= empty($category) ? 'active' : '' ?>">Todas</a>
    <?php foreach ($categories as $cat): ?>
    <a href="/noticias?cat=<?= urlencode($cat) ?>" class="cat-btn <?= $category === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="news-grid">
    <?php foreach ($news as $n): ?>
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
    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="page-btn">← Anterior</a>
    <?php endif; ?>
    <?php for ($p = max(1, $page-2); $p <= min($totalPages, $page+2); $p++): ?>
    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>" class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="page-btn">Siguiente →</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?></footer>
</body>
</html>
