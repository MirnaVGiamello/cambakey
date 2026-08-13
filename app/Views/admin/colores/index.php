<?php $content = ob_start() ?: ''; ?>
<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('admin/colores/nuevo') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nuevo color</a>
</div>
<div class="card shadow-sm border-0" style="max-width:400px">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small">
      <thead class="table-light"><tr><th>Color</th><th class="text-center">Activo</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($colores as $c): ?>
        <tr>
          <td><?= esc($c['nombre']) ?></td>
          <td class="text-center"><?= $c['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
          <td class="text-end">
            <a href="<?= site_url('admin/colores/editar/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?= site_url('admin/colores/eliminar/' . $c['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Eliminar <?= esc(addslashes($c['nombre'])) ?>?')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($colores) ?> colores</div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Colores', 'content' => $content]); ?>
