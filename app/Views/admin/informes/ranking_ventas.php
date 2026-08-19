<?php $content = ob_start() ?: ''; ?>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="desde" class="form-control form-control-sm" value="<?= esc($filtros['desde']) ?>">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="hasta" class="form-control form-control-sm" value="<?= esc($filtros['hasta']) ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1 d-block">Agrupar por</label>
        <div class="btn-group btn-group-sm w-100" role="group">
          <input type="radio" class="btn-check" name="vista" id="vistaAgrupado" value="agrupado" autocomplete="off" <?= $vista === 'agrupado' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="vistaAgrupado">Producto</label>
          <input type="radio" class="btn-check" name="vista" id="vistaDetalle" value="detalle" autocomplete="off" <?= $vista === 'detalle' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="vistaDetalle">Talle/color</label>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1 d-block">Ordenar por</label>
        <div class="btn-group btn-group-sm w-100" role="group">
          <input type="radio" class="btn-check" name="medida" id="medidaCantidad" value="cantidad" autocomplete="off" <?= $medida === 'cantidad' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="medidaCantidad">Unidades</label>
          <input type="radio" class="btn-check" name="medida" id="medidaPesos" value="pesos" autocomplete="off" <?= $medida === 'pesos' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="medidaPesos">Pesos</label>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <button class="btn btn-sm btn-brand w-100"><i class="bi bi-search me-1"></i>Ver</button>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Unidades vendidas</div>
      <div class="fs-4 fw-bold"><?= number_format($totalUnidades, 0) ?></div>
    </div></div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Total ventas</div>
      <div class="fs-4 fw-bold text-success">$<?= number_format($totalPesos, 2) ?></div>
    </div></div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white fw-semibold">
    Ranking por <?= $medida === 'pesos' ? 'pesos' : 'unidades' ?> — <?= $vista === 'detalle' ? 'talle/color' : 'producto' ?>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:48px">#</th>
          <th>Producto</th>
          <?php if ($vista === 'detalle'): ?><th>Talle</th><th>Color</th><?php endif ?>
          <th class="text-center">Unidades</th>
          <th class="text-end">Total</th>
          <th style="width:160px">% del total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filas as $f): ?>
        <tr>
          <td>
            <?php if ($f['puesto'] <= 3): ?>
              <span class="badge bg-warning text-dark">#<?= $f['puesto'] ?></span>
            <?php else: ?>
              <span class="text-muted">#<?= $f['puesto'] ?></span>
            <?php endif ?>
          </td>
          <td><?= esc($f['descripcion']) ?></td>
          <?php if ($vista === 'detalle'): ?>
            <td><?= esc($f['talle_nombre']) ?></td>
            <td><?= esc($f['color_nombre']) ?></td>
          <?php endif ?>
          <td class="text-center"><?= number_format($f['unidades'], 0) ?></td>
          <td class="text-end">$<?= number_format($f['total'], 2) ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="flex-grow-1 rounded" style="height:8px;background:rgba(0,0,0,.08)">
                <div style="height:8px;border-radius:4px;background:var(--accent);width:<?= number_format($f['porcentaje'], 1) ?>%"></div>
              </div>
              <span class="text-muted small text-nowrap"><?= number_format($f['porcentaje'], 1) ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($filas)): ?>
        <tr><td colspan="<?= $vista === 'detalle' ? 7 : 5 ?>" class="text-center text-muted py-4">No hay ventas en este período.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($filas) ?> productos</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Ranking de ventas', 'content' => $content]); ?>
