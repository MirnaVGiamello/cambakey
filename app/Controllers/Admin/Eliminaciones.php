<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\EliminacionModel;

class Eliminaciones extends BaseController
{
    public function index()
    {
        $filtros = [
            'tipo'  => $this->request->getGet('tipo'),
            'desde' => $this->request->getGet('desde'),
            'hasta' => $this->request->getGet('hasta'),
        ];

        $eliminaciones = (new EliminacionModel())->filtrar($filtros);

        return view('admin/eliminaciones/index', [
            'eliminaciones' => $eliminaciones,
            'filtros'       => $filtros,
        ]);
    }
}
