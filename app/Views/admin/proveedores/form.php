<?php $content = ob_start() ?: ''; $esNuevo = $proveedor === null; ?>
<div style="max-width:480px">
  <a href="<?= site_url('admin/proveedores') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= $esNuevo ? site_url('admin/proveedores/guardar') : site_url('admin/proveedores/actualizar/' . $proveedor['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control" value="<?= esc($proveedor['nombre'] ?? old('nombre')) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Domicilio</label>
          <input type="text" name="domicilio" class="form-control" value="<?= esc($proveedor['domicilio'] ?? old('domicilio')) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Contacto</label>
          <input type="text" name="contacto" class="form-control" value="<?= esc($proveedor['contacto'] ?? old('contacto')) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Observación</label>
          <textarea name="observacion" class="form-control" rows="2"><?= esc($proveedor['observacion'] ?? '') ?></textarea>
        </div>
        <?php if (!$esNuevo): ?>
        <div class="mb-4 form-check">
          <input class="form-check-input" type="checkbox" name="activo" value="1" id="chkActivo" <?= $proveedor['activo'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="chkActivo">Activo</label>
        </div>
        <?php endif ?>
        <button type="submit" class="btn btn-brand w-100"><?= $esNuevo ? 'Crear proveedor' : 'Guardar' ?></button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => $accion, 'content' => $content]); ?>
