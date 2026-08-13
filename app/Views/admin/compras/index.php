<?php $content = ob_start() ?: ''; ?>

<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('admin/compras/nueva') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nueva compra</a>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="desde" class="form-control form-control-sm" value="<?= esc($filtros['desde'] ?? '') ?>">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="hasta" class="form-control form-control-sm" value="<?= esc($filtros['hasta'] ?? '') ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Proveedor</label>
        <select name="proveedor_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($proveedores as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($filtros['proveedor_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Producto (base)</label>
        <select name="descripcion" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach (array_unique(array_column($productos, 'descripcion')) as $desc): ?>
            <option value="<?= esc($desc, 'attr') ?>" <?= ($filtros['descripcion'] ?? '') === $desc ? 'selected' : '' ?>><?= esc($desc) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Talle / Color</label>
        <select name="producto_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($productos as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($filtros['producto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['descripcion']) ?> - <?= esc($p['talle_nombre']) ?> - <?= esc($p['color_nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-12 col-md-2">
        <button class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-search"></i> Filtrar</button>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Unidades compradas</div>
      <div class="fs-4 fw-bold"><?= $totalUnidades ?></div>
    </div></div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Total compras</div>
      <div class="fs-4 fw-bold">$<?= number_format($totalCompras, 2) ?></div>
    </div></div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Fecha</th><th>Proveedor</th><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Total</th></tr></thead>
      <tbody>
        <?php foreach ($compras as $c): ?>
        <tr>
          <td><?= esc(date('d/m/Y H:i', strtotime($c['fecha']))) ?></td>
          <td><?= esc($c['proveedor_nombre']) ?></td>
          <td><?= esc($c['descripcion']) ?></td>
          <td><?= esc($c['talle_nombre']) ?></td>
          <td><?= esc($c['color_nombre']) ?></td>
          <td class="text-center"><?= $c['cantidad'] ?></td>
          <td class="text-end">$<?= number_format($c['precio'], 2) ?></td>
          <td class="text-end">$<?= number_format($c['cantidad'] * $c['precio'], 2) ?></td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($compras)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No hay compras registradas en este período.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($compras) ?> compras</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Compras', 'content' => $content]); ?>
