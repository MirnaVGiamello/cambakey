<?php $content = ob_start() ?: ''; ?>
<div class="row g-3">
  <div class="col-6 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">Productos activos</div>
        <div class="fs-3 fw-bold"><?= $cantidadProductos ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">Bajo stock</div>
        <div class="fs-3 fw-bold <?= $bajoStockCount > 0 ? 'text-danger' : '' ?>"><?= $bajoStockCount ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">Unidades vendidas (<?= esc($periodo) ?>)</div>
        <div class="fs-3 fw-bold"><?= $unidadesVendidas ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">Total ventas (<?= esc($periodo) ?>)</div>
        <div class="fs-4 fw-bold text-success">$<?= number_format($totalVentas, 2) ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="text-muted small">Total compras (<?= esc($periodo) ?>)</div>
        <div class="fs-4 fw-bold">$<?= number_format($totalCompras, 2) ?></div>
      </div>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Dashboard', 'content' => $content]); ?>
