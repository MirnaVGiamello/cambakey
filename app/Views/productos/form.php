<?php $content = ob_start() ?: ''; $esNuevo = $producto === null; ?>
<div style="max-width:520px">
  <a href="<?= site_url('productos') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= $esNuevo ? site_url('productos/guardar') : site_url('productos/actualizar/' . $producto['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
          <input type="text" name="descripcion" class="form-control" value="<?= esc($producto['descripcion'] ?? old('descripcion')) ?>" required>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
            <select name="tipo_producto_id" class="form-select" required>
              <option value="">Seleccionar...</option>
              <?php foreach ($tipos as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($producto['tipo_producto_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Proveedor <span class="text-danger">*</span></label>
            <select name="proveedor_id" class="form-select" required>
              <option value="">Seleccionar...</option>
              <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($producto['proveedor_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nombre']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <?php if ($esNuevo): ?>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Talles <span class="text-danger">*</span></label>
            <div class="border rounded p-2" style="max-height:160px;overflow:auto">
              <?php foreach ($talles as $t): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="talle_id[]" value="<?= $t['id'] ?>" id="talle<?= $t['id'] ?>">
                  <label class="form-check-label" for="talle<?= $t['id'] ?>"><?= esc($t['nombre']) ?></label>
                </div>
              <?php endforeach ?>
            </div>
            <div class="form-text">Elegí uno o varios. Se crea un producto por cada combinación de talle y color.</div>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Colores <span class="text-danger">*</span></label>
            <div class="border rounded p-2" style="max-height:160px;overflow:auto">
              <?php foreach ($colores as $c): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="color_id[]" value="<?= $c['id'] ?>" id="color<?= $c['id'] ?>">
                  <label class="form-check-label" for="color<?= $c['id'] ?>"><?= esc($c['nombre']) ?></label>
                </div>
              <?php endforeach ?>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Talle <span class="text-danger">*</span></label>
            <select name="talle_id" class="form-select" required>
              <option value="">Seleccionar...</option>
              <?php foreach ($talles as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($producto['talle_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= esc($t['nombre']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Color <span class="text-danger">*</span></label>
            <select name="color_id" class="form-select" required>
              <option value="">Seleccionar...</option>
              <?php foreach ($colores as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($producto['color_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <?php endif ?>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Costo</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" name="costo" class="form-control money-input" value="<?= number_format($producto['costo'] ?? 0, 2) ?>">
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Precio de venta</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" name="precio_venta" class="form-control money-input" value="<?= number_format($producto['precio_venta'] ?? 0, 2) ?>">
            </div>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <?php if ($esNuevo): ?>
          <div class="col-6">
            <label class="form-label fw-semibold">Stock inicial</label>
            <input type="number" min="0" name="stock_actual" class="form-control" value="0">
            <div class="form-text">Se aplica igual a cada combinación creada.</div>
          </div>
          <?php endif ?>
          <div class="col-6">
            <label class="form-label fw-semibold">Stock mínimo</label>
            <input type="number" min="0" name="stock_minimo" class="form-control" value="<?= esc($producto['stock_minimo'] ?? '0') ?>">
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Observación</label>
          <textarea name="observacion" class="form-control" rows="2"><?= esc($producto['observacion'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-brand w-100"><?= $esNuevo ? 'Crear producto(s)' : 'Guardar' ?></button>
      </form>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); echo view('layout', ['title' => $accion, 'content' => $content]); ?>
