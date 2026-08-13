<?php $content = ob_start() ?: ''; ?>
<div style="max-width:600px">
  <a href="<?= site_url('admin/compras') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('admin/compras/guardar') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
          <select id="productoBase" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php foreach (array_unique(array_column($productos, 'descripcion')) as $desc): ?>
              <option value="<?= esc($desc, 'attr') ?>"><?= esc($desc) ?></option>
            <?php endforeach ?>
          </select>
          <div class="form-text" id="proveedorInfo"></div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Precio (costo) <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="text" inputmode="decimal" name="precio" id="precio" class="form-control money-input" required disabled>
          </div>
          <div class="form-text">Se aplica a todas las cantidades que completes abajo.</div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Cantidades por talle / color <span class="text-danger">*</span></label>
          <div class="table-responsive border rounded" id="variantesWrap" style="max-height:320px;overflow:auto">
            <table class="table table-sm mb-0 small align-middle" id="tablaVariantes">
              <thead class="table-light"><tr><th>Talle</th><th>Color</th><th class="text-center">Stock</th><th style="width:90px">Cantidad</th></tr></thead>
              <tbody>
                <tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <button type="submit" class="btn btn-brand w-100">Registrar compra</button>
      </form>
    </div>
  </div>
</div>

<script>
const productos = <?= json_encode(array_map(fn ($p) => [
  'id'          => $p['id'],
  'descripcion' => $p['descripcion'],
  'talle'       => $p['talle_nombre'],
  'color'       => $p['color_nombre'],
  'stock'       => $p['stock_actual'],
  'costo'       => $p['costo'],
  'proveedor'   => $p['proveedor_nombre'],
], $productos), JSON_UNESCAPED_UNICODE) ?>;

const base         = document.getElementById('productoBase');
const precio       = document.getElementById('precio');
const proveedorInfo = document.getElementById('proveedorInfo');
const tbody        = document.querySelector('#tablaVariantes tbody');

base.addEventListener('change', () => {
  const coincidencias = productos.filter(p => p.descripcion === base.value);

  if (!coincidencias.length) {
    precio.disabled = true;
    precio.value = '';
    proveedorInfo.textContent = '';
    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>';
    return;
  }

  precio.disabled = false;
  precio.value = CB.formatMoney(coincidencias[0].costo || 0);
  proveedorInfo.textContent = 'Proveedor: ' + coincidencias[0].proveedor;

  tbody.innerHTML = coincidencias.map(p => `
    <tr>
      <td>${p.talle}</td>
      <td>${p.color}</td>
      <td class="text-center">${p.stock}</td>
      <td><input type="number" min="0" class="form-control form-control-sm" name="cantidades[${p.id}]" placeholder="0"></td>
    </tr>
  `).join('');
});
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Nueva compra', 'content' => $content]); ?>
