<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CompraModel;
use App\Models\VentaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $compraModel   = new CompraModel();
        $ventaModel    = new VentaModel();

        $desde = date('Y-m-01');
        $hasta = date('Y-m-t');

        $compras = $compraModel->filtrar(['desde' => $desde, 'hasta' => $hasta]);
        $ventas  = $ventaModel->filtrar(['desde' => $desde, 'hasta' => $hasta]);

        return view('dashboard/index', [
            'cantidadProductos' => $productoModel->where('activo', 1)->countAllResults(),
            'bajoStockCount'    => count($productoModel->filtrar(['stock' => 'bajo'])),
            'totalCompras'      => array_sum(array_map(fn ($c) => $c['cantidad'] * $c['precio'], $compras)),
            'totalVentas'       => array_sum(array_map(fn ($v) => $v['cantidad'] * $v['precio'], $ventas)),
            'unidadesVendidas'  => array_sum(array_column($ventas, 'cantidad')),
            'periodo'           => date('m/Y'),
        ]);
    }
}
