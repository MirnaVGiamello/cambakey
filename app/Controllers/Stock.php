<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class Stock extends BaseController
{
    public function bajo()
    {
        $model = new ProductoModel();

        return view('stock/bajo', [
            'porProveedor' => $model->bajoStockPorProveedor(),
        ]);
    }
}
