<?php $content = ob_start() ?: ''; ?>

<div class="d-flex justify-content-between mb-3 gap-2">
  <form method="get" class="flex-grow-1" style="max-width:300px">
    <input type="text" name="texto" class="form-control form-control-sm" placeholder="Buscar proveedor..." value="<?= esc($texto ?? '') ?>">
  </form>
  <a href="<?= site_url('admin/proveedores/nuevo') ?>" class="btn btn-sm btn-brand text-nowrap"><i class="bi bi-plus-lg me-1"></i>Nuevo</a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Nombre</th><th>Domicilio</th><th>Contacto</th><th class="text-center">Activo</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($proveedores as $p): ?>
        <tr>
          <td><?= esc($p['nombre']) ?></td>
          <td><?= esc($p['domicilio']) ?></td>
          <td><?= esc($p['contacto']) ?></td>
          <td class="text-center"><?= $p['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
          <td class="text-end">
            <a href="<?= site_url('admin/proveedores/editar/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?= site_url('admin/proveedores/eliminar/' . $p['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Dar de baja <?= esc(addslashes($p['nombre'])) ?>?')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach ?>
        <?php if (empty($proveedores)): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">Sin proveedores.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($proveedores) ?> proveedores</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Proveedores', 'content' => $content]); ?>
