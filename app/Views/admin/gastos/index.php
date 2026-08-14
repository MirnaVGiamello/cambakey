<?php $content = ob_start() ?: ''; ?>

<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('admin/gastos/nuevo') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nuevo gasto</a>
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
      <div class="col-6 col-md-4">
        <label class="form-label small mb-1">Tipo de gasto</label>
        <select name="tipo_gasto_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($filtros['tipo_gasto_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <button class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-search"></i> Filtrar</button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0 mb-3" style="max-width:300px">
  <div class="card-body">
    <div class="text-muted small">Total del período</div>
    <div class="fs-4 fw-bold">$<?= number_format($total, 2) ?></div>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Fecha</th><th>Tipo de gasto</th><th class="text-end">Importe</th></tr></thead>
      <tbody>
        <?php foreach ($gastos as $g): ?>
        <tr>
          <td><?= esc(date('d/m/Y', strtotime($g['fecha']))) ?></td>
          <td><?= esc($g['tipo_nombre']) ?></td>
          <td class="text-end">$<?= number_format($g['importe'], 2) ?></td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($gastos)): ?>
        <tr><td colspan="3" class="text-center text-muted py-4">No hay gastos registrados en este período.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($gastos) ?> gastos</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Gastos', 'content' => $content]); ?>
