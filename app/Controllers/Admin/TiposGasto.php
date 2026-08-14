<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\TipoGastoModel;

class TiposGasto extends BaseController
{
    private TipoGastoModel $model;

    public function __construct() { $this->model = new TipoGastoModel(); }

    public function index()
    {
        return view('admin/tipos_gasto/index', [
            'tipos' => $this->model->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('admin/tipos_gasto/form', ['tipo' => null, 'accion' => 'Nuevo tipo de gasto']);
    }

    public function guardar()
    {
        $this->model->insert(['nombre' => $this->request->getPost('nombre'), 'activo' => 1]);

        return redirect()->to('/admin/tipos-gasto')->with('ok', 'Tipo de gasto creado.');
    }

    public function editar(int $id)
    {
        return view('admin/tipos_gasto/form', ['tipo' => $this->model->find($id), 'accion' => 'Editar tipo de gasto']);
    }

    public function actualizar(int $id)
    {
        $this->model->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'activo' => (int) $this->request->getPost('activo'),
        ]);

        return redirect()->to('/admin/tipos-gasto')->with('ok', 'Tipo de gasto actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/tipos-gasto')->with('ok', 'Tipo de gasto dado de baja.');
    }
}
