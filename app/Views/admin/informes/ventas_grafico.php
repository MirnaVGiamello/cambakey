<?php $content = ob_start() ?: ''; ?>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Desde</label>
        <input type="date" name="desde" class="form-control form-control-sm" value="<?= esc($filtros['desde']) ?>">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Hasta</label>
        <input type="date" name="hasta" class="form-control form-control-sm" value="<?= esc($filtros['hasta']) ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1 d-block">Agrupar por</label>
        <div class="btn-group btn-group-sm w-100" role="group">
          <input type="radio" class="btn-check" name="corte" id="corteDia" value="dia" autocomplete="off" <?= $corte === 'dia' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="corteDia">Día</label>
          <input type="radio" class="btn-check" name="corte" id="corteMes" value="mes" autocomplete="off" <?= $corte === 'mes' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="corteMes">Mes</label>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small mb-1 d-block">Medida</label>
        <div class="btn-group btn-group-sm w-100" role="group">
          <input type="radio" class="btn-check" name="medida" id="medidaCantidad" value="cantidad" autocomplete="off" <?= $medida === 'cantidad' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="medidaCantidad">Cantidad</label>
          <input type="radio" class="btn-check" name="medida" id="medidaPesos" value="pesos" autocomplete="off" <?= $medida === 'pesos' ? 'checked' : '' ?>>
          <label class="btn btn-outline-secondary" for="medidaPesos">Pesos</label>
        </div>
      </div>
      <div class="col-12 col-md-2">
        <button class="btn btn-sm btn-brand w-100"><i class="bi bi-search me-1"></i>Ver</button>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Unidades vendidas</div>
      <div class="fs-4 fw-bold"><?= number_format($totalUnidades, 0) ?></div>
    </div></div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm border-0"><div class="card-body">
      <div class="text-muted small">Total ventas</div>
      <div class="fs-4 fw-bold text-success">$<?= number_format($totalVentas, 2) ?></div>
    </div></div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-header bg-white fw-semibold">
    Ventas por <?= $corte === 'mes' ? 'mes' : 'día' ?> — <?= $medida === 'pesos' ? 'Pesos' : 'Cantidad' ?>
  </div>
  <div class="card-body">
    <?php if (empty($serie)): ?>
      <div class="alert alert-light border text-muted mb-0">No hay ventas en este período.</div>
    <?php else: ?>
      <div class="cb-chart-wrap" style="position:relative">
        <div style="overflow-x:auto">
          <svg id="cbGraficoVentas" viewBox="0 0 <?= $grafico['anchoTotal'] ?> <?= $grafico['altoTotal'] ?>" width="<?= $grafico['anchoTotal'] ?>" height="<?= $grafico['altoTotal'] ?>" role="img" aria-label="Gráfico de ventas por <?= $corte === 'mes' ? 'mes' : 'día' ?>, en <?= $medida === 'pesos' ? 'pesos' : 'cantidad de unidades' ?>">
            <?php foreach ($grafico['lineas'] as $linea): ?>
              <line x1="<?= $grafico['margenIzq'] ?>" y1="<?= $linea['y'] ?>" x2="<?= $grafico['anchoTotal'] - 16 ?>" y2="<?= $linea['y'] ?>" stroke="#e1e0d9" stroke-width="1"></line>
              <text x="<?= $grafico['margenIzq'] - 8 ?>" y="<?= $linea['y'] + 4 ?>" text-anchor="end" font-size="11" fill="#898781"><?= $medida === 'pesos' ? '$' . number_format($linea['valor'], 0) : number_format($linea['valor'], 0) ?></text>
            <?php endforeach ?>

            <line x1="<?= $grafico['margenIzq'] ?>" y1="<?= $grafico['margenSup'] + $grafico['altoPlot'] ?>" x2="<?= $grafico['anchoTotal'] - 16 ?>" y2="<?= $grafico['margenSup'] + $grafico['altoPlot'] ?>" stroke="#c3c2b7" stroke-width="1"></line>

            <?php foreach ($grafico['barras'] as $i => $b): ?>
              <?php $valorTexto = $medida === 'pesos' ? '$' . number_format($b['valor'], 2) : number_format($b['valor'], 0); ?>
              <?php if ($b['path'] !== ''): ?>
                <path d="<?= $b['path'] ?>" fill="#C08A2E" style="pointer-events:none"></path>
              <?php endif ?>
              <?php if ($b['esMax']): ?>
                <text x="<?= $b['x'] + $b['w'] / 2 ?>" y="<?= $b['y'] - 6 ?>" text-anchor="middle" font-size="11" font-weight="600" fill="#52514e" style="pointer-events:none"><?= esc($valorTexto) ?></text>
              <?php endif ?>
              <?php if ($b['mostrarEtiqueta']): ?>
                <text x="<?= $b['x'] + $b['w'] / 2 ?>" y="<?= $grafico['margenSup'] + $grafico['altoPlot'] + 16 ?>" text-anchor="middle" font-size="10" fill="#898781" style="pointer-events:none"><?= esc($b['etiquetaCorta']) ?></text>
              <?php endif ?>
              <rect class="cb-hit" x="<?= $grafico['margenIzq'] + $i * $grafico['anchoSlot'] ?>" y="<?= $grafico['margenSup'] ?>" width="<?= $grafico['anchoSlot'] ?>" height="<?= $grafico['altoPlot'] ?>" fill="transparent" tabindex="0" role="img" data-periodo="<?= esc($b['etiquetaCompleta']) ?>" data-valor="<?= esc($valorTexto) ?>" aria-label="<?= esc($b['etiquetaCompleta'] . ': ' . $valorTexto) ?>"></rect>
            <?php endforeach ?>
          </svg>
        </div>
        <div class="cb-tooltip" id="cbTooltip"></div>
      </div>
    <?php endif ?>
  </div>
</div>

<?php if (!empty($serie)): ?>
<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 small align-middle">
      <thead class="table-light"><tr><th>Período</th><th class="text-center">Unidades</th><th class="text-end">Total</th></tr></thead>
      <tbody>
        <?php foreach ($serie as $i => $f): ?>
        <tr>
          <td><?= esc($grafico['barras'][$i]['etiquetaCompleta']) ?></td>
          <td class="text-center"><?= number_format($f['cantidad'], 0) ?></td>
          <td class="text-end">$<?= number_format($f['total'], 2) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif ?>

<style>
.cb-hit{cursor:pointer}
.cb-tooltip{position:absolute;top:4px;transform:translateX(-50%);background:#1C1C1E;color:#fff;padding:6px 10px;border-radius:6px;font-size:.78rem;pointer-events:none;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,.25);z-index:5;display:none;left:0}
.cb-tooltip .valor{font-weight:600}
</style>

<script>
(function () {
  const svg = document.getElementById('cbGraficoVentas');
  if (!svg) return;
  const tip  = document.getElementById('cbTooltip');
  const wrap = svg.closest('.cb-chart-wrap');

  function posicionar(clientX) {
    const rectWrap = wrap.getBoundingClientRect();
    const scrollBox = svg.closest('div');
    const px = clientX - rectWrap.left + (scrollBox ? scrollBox.scrollLeft : 0);
    tip.style.left = Math.min(Math.max(px, 40), rectWrap.width - 40) + 'px';
  }

  svg.querySelectorAll('.cb-hit').forEach(function (hit) {
    function mostrar(e) {
      hit.setAttribute('fill', 'rgba(11,11,11,.06)');
      tip.innerHTML = '';
      const per = document.createElement('div');
      per.className = 'text-white-50';
      per.textContent = hit.dataset.periodo;
      const val = document.createElement('div');
      val.className = 'valor';
      val.textContent = hit.dataset.valor;
      tip.appendChild(per);
      tip.appendChild(val);
      tip.style.display = 'block';
      posicionar(e.clientX !== undefined ? e.clientX : hit.getBoundingClientRect().left);
    }
    function ocultar() {
      hit.setAttribute('fill', 'transparent');
      tip.style.display = 'none';
    }
    hit.addEventListener('mouseenter', mostrar);
    hit.addEventListener('mousemove', function (e) { posicionar(e.clientX); });
    hit.addEventListener('mouseleave', ocultar);
    hit.addEventListener('focus', mostrar);
    hit.addEventListener('blur', ocultar);
  });
})();
</script>

<?php $content = ob_get_clean(); echo view('layout', ['title' => 'Ventas por período', 'content' => $content]); ?>
