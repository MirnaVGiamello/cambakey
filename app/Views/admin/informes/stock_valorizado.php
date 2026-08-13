<?php $content = ob_start() ?: ''; ?>

<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
  <div class="btn-group btn-group-sm" role="group">
    <a href="<?= site_url('admin/informes/stock-valorizado?vista=producto') ?>" class="btn <?= $agrupado ? 'btn-brand' : 'btn-outline-secondary' ?>">Por producto</a>
    <a href="<?= site_url('admin/informes/stock-valorizado?vista=detalle') ?>" class="btn <?= !$agrupado ? 'btn-brand' : 'btn-outline-secondary' ?>">Detalle (talle/color)</a>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Unidades en stock</div>
      <div class="fs-4 fw-bold"><?= $totalStock ?></div>
    </div></div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Valor total (costo)</div>
      <div class="fs-4 fw-bold">$<?= number_format($total, 2) ?></div>
    </div></div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light">
        <tr>
          <th>Producto</th>
          <?php if (!$agrupado): ?><th>Talle</th><th>Color</th><?php endif ?>
          <th>Tipo</th>
          <th>Proveedor</th>
          <th class="text-center">Stock</th>
          <?php if (!$agrupado): ?><th class="text-end">Costo unit.</th><?php endif ?>
          <th class="text-end">Valor</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filas as $f): ?>
        <tr>
          <td><?= esc($f['descripcion']) ?></td>
          <?php if (!$agrupado): ?>
            <td><?= esc($f['talle_nombre']) ?></td>
            <td><?= esc($f['color_nombre']) ?></td>
          <?php endif ?>
          <td><?= esc($f['tipo_nombre'] ?? '') ?></td>
          <td><?= esc($f['proveedor_nombre']) ?></td>
          <td class="text-center"><?= $f['stock_actual'] ?></td>
          <?php if (!$agrupado): ?><td class="text-end">$<?= number_format($f['costo'], 2) ?></td><?php endif ?>
          <td class="text-end">$<?= number_format($f['valor'], 2) ?></td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($filas)): ?>
        <tr><td colspan="<?= $agrupado ? 5 : 8 ?>" class="text-center text-muted py-4">No hay productos en stock.</td></tr>
        <?php endif ?>
      </tbody>
      <tfoot>
        <tr class="table-light fw-semibold">
          <td colspan="<?= $agrupado ? 3 : 5 ?>" class="text-end">Total</td>
          <td class="text-center"><?= $totalStock ?></td>
          <?php if (!$agrupado): ?><td></td><?php endif ?>
          <td class="text-end">$<?= number_format($total, 2) ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Stock valorizado', 'content' => $content]); ?>
