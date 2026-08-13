<?php $content = ob_start() ?: ''; $esNuevo = $usuario === null; ?>
<div style="max-width:420px">
  <a href="<?= site_url('admin/usuarios') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= $esNuevo ? site_url('admin/usuarios/guardar') : site_url('admin/usuarios/actualizar/' . $usuario['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Usuario <span class="text-danger">*</span></label>
          <input type="text" name="usuario" class="form-control" value="<?= esc($usuario['usuario'] ?? old('usuario')) ?>" <?= $esNuevo ? 'required' : 'disabled' ?>>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control" value="<?= esc($usuario['nombre'] ?? old('nombre')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Perfil <span class="text-danger">*</span></label>
          <select name="perfil" class="form-select" required>
            <option value="ventas" <?= ($usuario['perfil'] ?? '') === 'ventas' ? 'selected' : '' ?>>Ventas</option>
            <option value="admin" <?= ($usuario['perfil'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Contraseña <?= $esNuevo ? '<span class="text-danger">*</span>' : '' ?></label>
          <input type="password" name="password" class="form-control" <?= $esNuevo ? 'required' : '' ?> placeholder="<?= $esNuevo ? '' : 'Dejar en blanco para no cambiar' ?>">
        </div>

        <?php if (!$esNuevo): ?>
        <div class="mb-4 form-check">
          <input class="form-check-input" type="checkbox" name="activo" value="1" id="chkActivo" <?= $usuario['activo'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="chkActivo">Activo</label>
        </div>
        <?php endif ?>

        <button type="submit" class="btn btn-brand w-100"><?= $esNuevo ? 'Crear usuario' : 'Guardar' ?></button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => $accion, 'content' => $content]); ?>
