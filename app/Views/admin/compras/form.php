<?php $content = ob_start() ?: ''; ?>
<div style="max-width:680px">
  <a href="<?= site_url('admin/compras') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('admin/compras/guardar') ?>" id="formCompra">
        <?= csrf_field() ?>
        <div id="lineasOcultas"></div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Producto</label>
          <select id="productoBase" class="form-select">
            <option value="">Seleccionar...</option>
            <?php foreach (array_unique(array_column($productos, 'descripcion')) as $desc): ?>
              <option value="<?= esc($desc, 'attr') ?>"><?= esc($desc) ?></option>
            <?php endforeach ?>
          </select>
          <div class="form-text" id="proveedorInfo"></div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Precio (costo)</label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="text" inputmode="decimal" id="precio" class="form-control money-input" disabled>
          </div>
          <div class="form-text">Se aplica a las cantidades que completes abajo para este producto.</div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Cantidades por talle / color</label>
          <div class="table-responsive border rounded" id="variantesWrap" style="max-height:320px;overflow:auto">
            <table class="table table-sm mb-0 small align-middle" id="tablaVariantes">
              <thead class="table-light"><tr><th>Talle</th><th>Color</th><th class="text-center">Stock</th><th style="width:90px">Cantidad</th></tr></thead>
              <tbody>
                <tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <button type="button" id="btnAgregar" class="btn btn-outline-secondary w-100 mb-4"><i class="bi bi-plus-lg me-1"></i>Agregar producto a la compra</button>

        <div id="bloqueLineas" class="mb-4" style="display:none">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-2">
              <thead class="table-light"><tr><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th><th></th></tr></thead>
              <tbody id="cuerpoLineas"></tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end align-items-baseline gap-2">
            <span class="text-muted">Total (para comparar con el ticket):</span>
            <span id="totalCompra" class="fs-4 fw-bold">$0.00</span>
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

const base          = document.getElementById('productoBase');
const precio        = document.getElementById('precio');
const proveedorInfo = document.getElementById('proveedorInfo');
const tbody         = document.querySelector('#tablaVariantes tbody');
const btnAgregar    = document.getElementById('btnAgregar');
const bloqueLineas  = document.getElementById('bloqueLineas');
const cuerpoLineas  = document.getElementById('cuerpoLineas');
const totalCompraEl = document.getElementById('totalCompra');
const lineasOcultas = document.getElementById('lineasOcultas');
const form          = document.getElementById('formCompra');

let lineas = [];

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
    <tr data-producto-id="${p.id}" data-desc="${p.descripcion}" data-talle="${p.talle}" data-color="${p.color}">
      <td>${p.talle}</td>
      <td>${p.color}</td>
      <td class="text-center">${p.stock}</td>
      <td><input type="number" min="0" class="form-control form-control-sm" placeholder="0"></td>
    </tr>
  `).join('');
});

function leerVariantesActuales() {
  const costoActual = CB.parseMoney(precio.value);
  if (precio.disabled || costoActual === '' || isNaN(costoActual) || costoActual <= 0) return null;

  const filas = Array.from(tbody.querySelectorAll('tr[data-producto-id]'));
  const nuevas = [];
  filas.forEach(fila => {
    const cantidad = parseInt(fila.querySelector('input').value, 10);
    if (cantidad > 0) {
      nuevas.push({
        producto_id: fila.dataset.productoId,
        descripcion: fila.dataset.desc,
        talle: fila.dataset.talle,
        color: fila.dataset.color,
        cantidad,
        precio: costoActual,
      });
    }
  });

  return nuevas.length ? nuevas : null;
}

function limpiarSeleccionActual() {
  base.value = '';
  precio.disabled = true;
  precio.value = '';
  proveedorInfo.textContent = '';
  tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Elegí primero el producto</td></tr>';
  base.focus();
}

function renderLineas() {
  cuerpoLineas.innerHTML = lineas.map((l, i) => `
    <tr>
      <td>${l.descripcion}</td>
      <td>${l.talle}</td>
      <td>${l.color}</td>
      <td class="text-center">${l.cantidad}</td>
      <td class="text-end">$${CB.formatMoney(l.precio)}</td>
      <td class="text-end">$${CB.formatMoney(l.cantidad * l.precio)}</td>
      <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" data-quitar="${i}"><i class="bi bi-x"></i></button></td>
    </tr>
  `).join('');

  lineasOcultas.innerHTML = lineas.map(l => `
    <input type="hidden" name="producto_id[]" value="${l.producto_id}">
    <input type="hidden" name="cantidad[]" value="${l.cantidad}">
    <input type="hidden" name="precio[]" value="${l.precio}">
  `).join('');

  const total = lineas.reduce((acc, l) => acc + l.cantidad * l.precio, 0);
  totalCompraEl.textContent = '$' + CB.formatMoney(total);
  bloqueLineas.style.display = lineas.length ? '' : 'none';
}

btnAgregar.addEventListener('click', () => {
  const nuevas = leerVariantesActuales();
  if (!nuevas) {
    alert('Elegí un producto, completá el costo y al menos una cantidad mayor a 0.');
    return;
  }
  lineas.push(...nuevas);
  renderLineas();
  limpiarSeleccionActual();
});

cuerpoLineas.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-quitar]');
  if (!btn) return;
  lineas.splice(parseInt(btn.dataset.quitar, 10), 1);
  renderLineas();
});

form.addEventListener('submit', (e) => {
  // Si quedó un producto cargado arriba sin tocar "Agregar", se suma solo al confirmar.
  const pendientes = leerVariantesActuales();
  if (pendientes) {
    lineas.push(...pendientes);
    renderLineas();
  }

  if (!lineas.length) {
    e.preventDefault();
    alert('Agregá al menos un producto a la compra.');
  }
});
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Nueva compra', 'content' => $content]); ?>
