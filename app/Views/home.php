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
}
html{scroll-behavior:smooth}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh}

/* NAV */
nav{display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:60px;background:rgba(10,15,10,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100}
.nav-brand{font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:900;color:var(--green);text-decoration:none;letter-spacing:-.5px}
.nav-brand span{color:var(--white)}
.nav-links{display:flex;gap:1.5rem;list-style:none}
.nav-links a{color:var(--muted);text-decoration:none;font-size:.9rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px;transition:color .2s}
.nav-links a:hover{color:var(--green)}
.nav-admin{background:var(--green);color:var(--dark)!important;padding:.35rem .9rem;border-radius:4px;font-weight:700!important}

/* HERO */
.hero{position:relative;height:520px;overflow:hidden;border-bottom:2px solid var(--green)}
.hero-img{width:100%;height:100%;object-fit:cover;filter:brightness(.35)}
.hero-placeholder{width:100%;height:100%;background:linear-gradient(135deg,#0d1f0d 0%,#0a2a0a 50%,#051505 100%)}
.hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:2.5rem 3rem}
.hero-cat{display:inline-block;background:var(--green);color:var(--dark);font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.75rem;letter-spacing:1.5px;text-transform:uppercase;padding:.25rem .7rem;border-radius:2px;margin-bottom:.75rem}
.hero-title{font-family:'Barlow Condensed',sans-serif;font-size:clamp(2rem,4vw,3.2rem);font-weight:900;line-height:1.05;max-width:700px;margin-bottom:.75rem}
.hero-meta{color:var(--muted);font-size:.85rem}
.hero-meta strong{color:var(--green2)}
.hero a{text-decoration:none;color:inherit}

/* GRID */
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
.section{padding:3rem 0}
.section-title{font-family:'Barlow Condensed',sans-serif;font-size:1.3rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--green);border-left:3px solid var(--green);padding-left:.75rem;margin-bottom:1.5rem}
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem}
.news-card{background:var(--card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:transform .2s,border-color .2s;display:flex;flex-direction:column}
.news-card:hover{transform:translateY(-3px);border-color:var(--green)}
.news-card a{text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}
.card-img{height:160px;overflow:hidden;background:var(--dark3);position:relative}
.card-img img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.news-card:hover .card-img img{transform:scale(1.04)}
.card-no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.card-body{padding:1rem;flex:1;display:flex;flex-direction:column;gap:.5rem}
.card-cat{font-family:'Barlow Condensed',sans-serif;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--green)}
.card-title{font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:700;line-height:1.3;flex:1}
.card-meta{font-size:.75rem;color:var(--muted);margin-top:auto}
.card-source{font-size:.7rem;color:var(--green2);font-weight:600}

/* CATEGORIES BAR */
.cats-bar{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem}
.cat-btn{background:var(--card);border:1px solid var(--border);color:var(--muted);padding:.4rem 1rem;border-radius:20px;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;text-decoration:none;transition:all .2s}
.cat-btn:hover,.cat-btn.active{background:var(--green);border-color:var(--green);color:var(--dark)}

/* FOOTER */
footer{background:var(--dark2);border-top:1px solid var(--border);padding:2rem;text-align:center;color:var(--muted);font-size:.85rem}
footer strong{color:var(--green)}

.empty{text-align:center;padding:4rem;color:var(--muted)}
.empty h3{font-family:'Barlow Condensed',sans-serif;font-size:1.5rem;margin-bottom:.5rem}
</style>
</head>
<body>

<nav>
  <a href="/" class="nav-brand">DESDE<span>LA</span>LÍNEA</a>
  <ul class="nav-links">
    <li><a href="/">Inicio</a></li>
    <li><a href="/argentina">🇦🇷 Argentina</a></li>
    <li><a href="/europa">🌍 Europa</a></li>
    <li><a href="/mundial">🏆 Mundial</a></li>
    <li><a href="/noticias">Noticias</a></li>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <li><a href="/admin" class="nav-admin">Admin</a></li>
    <?php else: ?>
    <li><a href="/login">Ingresar</a></li>
    <?php endif; ?>
  </ul>
</nav>

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
  <div class="section">
    <?php if (!empty($categories)): ?>
    <div class="cats-bar">
      <a href="/" class="cat-btn active">Todas</a>
      <?php foreach ($categories as $cat): ?>
      <a href="/noticias?cat=<?= urlencode($cat) ?>" class="cat-btn"><?= htmlspecialchars($cat) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($latest)): ?>
    <p class="section-title">Últimas Noticias</p>
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
      <p>Ejecutá <code>php scrape.php</code> o usá el panel admin para traer noticias.</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<footer>
  <strong>Desde la Línea</strong> · Portal Deportivo · <?= date('Y') ?>
</footer>
</body>
</html>
