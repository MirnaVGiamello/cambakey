<?php $content = ob_start() ?: ''; ?>
<div style="max-width:400px">
  <a href="<?= site_url('admin/gastos') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('admin/gastos/guardar') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Tipo de gasto <span class="text-danger">*</span></label>
          <select name="tipo_gasto_id" class="form-select" required autofocus>
            <option value="">Seleccionar...</option>
            <?php foreach ($tipos as $t): ?>
              <option value="<?= $t['id'] ?>" <?= old('tipo_gasto_id') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
          <input type="date" name="fecha" class="form-control" value="<?= esc(old('fecha') ?: date('Y-m-d')) ?>" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Importe <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="text" inputmode="decimal" name="importe" class="form-control money-input" value="<?= esc(old('importe')) ?>" required>
          </div>
        </div>

        <button type="submit" class="btn btn-brand w-100">Registrar gasto</button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Nuevo gasto', 'content' => $content]); ?>
