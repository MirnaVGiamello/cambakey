<?php $content = ob_start() ?: ''; ?>
<div style="max-width:640px">
  <a href="<?= site_url('ventas') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('ventas/guardar') ?>" id="formVenta">
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
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Talle / Color</label>
          <select id="productoVariante" class="form-select" disabled>
            <option value="">Elegí primero el producto</option>
          </select>
          <div class="form-text" id="stockInfo"></div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Cantidad</label>
            <input type="number" min="1" id="cantidad" class="form-control" value="1">
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Precio de venta</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" id="precio" class="form-control money-input">
            </div>
          </div>
        </div>

        <button type="button" id="btnAgregar" class="btn btn-outline-secondary w-100 mb-4"><i class="bi bi-plus-lg me-1"></i>Agregar a la venta</button>

        <div id="bloqueLineas" class="mb-4" style="display:none">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-2">
              <thead class="table-light"><tr><th>Producto</th><th>Talle</th><th>Color</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th><th></th></tr></thead>
              <tbody id="cuerpoLineas"></tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end align-items-baseline gap-2">
            <span class="text-muted">Total a cobrar:</span>
            <span id="totalVenta" class="fs-4 fw-bold">$0.00</span>
          </div>
        </div>

        <button type="submit" class="btn btn-brand w-100">Registrar venta</button>
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
  'precio'      => $p['precio_venta'],
], $productos), JSON_UNESCAPED_UNICODE) ?>;

const base          = document.getElementById('productoBase');
const variante       = document.getElementById('productoVariante');
const cantidadInput  = document.getElementById('cantidad');
const precioInput    = document.getElementById('precio');
const stockInfo      = document.getElementById('stockInfo');
const btnAgregar     = document.getElementById('btnAgregar');
const bloqueLineas   = document.getElementById('bloqueLineas');
const cuerpoLineas   = document.getElementById('cuerpoLineas');
const totalVentaEl   = document.getElementById('totalVenta');
const lineasOcultas  = document.getElementById('lineasOcultas');
const form           = document.getElementById('formVenta');

let lineas = [];

base.addEventListener('change', () => {
  variante.innerHTML = '';
  precioInput.value = '';
  stockInfo.textContent = '';

  const coincidencias = productos.filter(p => p.descripcion === base.value);
  if (!coincidencias.length) {
    variante.disabled = true;
    variante.innerHTML = '<option value="">Elegí primero el producto</option>';
    return;
  }

  variante.disabled = false;
  variante.innerHTML = '<option value="">Seleccionar talle/color...</option>' +
    coincidencias.map(p => `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.stock}" data-desc="${p.descripcion}" data-talle="${p.talle}" data-color="${p.color}">${p.talle} - ${p.color} (stock: ${p.stock})</option>`).join('');
});

variante.addEventListener('change', () => {
  const opt = variante.options[variante.selectedIndex];
  precioInput.value = opt.dataset.precio !== undefined ? CB.formatMoney(opt.dataset.precio) : '';
  stockInfo.textContent = opt.dataset.stock !== undefined ? `Stock actual: ${opt.dataset.stock}` : '';
});

function leerSeleccionActual() {
  const opt = variante.options[variante.selectedIndex];
  if (!opt || !opt.value) return null;

  const cantidad = parseInt(cantidadInput.value, 10);
  const precio = CB.parseMoney(precioInput.value);
  if (!cantidad || cantidad < 1 || precio === '' || isNaN(precio) || precio <= 0) return null;

  return {
    producto_id: opt.value,
    descripcion: opt.dataset.desc,
    talle: opt.dataset.talle,
    color: opt.dataset.color,
    cantidad,
    precio,
  };
}

function limpiarSeleccionActual() {
  base.value = '';
  variante.innerHTML = '<option value="">Elegí primero el producto</option>';
  variante.disabled = true;
  cantidadInput.value = 1;
  precioInput.value = '';
  stockInfo.textContent = '';
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
  totalVentaEl.textContent = '$' + CB.formatMoney(total);
  bloqueLineas.style.display = lineas.length ? '' : 'none';
}

btnAgregar.addEventListener('click', () => {
  const linea = leerSeleccionActual();
  if (!linea) {
    alert('Elegí el producto, la cantidad y el precio antes de agregar.');
    return;
  }
  lineas.push(linea);
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
  // Si quedó algo cargado arriba sin tocar "Agregar", se suma solo al confirmar.
  const pendiente = leerSeleccionActual();
  if (pendiente) {
    lineas.push(pendiente);
    renderLineas();
  }

  if (!lineas.length) {
    e.preventDefault();
    alert('Agregá al menos un producto a la venta.');
  }
});
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Nueva venta', 'content' => $content]); ?>
