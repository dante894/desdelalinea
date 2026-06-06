<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($article['title']) ?> — Desde la Línea</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
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
.container{max-width:800px;margin:0 auto;padding:0 1.5rem}
.breadcrumb{padding:1.5rem 0 .5rem;font-size:.8rem;color:var(--muted)}
.breadcrumb a{color:var(--green);text-decoration:none}
.article-cat{display:inline-block;background:var(--green);color:var(--dark);font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.7rem;letter-spacing:1.5px;text-transform:uppercase;padding:.25rem .7rem;border-radius:2px;margin-bottom:1rem}
.article-title{font-family:'Barlow Condensed',sans-serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;line-height:1.1;margin-bottom:1rem}
.article-meta{display:flex;align-items:center;gap:1rem;color:var(--muted);font-size:.85rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border)}
.article-meta strong{color:var(--green2)}
.article-img{width:100%;max-height:420px;object-fit:cover;border-radius:8px;margin-bottom:2rem;border:1px solid var(--border)}
.article-summary{font-size:1.1rem;color:var(--text);line-height:1.7;margin-bottom:1.5rem;font-weight:500}
.article-content{font-size:.95rem;color:#b0ccb0;line-height:1.8;margin-bottom:2rem}
.source-link{display:inline-flex;align-items:center;gap:.5rem;background:var(--card);border:1px solid var(--border);color:var(--green);padding:.6rem 1.2rem;border-radius:4px;text-decoration:none;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px;transition:all .2s;margin-bottom:3rem}
.source-link:hover{background:var(--green);color:var(--dark)}
.related-title{font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green);border-left:3px solid var(--green);padding-left:.75rem;margin-bottom:1.25rem}
.related-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:3rem}
.related-card{background:var(--card);border:1px solid var(--border);border-radius:6px;overflow:hidden;transition:border-color .2s}
.related-card:hover{border-color:var(--green)}
.related-card a{text-decoration:none;color:inherit}
.related-img{height:100px;overflow:hidden;background:var(--dark3)}
.related-img img{width:100%;height:100%;object-fit:cover}
.related-body{padding:.75rem}
.related-body h4{font-family:'Barlow Condensed',sans-serif;font-size:.95rem;font-weight:700;line-height:1.3;color:var(--text)}
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
    <li><a href="/admin" style="background:var(--green);color:var(--dark);padding:.35rem .9rem;border-radius:4px;font-weight:700">Admin</a></li>
    <?php endif; ?>
  </ul>
</nav>

<div class="container">
  <div class="breadcrumb">
    <a href="/">Inicio</a> / <a href="/noticias">Noticias</a> / <?= htmlspecialchars($article['category']) ?>
  </div>

  <span class="article-cat"><?= htmlspecialchars($article['category']) ?></span>
  <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>

  <div class="article-meta">
    <span>Fuente: <strong><?= htmlspecialchars($article['source_name'] ?? 'Desconocida') ?></strong></span>
    <span><?= date('d/m/Y H:i', strtotime($article['scraped_at'])) ?></span>
  </div>

  <?php if ($article['image_url']): ?>
  <img class="article-img" src="<?= htmlspecialchars($article['image_url']) ?>" alt="">
  <?php endif; ?>

  <?php if ($article['summary']): ?>
  <p class="article-summary"><?= htmlspecialchars($article['summary']) ?></p>
  <?php endif; ?>

  <?php if ($article['content']): ?>
  <div class="article-content"><?= nl2br(htmlspecialchars($article['content'])) ?></div>
  <?php endif; ?>

  <?php if ($article['source_url']): ?>
  <a href="<?= htmlspecialchars($article['source_url']) ?>" target="_blank" rel="noopener" class="source-link">
    🔗 Leer nota completa en <?= htmlspecialchars($article['source_name'] ?? 'la fuente') ?>
  </a>
  <?php endif; ?>

  <?php if (!empty($related)): ?>
  <p class="related-title">También te puede interesar</p>
  <div class="related-grid">
    <?php foreach ($related as $r): ?>
    <div class="related-card">
      <a href="/noticia?id=<?= $r['id'] ?>">
        <div class="related-img">
          <?php if ($r['image_url']): ?>
          <img src="<?= htmlspecialchars($r['image_url']) ?>" alt="" loading="lazy">
          <?php endif; ?>
        </div>
        <div class="related-body">
          <h4><?= htmlspecialchars($r['title']) ?></h4>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<footer><strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?></footer>
</body>
</html>
