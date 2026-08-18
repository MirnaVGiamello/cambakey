<?php $content = ob_start() ?: ''; ?>

<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('ventas/nueva') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nueva venta</a>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="desde" class="form-control form-control-sm" value="<?= esc($filtros['desde'] ?? '') ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="hasta" class="form-control form-control-sm" value="<?= esc($filtros['hasta'] ?? '') ?>">
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
      <div class="text-muted small">Unidades vendidas</div>
      <div class="fs-4 fw-bold"><?= $totalUnidades ?></div>
    </div></div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Total ventas</div>
      <div class="fs-4 fw-bold text-success">$<?= number_format($totalVentas, 2) ?></div>
    </div></div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-header bg-white fw-semibold">Detalle</div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <?php $esAdmin = session()->get('perfil') === 'admin'; ?>
      <thead class="table-light"><tr><th>ID</th><th>Fecha</th><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Total</th><?php if ($esAdmin): ?><th></th><?php endif ?></tr></thead>
      <tbody>
        <?php foreach ($ventas as $v): ?>
        <tr>
          <td><?= $v['id'] ?></td>
          <td><?= esc(date('d/m/Y H:i', strtotime($v['fecha']))) ?></td>
          <td><?= esc($v['descripcion']) ?></td>
          <td><?= esc($v['talle_nombre']) ?></td>
          <td><?= esc($v['color_nombre']) ?></td>
          <td class="text-center"><?= $v['cantidad'] ?></td>
          <td class="text-end">$<?= number_format($v['precio'], 2) ?></td>
          <td class="text-end">$<?= number_format($v['cantidad'] * $v['precio'], 2) ?></td>
          <?php if ($esAdmin): ?>
          <td class="text-end">
            <form method="post" action="<?= site_url('ventas/eliminar/' . $v['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Eliminar esta venta? Se repondrá el stock.')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
          <?php endif ?>
        </tr>
        <?php endforeach ?>
        <?php if (empty($ventas)): ?>
        <tr><td colspan="<?= $esAdmin ? 9 : 8 ?>" class="text-center text-muted py-4">No hay ventas registradas en este período.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white fw-semibold">Resumen por producto</div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Unidades</th><th class="text-end">Total</th></tr></thead>
      <tbody>
        <?php foreach ($resumen as $r): ?>
        <tr>
          <td><?= esc($r['descripcion']) ?></td>
          <td><?= esc($r['talle_nombre']) ?></td>
          <td><?= esc($r['color_nombre']) ?></td>
          <td class="text-center"><?= $r['unidades'] ?></td>
          <td class="text-end">$<?= number_format($r['total'], 2) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Ventas', 'content' => $content]); ?>
