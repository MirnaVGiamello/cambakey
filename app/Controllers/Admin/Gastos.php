<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\GastoModel;
use App\Models\TipoGastoModel;

class Gastos extends BaseController
{
    private GastoModel $model;
    private TipoGastoModel $tipoModel;

    public function __construct()
    {
        $this->model     = new GastoModel();
        $this->tipoModel = new TipoGastoModel();
    }

    public function index()
    {
        $filtros = [
            'desde'         => $this->request->getGet('desde') ?: date('Y-m-01'),
            'hasta'         => $this->request->getGet('hasta') ?: date('Y-m-d'),
            'tipo_gasto_id' => $this->request->getGet('tipo_gasto_id'),
        ];

        $gastos = $this->model->filtrar($filtros);

        return view('admin/gastos/index', [
            'gastos'  => $gastos,
            'filtros' => $filtros,
            'tipos'   => $this->tipoModel->where('activo', 1)->orderBy('nombre')->findAll(),
            'total'   => array_sum(array_column($gastos, 'importe')),
        ]);
    }

    public function nuevo()
    {
        return view('admin/gastos/form', [
            'tipos' => $this->tipoModel->where('activo', 1)->orderBy('nombre')->findAll(),
        ]);
    }

    public function guardar()
    {
        $tipoGastoId = (int) $this->request->getPost('tipo_gasto_id');
        $fecha       = $this->request->getPost('fecha') ?: date('Y-m-d');
        $importe     = $this->limpiarMonto($this->request->getPost('importe')) ?? 0;

        if (!$tipoGastoId || !$this->tipoModel->find($tipoGastoId) || $importe <= 0) {
            return redirect()->to('/admin/gastos/nuevo')->with('error', 'Ingresá el tipo de gasto y un importe válido.')->withInput();
        }

        $this->model->insert([
            'tipo_gasto_id' => $tipoGastoId,
            'fecha'         => $fecha,
            'importe'       => $importe,
            'usuario_id'    => session()->get('usuario_id'),
        ]);

        return redirect()->to('/admin/gastos')->with('ok', 'Gasto registrado.');
    }
}
