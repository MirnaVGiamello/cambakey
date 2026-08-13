<?php $content = ob_start() ?: ''; ?>
<div style="max-width:480px">
  <a href="<?= site_url('ventas') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="post" action="<?= site_url('ventas/guardar') ?>" id="formVenta">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">Producto <span class="text-danger">*</span></label>
          <select id="productoBase" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php foreach (array_unique(array_column($productos, 'descripcion')) as $desc): ?>
              <option value="<?= esc($desc, 'attr') ?>"><?= esc($desc) ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Talle / Color <span class="text-danger">*</span></label>
          <select name="producto_id" id="productoVariante" class="form-select" required disabled>
            <option value="">Elegí primero el producto</option>
          </select>
          <div class="form-text" id="stockInfo"></div>
        </div>

        <div class="row g-2 mb-4">
          <div class="col-6">
            <label class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
            <input type="number" min="1" name="cantidad" id="cantidad" class="form-control" required>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Precio de venta <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="text" inputmode="decimal" name="precio" id="precio" class="form-control money-input" required>
            </div>
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

const base    = document.getElementById('productoBase');
const variante = document.getElementById('productoVariante');
const precio  = document.getElementById('precio');
const stockInfo = document.getElementById('stockInfo');

base.addEventListener('change', () => {
  variante.innerHTML = '';
  precio.value = '';
  stockInfo.textContent = '';

  const coincidencias = productos.filter(p => p.descripcion === base.value);
  if (!coincidencias.length) {
    variante.disabled = true;
    variante.innerHTML = '<option value="">Elegí primero el producto</option>';
    return;
  }

  variante.disabled = false;
  variante.innerHTML = '<option value="">Seleccionar talle/color...</option>' +
    coincidencias.map(p => `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.stock}">${p.talle} - ${p.color} (stock: ${p.stock})</option>`).join('');
});

variante.addEventListener('change', () => {
  const opt = variante.options[variante.selectedIndex];
  precio.value = opt.dataset.precio !== undefined ? CB.formatMoney(opt.dataset.precio) : '';
  stockInfo.textContent = opt.dataset.stock !== undefined ? `Stock actual: ${opt.dataset.stock}` : '';
});
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Nueva venta', 'content' => $content]); ?>
