<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\TalleModel;

class Talles extends BaseController
{
    private TalleModel $model;

    public function __construct() { $this->model = new TalleModel(); }

    public function index()
    {
        return view('admin/talles/index', [
            'talles' => $this->model->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('admin/talles/form', ['talle' => null, 'accion' => 'Nuevo talle']);
    }

    public function guardar()
    {
        $this->model->insert(['nombre' => $this->request->getPost('nombre'), 'activo' => 1]);

        return redirect()->to('/admin/talles')->with('ok', 'Talle creado.');
    }

    public function editar(int $id)
    {
        return view('admin/talles/form', ['talle' => $this->model->find($id), 'accion' => 'Editar talle']);
    }

    public function actualizar(int $id)
    {
        $this->model->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'activo' => (int) $this->request->getPost('activo'),
        ]);

        return redirect()->to('/admin/talles')->with('ok', 'Talle actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/talles')->with('ok', 'Talle dado de baja.');
    }
}
