<?php $content = ob_start() ?: ''; ?>
<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('admin/usuarios/nuevo') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nuevo usuario</a>
</div>
<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Usuario</th><th>Nombre</th><th>Perfil</th><th class="text-center">Activo</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= esc($u['usuario']) ?></td>
          <td><?= esc($u['nombre']) ?></td>
          <td><span class="badge bg-secondary"><?= esc($u['perfil']) ?></span></td>
          <td class="text-center"><?= $u['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
          <td class="text-end">
            <a href="<?= site_url('admin/usuarios/editar/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?= site_url('admin/usuarios/eliminar/' . $u['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Dar de baja a <?= esc(addslashes($u['nombre'])) ?>?')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($usuarios) ?> usuarios</div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Usuarios', 'content' => $content]); ?>
