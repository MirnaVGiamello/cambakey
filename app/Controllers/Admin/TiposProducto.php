<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\TipoProductoModel;

class TiposProducto extends BaseController
{
    private TipoProductoModel $model;

    public function __construct() { $this->model = new TipoProductoModel(); }

    public function index()
    {
        return view('admin/tipos_producto/index', [
            'tipos' => $this->model->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('admin/tipos_producto/form', ['tipo' => null, 'accion' => 'Nuevo tipo de producto']);
    }

    public function guardar()
    {
        $this->model->insert(['nombre' => $this->request->getPost('nombre'), 'activo' => 1]);

        return redirect()->to('/admin/tipos-producto')->with('ok', 'Tipo de producto creado.');
    }

    public function editar(int $id)
    {
        return view('admin/tipos_producto/form', ['tipo' => $this->model->find($id), 'accion' => 'Editar tipo de producto']);
    }

    public function actualizar(int $id)
    {
        $this->model->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'activo' => (int) $this->request->getPost('activo'),
        ]);

        return redirect()->to('/admin/tipos-producto')->with('ok', 'Tipo de producto actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/tipos-producto')->with('ok', 'Tipo de producto dado de baja.');
    }
}
