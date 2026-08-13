<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ProductoModel;

class Informes extends BaseController
{
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
}
