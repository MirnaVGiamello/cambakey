<?php $content = ob_start() ?: ''; ?>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Tipo</label>
        <select name="tipo" class="form-select form-select-sm">
          <option value="">Todos</option>
          <option value="compra" <?= ($filtros['tipo'] ?? '') === 'compra' ? 'selected' : '' ?>>Compra</option>
          <option value="venta"  <?= ($filtros['tipo'] ?? '') === 'venta'  ? 'selected' : '' ?>>Venta</option>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="desde" class="form-control form-control-sm" value="<?= esc($filtros['desde'] ?? '') ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="hasta" class="form-control form-control-sm" value="<?= esc($filtros['hasta'] ?? '') ?>">
      </div>
      <div class="col-12 col-md-2">
        <button class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-search"></i> Filtrar</button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light">
        <tr>
          <th>Tipo</th><th>Fecha original</th><th>Producto</th><th>Talle</th><th>Color</th>
          <th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Total</th>
          <th>Cargado por</th><th>Eliminado por</th><th>Eliminado el</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($eliminaciones as $e): ?>
        <tr>
          <td><?= $e['tipo'] === 'compra' ? '<span class="badge bg-info">Compra</span>' : '<span class="badge bg-success">Venta</span>' ?></td>
          <td><?= esc(date('d/m/Y H:i', strtotime($e['fecha']))) ?></td>
          <td><?= esc($e['descripcion']) ?></td>
          <td><?= esc($e['talle_nombre']) ?></td>
          <td><?= esc($e['color_nombre']) ?></td>
          <td class="text-center"><?= $e['cantidad'] ?></td>
          <td class="text-end">$<?= number_format($e['precio'], 2) ?></td>
          <td class="text-end">$<?= number_format($e['cantidad'] * $e['precio'], 2) ?></td>
          <td><?= esc($e['cargado_por_nombre'] ?? '—') ?></td>
          <td><?= esc($e['eliminado_por_nombre'] ?? '—') ?></td>
          <td><?= esc(date('d/m/Y H:i', strtotime($e['eliminado_en']))) ?></td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($eliminaciones)): ?>
        <tr><td colspan="11" class="text-center text-muted py-4">No hay compras ni ventas eliminadas en este período.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($eliminaciones) ?> registros</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Eliminados', 'content' => $content]); ?>
