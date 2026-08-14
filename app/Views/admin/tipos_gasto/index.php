<?php $content = ob_start() ?: ''; ?>
<div class="d-flex justify-content-end mb-3">
  <a href="<?= site_url('admin/tipos-gasto/nuevo') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nuevo tipo</a>
</div>
<div class="card shadow-sm border-0" style="max-width:500px">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small">
      <thead class="table-light"><tr><th>Tipo de gasto</th><th class="text-center">Activo</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($tipos as $t): ?>
        <tr>
          <td><?= esc($t['nombre']) ?></td>
          <td class="text-center"><?= $t['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
          <td class="text-end">
            <a href="<?= site_url('admin/tipos-gasto/editar/' . $t['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?= site_url('admin/tipos-gasto/eliminar/' . $t['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Eliminar <?= esc(addslashes($t['nombre'])) ?>?')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($tipos) ?> tipos de gasto</div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Tipos de gasto', 'content' => $content]); ?>
