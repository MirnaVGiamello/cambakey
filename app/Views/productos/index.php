<?php $content = ob_start() ?: ''; $esAdmin = session()->get('perfil') === 'admin'; ?>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label small mb-1">Buscar</label>
        <input type="text" name="texto" class="form-control form-control-sm" value="<?= esc($filtros['texto'] ?? '') ?>" placeholder="Descripción...">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Tipo</label>
        <select name="tipo_producto_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($filtros['tipo_producto_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Proveedor</label>
        <select name="proveedor_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($proveedores as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($filtros['proveedor_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-4 col-md-1">
        <label class="form-label small mb-1">Talle</label>
        <select name="talle_id" class="form-select form-select-sm">
          <option value="">-</option>
          <?php foreach ($talles as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($filtros['talle_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-4 col-md-2">
        <label class="form-label small mb-1">Color</label>
        <select name="color_id" class="form-select form-select-sm">
          <option value="">Todos</option>
          <?php foreach ($colores as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($filtros['color_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-4 col-md-1">
        <label class="form-label small mb-1">Stock</label>
        <select name="stock" class="form-select form-select-sm">
          <option value="">Todos</option>
          <option value="bajo" <?= ($filtros['stock'] ?? '') === 'bajo' ? 'selected' : '' ?>>Bajo</option>
          <option value="ok" <?= ($filtros['stock'] ?? '') === 'ok' ? 'selected' : '' ?>>OK</option>
        </select>
      </div>
      <div class="col-12 col-md-1">
        <button class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-search"></i></button>
      </div>
    </form>
  </div>
</div>

<?php if ($esAdmin): ?>
<div class="d-flex justify-content-end gap-2 mb-3">
  <a href="<?= site_url('productos/precios') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-tag me-1"></i>Actualizar precios</a>
  <a href="<?= site_url('productos/nuevo') ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg me-1"></i>Nuevo producto</a>
</div>
<?php endif ?>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light">
        <tr>
          <th>Descripción</th><th>Tipo</th><th>Proveedor</th><th>Talle</th><th>Color</th>
          <?php if ($esAdmin): ?><th class="text-end">Costo</th><?php endif ?>
          <th class="text-end">Precio</th><th class="text-center">Stock</th><?php if ($esAdmin): ?><th></th><?php endif ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $p): ?>
        <tr>
          <td><?= esc($p['descripcion']) ?></td>
          <td><?= esc($p['tipo_nombre']) ?></td>
          <td><?= esc($p['proveedor_nombre']) ?></td>
          <td><?= esc($p['talle_nombre']) ?></td>
          <td><?= esc($p['color_nombre']) ?></td>
          <?php if ($esAdmin): ?><td class="text-end">$<?= number_format($p['costo'], 2) ?></td><?php endif ?>
          <td class="text-end">$<?= number_format($p['precio_venta'], 2) ?></td>
          <td class="text-center">
            <span class="badge <?= $p['stock_actual'] <= $p['stock_minimo'] ? 'bg-danger' : 'bg-success' ?>"><?= $p['stock_actual'] ?></span>
          </td>
          <?php if ($esAdmin): ?>
          <td class="text-end">
            <a href="<?= site_url('productos/editar/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?= site_url('productos/eliminar/' . $p['id']) ?>" class="d-inline"
                  onsubmit="return confirm('¿Dar de baja este producto?')">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
            </form>
          </td>
          <?php endif ?>
        </tr>
        <?php endforeach ?>
        <?php if (empty($productos)): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">No hay productos que coincidan con la búsqueda.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <div class="card-footer text-muted small"><?= count($productos) ?> productos</div>
</div>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Productos', 'content' => $content]); ?>
