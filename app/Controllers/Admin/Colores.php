<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ColorModel;

class Colores extends BaseController
{
    private ColorModel $model;

    public function __construct() { $this->model = new ColorModel(); }

    public function index()
    {
        return view('admin/colores/index', [
            'colores' => $this->model->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('admin/colores/form', ['color' => null, 'accion' => 'Nuevo color']);
    }

    public function guardar()
    {
        $this->model->insert(['nombre' => $this->request->getPost('nombre'), 'activo' => 1]);

        return redirect()->to('/admin/colores')->with('ok', 'Color creado.');
    }

    public function editar(int $id)
    {
        return view('admin/colores/form', ['color' => $this->model->find($id), 'accion' => 'Editar color']);
    }

    public function actualizar(int $id)
    {
        $this->model->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'activo' => (int) $this->request->getPost('activo'),
        ]);

        return redirect()->to('/admin/colores')->with('ok', 'Color actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/colores')->with('ok', 'Color dado de baja.');
    }
}
