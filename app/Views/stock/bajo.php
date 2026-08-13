<?php $content = ob_start() ?: ''; ?>

<?php if (empty($porProveedor)): ?>
  <div class="alert alert-success">No hay productos bajo el stock mínimo. 🎉</div>
<?php endif ?>

<?php foreach ($porProveedor as $proveedor => $productos): ?>
  <div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-building me-1"></i><?= esc($proveedor) ?></div>
    <div class="table-responsive">
      <table class="table table-hover mb-0 small align-middle">
        <thead class="table-light">
          <tr><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Stock</th><th class="text-center">Mínimo</th></tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $p): ?>
          <tr>
            <td><?= esc($p['descripcion']) ?></td>
            <td><?= esc($p['talle_nombre']) ?></td>
            <td><?= esc($p['color_nombre']) ?></td>
            <td class="text-center"><span class="badge bg-danger"><?= $p['stock_actual'] ?></span></td>
            <td class="text-center"><?= $p['stock_minimo'] ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endforeach ?>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Bajo stock', 'content' => $content]); ?>
