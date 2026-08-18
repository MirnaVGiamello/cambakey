<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $title ?? 'Cambakey' ?> · Cambakey</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  :root{--accent:#C08A2E}
  body{background:#f5f5f5}

  .sidebar{width:220px;min-height:100vh;background:#1C1C1E;flex-shrink:0;transition:transform .28s}
  .sidebar .brand{background:#fff;padding:14px 16px;display:flex;justify-content:center}
  .sidebar .brand img{max-width:100%;height:auto;width:150px}
  .sidebar .nav-link{color:rgba(255,255,255,.65);padding:9px 16px;border-radius:6px;margin:2px 8px;font-size:.88rem;display:flex;align-items:center;gap:8px}
  .sidebar .nav-link:hover,.sidebar .nav-link.active{color:#fff;background:rgba(255,255,255,.1)}
  .sidebar .nav-link i{font-size:1rem;opacity:.8}
  .sidebar .nav-link.nav-sub{padding-left:34px;font-size:.83rem}
  .sidebar .nav-group-btn{width:calc(100% - 16px);background:none;border:none;text-align:left;color:rgba(255,255,255,.65);padding:9px 16px;border-radius:6px;margin:2px 8px;font-size:.88rem;display:flex;align-items:center;gap:8px;cursor:pointer}
  .sidebar .nav-group-btn:hover,.sidebar .nav-group-btn.active{color:#fff;background:rgba(255,255,255,.1)}
  .sidebar .nav-group-btn i:first-child{font-size:1rem;opacity:.8}
  .sidebar .nav-group-btn .chevron{margin-left:auto;font-size:.7rem;opacity:.6;transition:transform .2s}
  .sidebar .nav-group-btn[aria-expanded="true"] .chevron{transform:rotate(180deg)}

  @media(min-width:768px){
    .sidebar{display:flex!important;flex-direction:column}
    .btn-menu{display:none!important}
    .sb-overlay{display:none!important}
  }

  @media(max-width:767px){
    .sidebar{
      position:fixed;top:0;left:0;bottom:0;z-index:1050;
      display:flex;flex-direction:column;
      transform:translateX(-100%);min-height:100%;
    }
    .sidebar.open{transform:translateX(0)}
    .sb-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1049;display:none}
    .sb-overlay.vis{display:block}
    .btn-menu{display:flex!important}
    .main-col{width:100%}
  }

  .topbar{background:#fff;border-bottom:1px solid #e9ecef;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;gap:10px}
  .brand-link{display:flex;align-items:center;gap:6px;color:#1C1C1E;font-weight:800;font-size:.9rem;text-decoration:none}
  .brand-link:hover{color:var(--accent)}
  .brand-link img{height:22px;width:auto}
  .page-title{font-weight:700;font-size:1.05rem;color:#1C1C1E;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .content{padding:16px}
  .btn-brand{background:var(--accent);border:none;color:#fff;font-weight:600}
  .btn-brand:hover{background:#a3741f;color:#fff}
  .table-responsive{-webkit-overflow-scrolling:touch}
  th.th-sortable{cursor:pointer;user-select:none;white-space:nowrap}
  th.th-sortable:hover{background:rgba(0,0,0,.05)}
  th.th-sortable .sort-icon{opacity:.6;font-size:.85em}
</style>
</head>
<body>

<div id="sbOverlay" class="sb-overlay"></div>

<div class="d-flex">
  <div class="sidebar" id="sidebar">
    <div class="brand"><img src="<?= base_url('images/cambakey.png') ?>" alt="Cambakey"></div>
    <nav class="flex-grow-1 py-2">
      <?php $u = current_url(); ?>
      <?php if (session()->get('perfil') === 'admin'): ?>
        <a href="<?= site_url('dashboard') ?>" class="nav-link <?= str_contains($u, 'dashboard') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <?php endif ?>
      <a href="<?= site_url('productos') ?>" class="nav-link <?= str_contains($u, '/productos') ? 'active' : '' ?>"><i class="bi bi-box-seam"></i> Productos</a>
      <a href="<?= site_url('stock/bajo') ?>" class="nav-link <?= str_contains($u, 'stock') ? 'active' : '' ?>"><i class="bi bi-exclamation-triangle"></i> Bajo stock</a>
      <a href="<?= site_url('ventas') ?>" class="nav-link <?= str_contains($u, 'ventas') ? 'active' : '' ?>"><i class="bi bi-cart-check"></i> Ventas</a>

      <?php if (session()->get('perfil') === 'admin'): ?>
        <?php
          $informesAbierto = str_contains($u, 'stock-valorizado') || str_contains($u, 'ventas-grafico') || str_contains($u, 'eliminaciones');
          $tablasAbierto   = str_contains($u, 'proveedores') || str_contains($u, 'tipos-producto') || str_contains($u, 'talles') || str_contains($u, 'colores') || str_contains($u, 'tipos-gasto');
        ?>
        <hr style="border-color:rgba(255,255,255,.1);margin:8px 16px">
        <div style="color:rgba(255,255,255,.3);font-size:.68rem;letter-spacing:.1em;padding:4px 16px 2px">ADMINISTRACIÓN</div>
        <a href="<?= site_url('admin/compras') ?>" class="nav-link <?= str_contains($u, 'compras') ? 'active' : '' ?>"><i class="bi bi-truck"></i> Compras</a>
        <a href="<?= site_url('admin/gastos') ?>"   class="nav-link <?= str_contains($u, '/gastos') ? 'active' : '' ?>"><i class="bi bi-wallet2"></i> Gastos</a>

        <button type="button" class="nav-group-btn <?= $informesAbierto ? 'active' : '' ?>" data-bs-toggle="collapse" data-bs-target="#navInformes" aria-expanded="<?= $informesAbierto ? 'true' : 'false' ?>">
          <i class="bi bi-graph-up"></i> Informes <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $informesAbierto ? 'show' : '' ?>" id="navInformes">
          <a href="<?= site_url('admin/informes/stock-valorizado') ?>" class="nav-link nav-sub <?= str_contains($u, 'stock-valorizado') ? 'active' : '' ?>"><i class="bi bi-cash-stack"></i> Stock valorizado</a>
          <a href="<?= site_url('admin/informes/ventas-grafico') ?>"   class="nav-link nav-sub <?= str_contains($u, 'ventas-grafico') ? 'active' : '' ?>"><i class="bi bi-bar-chart-line"></i> Ventas (gráfico)</a>
          <a href="<?= site_url('admin/eliminaciones') ?>"              class="nav-link nav-sub <?= str_contains($u, 'eliminaciones') ? 'active' : '' ?>"><i class="bi bi-clock-history"></i> Eliminados</a>
        </div>

        <button type="button" class="nav-group-btn <?= $tablasAbierto ? 'active' : '' ?>" data-bs-toggle="collapse" data-bs-target="#navTablas" aria-expanded="<?= $tablasAbierto ? 'true' : 'false' ?>">
          <i class="bi bi-table"></i> Tablas <i class="bi bi-chevron-down chevron"></i>
        </button>
        <div class="collapse <?= $tablasAbierto ? 'show' : '' ?>" id="navTablas">
          <a href="<?= site_url('admin/proveedores') ?>"    class="nav-link nav-sub <?= str_contains($u, 'proveedores') ? 'active' : '' ?>"><i class="bi bi-building"></i> Proveedores</a>
          <a href="<?= site_url('admin/tipos-producto') ?>" class="nav-link nav-sub <?= str_contains($u, 'tipos-producto') ? 'active' : '' ?>"><i class="bi bi-tags"></i> Tipos de producto</a>
          <a href="<?= site_url('admin/talles') ?>"         class="nav-link nav-sub <?= str_contains($u, 'talles') ? 'active' : '' ?>"><i class="bi bi-rulers"></i> Talles</a>
          <a href="<?= site_url('admin/colores') ?>"        class="nav-link nav-sub <?= str_contains($u, 'colores') ? 'active' : '' ?>"><i class="bi bi-palette"></i> Colores</a>
          <a href="<?= site_url('admin/tipos-gasto') ?>"    class="nav-link nav-sub <?= str_contains($u, 'tipos-gasto') ? 'active' : '' ?>"><i class="bi bi-tags"></i> Tipos de gasto</a>
        </div>

        <a href="<?= site_url('admin/usuarios') ?>" class="nav-link <?= str_contains($u, 'usuarios') ? 'active' : '' ?>"><i class="bi bi-people"></i> Usuarios</a>
      <?php endif ?>
    </nav>
  </div>

  <div class="flex-grow-1 main-col" style="min-width:0">
    <div class="topbar">
      <div class="d-flex align-items-center gap-2" style="min-width:0;overflow:hidden">
        <button class="btn btn-sm btn-outline-secondary btn-menu p-1 lh-1" id="btnMenu" style="width:34px;height:34px" aria-label="Menú">
          <i class="bi bi-list fs-5"></i>
        </button>
        <a href="<?= site_url('/') ?>" class="brand-link flex-shrink-0 d-md-none"><img src="<?= base_url('images/cambakey.png') ?>" alt="Cambakey"></a>
        <span class="page-title text-muted">· <?= $title ?? '' ?></span>
      </div>
      <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <span class="text-muted small text-nowrap"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('nombre')) ?></span>
        <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Salir">
          <i class="bi bi-box-arrow-left"></i>
        </a>
      </div>
    </div>

    <?php if (session()->getFlashdata('ok')): ?>
      <div class="alert alert-success alert-dismissible m-3 mb-0 py-2" role="alert">
        <?= esc(session()->getFlashdata('ok')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible m-3 mb-0 py-2" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>

    <div class="content">
      <?= $content ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sbOverlay');
const btnMenu = document.getElementById('btnMenu');

btnMenu.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  overlay.classList.toggle('vis');
});
overlay.addEventListener('click', () => {
  sidebar.classList.remove('open');
  overlay.classList.remove('vis');
});
sidebar.querySelectorAll('.nav-link').forEach(a => {
  a.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('vis');
  });
});

// Formato de montos: $16,000.00 en pantalla, número plano al enviar el formulario.
window.CB = window.CB || {};
CB.formatMoney = function (value) {
  const num = parseFloat(String(value).replace(/,/g, ''));
  return isNaN(num) ? '' : num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
CB.parseMoney = function (value) {
  const num = parseFloat(String(value).replace(/,/g, ''));
  return isNaN(num) ? '' : num;
};

document.querySelectorAll('.money-input').forEach(function (input) {
  if (input.value !== '') input.value = CB.formatMoney(input.value);
  input.addEventListener('blur', function () {
    if (input.value !== '') input.value = CB.formatMoney(input.value);
  });
});

document.querySelectorAll('form').forEach(function (form) {
  form.addEventListener('submit', function () {
    form.querySelectorAll('.money-input').forEach(function (input) {
      if (input.value !== '') input.value = CB.parseMoney(input.value);
    });
  });
});

// Ordenamiento por click en el header, aplicado a todas las grillas de listado.
function cbSortCellValue(text) {
  text = text.trim();
  if (text === '') return null;
  const fecha = text.match(/^(\d{2})\/(\d{2})\/(\d{4})(?:\s+(\d{2}):(\d{2}))?$/);
  if (fecha) {
    const [, d, mo, y, h, mi] = fecha;
    return new Date(`${y}-${mo}-${d}T${h || '00'}:${mi || '00'}:00`).getTime();
  }
  const limpio = text.replace(/[$%\s]/g, '').replace(/,/g, '');
  if (limpio !== '' && !isNaN(limpio)) return parseFloat(limpio);
  return null;
}

function cbCompareRows(a, b, col, dir) {
  const ta = (a.children[col] && a.children[col].textContent || '').trim();
  const tb = (b.children[col] && b.children[col].textContent || '').trim();
  const na = cbSortCellValue(ta), nb = cbSortCellValue(tb);
  const cmp = (na !== null && nb !== null)
    ? na - nb
    : ta.localeCompare(tb, 'es', { numeric: true, sensitivity: 'base' });
  return dir === 'asc' ? cmp : -cmp;
}

document.querySelectorAll('table').forEach(function (table) {
  if (table.id === 'tablaVariantes' || !table.tHead) return;
  const headerRow = table.tHead.rows[table.tHead.rows.length - 1];
  const ths = Array.from(headerRow.cells);
  let sortState = { col: -1, dir: 'asc' };

  ths.forEach(function (th, col) {
    if (th.textContent.trim() === '') return;
    th.classList.add('th-sortable');
    th.insertAdjacentHTML('beforeend', ' <i class="bi bi-arrow-down-up sort-icon"></i>');

    th.addEventListener('click', function () {
      const tbody = table.tBodies[0];
      if (!tbody) return;
      const rows = Array.from(tbody.rows).filter(r => r.cells.length === ths.length);
      if (rows.length < 2) return;

      const dir = (sortState.col === col && sortState.dir === 'asc') ? 'desc' : 'asc';
      sortState = { col, dir };

      rows.sort((a, b) => cbCompareRows(a, b, col, dir));
      rows.forEach(r => tbody.appendChild(r));

      ths.forEach(function (h, i) {
        const icon = h.querySelector('.sort-icon');
        if (!icon) return;
        icon.className = 'bi sort-icon ' + (i === col ? (dir === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up');
      });
    });
  });
});
</script>
</body>
</html>
