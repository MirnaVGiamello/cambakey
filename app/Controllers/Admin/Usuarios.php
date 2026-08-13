<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    private UsuarioModel $model;

    public function __construct() { $this->model = new UsuarioModel(); }

    public function index()
    {
        return view('admin/usuarios/index', [
            'usuarios' => $this->model->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo()
    {
        return view('admin/usuarios/form', ['usuario' => null, 'accion' => 'Nuevo usuario']);
    }

    public function guardar()
    {
        $this->model->insert([
            'usuario'  => $this->request->getPost('usuario'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nombre'   => $this->request->getPost('nombre'),
            'perfil'   => $this->request->getPost('perfil'),
            'activo'   => 1,
        ]);

        return redirect()->to('/admin/usuarios')->with('ok', 'Usuario creado.');
    }

    public function editar(int $id)
    {
        return view('admin/usuarios/form', ['usuario' => $this->model->find($id), 'accion' => 'Editar usuario']);
    }

    public function actualizar(int $id)
    {
        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'perfil' => $this->request->getPost('perfil'),
            'activo' => (int) $this->request->getPost('activo'),
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $datos['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->model->update($id, $datos);

        return redirect()->to('/admin/usuarios')->with('ok', 'Usuario actualizado.');
    }

    public function eliminar(int $id)
    {
        if ($id === (int) session()->get('usuario_id')) {
            return redirect()->to('/admin/usuarios')->with('error', 'No podés dar de baja tu propio usuario.');
        }

        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/admin/usuarios')->with('ok', 'Usuario dado de baja.');
    }
}
