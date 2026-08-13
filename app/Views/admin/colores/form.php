<?php $content = ob_start() ?: ''; $esNuevo = $color === null; ?>
<div style="max-width:400px">
  <a href="<?= site_url('admin/colores') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= $esNuevo ? site_url('admin/colores/guardar') : site_url('admin/colores/actualizar/' . $color['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control" value="<?= esc($color['nombre'] ?? old('nombre')) ?>" required autofocus>
        </div>
        <?php if (!$esNuevo): ?>
        <div class="mb-4 form-check">
          <input class="form-check-input" type="checkbox" name="activo" value="1" id="chkActivo" <?= $color['activo'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="chkActivo">Activo</label>
        </div>
        <?php endif ?>
        <button type="submit" class="btn btn-brand w-100"><?= $esNuevo ? 'Crear' : 'Guardar' ?></button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => $accion, 'content' => $content]); ?>
