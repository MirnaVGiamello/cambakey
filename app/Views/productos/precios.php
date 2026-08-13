<?php $content = ob_start() ?: ''; ?>
<div style="max-width:600px">
  <a href="<?= site_url('productos') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('productos/precios/actualizar') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="descripcion" id="descripcionHidden">

        <div class="mb-3">
          <label class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
          <select id="productoBase" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php foreach (array_unique(array_column($productos, 'descripcion')) as $desc): ?>
              <option value="<?= esc($desc, 'attr') ?>"><?= esc($desc) ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Nuevo costo</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" name="costo" id="costo" class="form-control money-input" placeholder="Sin cambios" disabled>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Nuevo precio de venta</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" name="precio_venta" id="precioVenta" class="form-control money-input" placeholder="Sin cambios" disabled>
            </div>
          </div>
        </div>
        <div class="form-text mb-3">Dejá en blanco el que no quieras cambiar. Se aplica a todas las variantes de talle y color de este producto.</div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Variantes afectadas</label>
          <div class="table-responsive border rounded" style="max-height:280px;overflow:auto">
            <table class="table table-sm mb-0 small align-middle" id="tablaVariantes">
              <thead class="table-light"><tr><th>Talle</th><th>Color</th><th class="text-end">Costo actual</th><th class="text-end">Precio actual</th></tr></thead>
              <tbody>
                <tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <button type="submit" class="btn btn-brand w-100">Actualizar precios</button>
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
  'costo'       => $p['costo'],
  'precio'      => $p['precio_venta'],
], $productos), JSON_UNESCAPED_UNICODE) ?>;

const base    = document.getElementById('productoBase');
const costo   = document.getElementById('costo');
const precioVenta = document.getElementById('precioVenta');
const descripcionHidden = document.getElementById('descripcionHidden');
const tbody   = document.querySelector('#tablaVariantes tbody');

base.addEventListener('change', () => {
  descripcionHidden.value = base.value;
  const coincidencias = productos.filter(p => p.descripcion === base.value);

  if (!coincidencias.length) {
    costo.disabled = true;
    precioVenta.disabled = true;
    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>';
    return;
  }

  costo.disabled = false;
  precioVenta.disabled = false;
  costo.value = '';
  precioVenta.value = '';

  tbody.innerHTML = coincidencias.map(p => `
    <tr>
      <td>${p.talle}</td>
      <td>${p.color}</td>
      <td class="text-end">$${CB.formatMoney(p.costo)}</td>
      <td class="text-end">$${CB.formatMoney(p.precio)}</td>
    </tr>
  `).join('');
});
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Actualizar precios', 'content' => $content]); ?>
