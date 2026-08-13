<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cambakey · Ingresar</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
  body{background:#1C1C1E;min-height:100vh;display:flex;align-items:center;justify-content:center}
  .card{border:none;border-radius:12px;max-width:380px;width:100%}
  .brand{text-align:center}
  .brand img{max-width:220px;width:100%;height:auto}
  .brand small{display:block;font-size:.75rem;font-weight:300;color:#888;letter-spacing:.1em;text-transform:uppercase;margin-top:6px}
  .btn-brand{background:#C08A2E;border:none;color:#fff;font-weight:600}
  .btn-brand:hover{background:#a3741f;color:#fff}
</style>
</head>
<body>
<div class="card shadow-lg p-4">
  <div class="mb-4">
    <div class="brand">
      <img src="<?= base_url('images/cambakey.png') ?>" alt="Cambakey">
      <small>Gestión de ventas</small>
    </div>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 small"><?= esc($error) ?></div>
  <?php endif ?>

  <form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
      <input type="text" name="usuario" class="form-control" placeholder="Usuario"
             value="<?= esc(old('usuario')) ?>" required autofocus>
    </div>
    <div class="mb-4">
      <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
    </div>
    <button type="submit" class="btn btn-brand w-100">Ingresar</button>
  </form>
</div>
</body>
</html>
