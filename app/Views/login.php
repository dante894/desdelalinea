<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ingresar — Desde la Línea</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--green:#00c853;--dark:#0a0f0a;--card:#141c14;--border:#1e2e1e;--text:#e8f5e8;--muted:#6a8f6a}
body{background:var(--dark);color:var(--text);font-family:'Barlow',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center}
.box{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:2.5rem;width:100%;max-width:380px}
.brand{font-family:'Barlow Condensed',sans-serif;font-size:1.8rem;font-weight:900;color:var(--green);text-align:center;margin-bottom:.25rem}
.brand span{color:#fff}
.sub{text-align:center;color:var(--muted);font-size:.85rem;margin-bottom:2rem}
.form-group{margin-bottom:1rem}
label{display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:.4rem;font-weight:600}
input{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:6px;padding:.75rem 1rem;color:var(--text);font-size:.95rem;font-family:'Barlow',sans-serif;transition:border-color .2s;outline:none}
input:focus{border-color:var(--green)}
.btn{width:100%;background:var(--green);color:#0a0f0a;border:none;border-radius:6px;padding:.85rem;font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.1rem;text-transform:uppercase;letter-spacing:.5px;cursor:pointer;margin-top:.5rem;transition:opacity .2s}
.btn:hover{opacity:.85}
.error{background:rgba(239,83,80,.1);border:1px solid rgba(239,83,80,.3);border-radius:6px;padding:.75rem;color:#ef9a9a;font-size:.85rem;margin-bottom:1rem;text-align:center}
.back{display:block;text-align:center;margin-top:1.25rem;color:var(--muted);font-size:.85rem;text-decoration:none}
.back:hover{color:var(--green)}
</style>
</head>
<body>
<div class="box">
  <div class="brand">DESDE<span>LA</span>LÍNEA</div>
  <p class="sub">Panel de administración</p>
  <?php if (!empty($_SESSION['error'])): ?>
  <div class="error"><?= htmlspecialchars($_SESSION['error']) ?><?php unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <form method="POST" action="/login">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" placeholder="admin@desdelalinea.com" required autofocus>
    </div>
    <div class="form-group">
      <label>Contraseña</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>
    <button class="btn" type="submit">Ingresar</button>
  </form>
  <a href="/" class="back">← Volver al portal</a>
</div>
</body>
</html>
