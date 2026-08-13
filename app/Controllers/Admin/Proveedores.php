<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\ProveedorModel;

class Proveedores extends BaseController
{
    private ProveedorModel $model;

    public function __construct() { $this->model = new ProveedorModel(); }

    public function index()
    {
        return view('admin/proveedores/index', [
            'proveedores' => $this->model->buscar($this->request->getGet('texto')),
            'texto'       => $this->request->getGet('texto'),
        ]);
    }

    public function nuevo()
    {
        return view('admin/proveedores/form', ['proveedor' => null, 'accion' => 'Nuevo proveedor']);
    }

    private function datosPost(): array
    {
        return [
            'nombre'      => $this->request->getPost('nombre'),
            'domicilio'   => $this->request->getPost('domicilio'),
            'contacto'    => $this->request->getPost('contacto'),
            'observacion' => $this->request->getPost('observacion'),
        ];
    }

    public function guardar()
    {
        $datos = $this->datosPost();
        $datos['activo'] = 1;
        $this->model->insert($datos);

        return redirect()->to('/admin/proveedores')->with('ok', 'Proveedor creado.');
    }

    public function editar(int $id)
    {
        return view('admin/proveedores/form', ['proveedor' => $this->model->find($id), 'accion' => 'Editar proveedor']);
    }

    public function actualizar(int $id)
    {
        $datos = $this->datosPost();
        $datos['activo'] = (int) $this->request->getPost('activo');
        $this->model->update($id, $datos);

        return redirect()->to('/admin/proveedores')->with('ok', 'Proveedor actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/proveedores')->with('ok', 'Proveedor dado de baja.');
    }
}
