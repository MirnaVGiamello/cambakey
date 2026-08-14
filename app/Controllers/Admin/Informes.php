<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ProductoModel;
use App\Models\VentaModel;

class Informes extends BaseController
{
    private const MESES_ABREV = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
    private const MESES_FULL  = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

    public function stockValorizado()
    {
        $agrupado = $this->request->getGet('vista') !== 'detalle';
        $filas    = (new ProductoModel())->stockValorizado($agrupado);

        return view('admin/informes/stock_valorizado', [
            'filas'    => $filas,
            'agrupado' => $agrupado,
            'total'    => array_sum(array_column($filas, 'valor')),
            'totalStock' => array_sum(array_column($filas, 'stock_actual')),
        ]);
    }

    public function ventasGrafico()
    {
        $filtros = [
            'desde' => $this->request->getGet('desde') ?: date('Y-m-01'),
            'hasta' => $this->request->getGet('hasta') ?: date('Y-m-d'),
        ];
        $corte  = $this->request->getGet('corte')  === 'mes'   ? 'mes'   : 'dia';
        $medida = $this->request->getGet('medida') === 'pesos' ? 'pesos' : 'cantidad';

        $serie = (new VentaModel())->serieTemporal($filtros, $corte);

        return view('admin/informes/ventas_grafico', [
            'filtros'       => array_merge($filtros, ['corte' => $corte, 'medida' => $medida]),
            'corte'         => $corte,
            'medida'        => $medida,
            'serie'         => $serie,
            'grafico'       => $this->construirGrafico($serie, $corte, $medida),
            'totalUnidades' => array_sum(array_column($serie, 'cantidad')),
            'totalVentas'   => array_sum(array_column($serie, 'total')),
        ]);
    }

    private function construirGrafico(array $serie, string $corte, string $medida): array
    {
        $campo   = $medida === 'pesos' ? 'total' : 'cantidad';
        $valores = array_map(fn ($f) => (float) $f[$campo], $serie);
        $max     = $valores ? max($valores) : 0.0;
        $niveles = $this->nivelesEje($max);
        $topeEje = end($niveles) ?: 1.0;

        $altoPlot  = 220;
        $margenIzq = 56;
        $margenDer = 16;
        $margenSup = 28;
        $margenInf = 36;
        $anchoSlot  = 34;
        $anchoBarra = 22;

        $n = count($serie);
        $anchoPlot  = max($n * $anchoSlot, 240);
        $anchoTotal = $anchoPlot + $margenIzq + $margenDer;
        $altoTotal  = $altoPlot + $margenSup + $margenInf;

        $pasoEtiqueta = $n > 0 ? max(1, (int) ceil($n / 10)) : 1;
        $indiceMax    = $valores ? array_keys($valores, max($valores))[0] : null;

        $barras = [];
        foreach ($serie as $i => $fila) {
            $valor = (float) $fila[$campo];
            $h     = $topeEje > 0 ? ($valor / $topeEje) * $altoPlot : 0.0;
            $x     = $margenIzq + $i * $anchoSlot + ($anchoSlot - $anchoBarra) / 2;
            $y     = $margenSup + ($altoPlot - $h);
            $r     = min(4, $h / 2, $anchoBarra / 2);

            [$corta, $completa] = $this->etiquetaPeriodo($fila['periodo'], $corte);

            $barras[] = [
                'x' => $x, 'w' => $anchoBarra, 'y' => $y, 'h' => $h,
                'path'             => $this->pathBarra($x, $y, $anchoBarra, $h, $r),
                'valor'            => $valor,
                'etiquetaCorta'    => $corta,
                'etiquetaCompleta' => $completa,
                'mostrarEtiqueta'  => ($i % $pasoEtiqueta === 0) || $i === $n - 1,
                'esMax'            => ($i === $indiceMax && $valor > 0),
            ];
        }

        $lineas = [];
        foreach ($niveles as $nivel) {
            $y = $margenSup + $altoPlot - ($topeEje > 0 ? ($nivel / $topeEje) * $altoPlot : 0);
            $lineas[] = ['y' => $y, 'valor' => $nivel];
        }

        return [
            'anchoTotal' => $anchoTotal,
            'altoTotal'  => $altoTotal,
            'margenIzq'  => $margenIzq,
            'margenSup'  => $margenSup,
            'altoPlot'   => $altoPlot,
            'anchoSlot'  => $anchoSlot,
            'barras'     => $barras,
            'lineas'     => $lineas,
        ];
    }

    private function nivelesEje(float $max): array
    {
        if ($max <= 0) return [0.0];

        $pasos = 4;
        $bruto = $max / $pasos;
        $mag   = 10 ** floor(log10($bruto));
        $norm  = $bruto / $mag;
        $paso  = ($norm <= 1 ? 1 : ($norm <= 2 ? 2 : ($norm <= 5 ? 5 : 10))) * $mag;
        $tope  = ceil($max / $paso) * $paso;

        $niveles = [];
        for ($v = 0.0; $v <= $tope + $paso * 0.001; $v += $paso) {
            $niveles[] = $v;
        }

        return $niveles;
    }

    private function etiquetaPeriodo(string $periodo, string $corte): array
    {
        if ($corte === 'mes') {
            [$y, $m] = explode('-', $periodo);
            $m = (int) $m;
            return [self::MESES_ABREV[$m] . " '" . substr($y, -2), self::MESES_FULL[$m] . ' ' . $y];
        }

        $t = strtotime($periodo);
        return [date('d/m', $t), date('d/m/Y', $t)];
    }

    private function pathBarra(float $x, float $y, float $w, float $h, float $r): string
    {
        if ($h <= 0) return '';

        return sprintf(
            'M%.2f,%.2f L%.2f,%.2f A%.2f,%.2f 0 0 1 %.2f,%.2f L%.2f,%.2f A%.2f,%.2f 0 0 1 %.2f,%.2f L%.2f,%.2f Z',
            $x, $y + $h,
            $x, $y + $r,
            $r, $r, $x + $r, $y,
            $x + $w - $r, $y,
            $r, $r, $x + $w, $y + $r,
            $x + $w, $y + $h
        );
    }
}
