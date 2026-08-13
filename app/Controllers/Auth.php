<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('usuario_id')) {
            return redirect()->to('/');
        }

        return view('auth/login', ['error' => session()->getFlashdata('error')]);
    }

    public function login()
    {
        $usuario  = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');
        $model    = new UsuarioModel();
        $fila     = $model->findByUsuario($usuario);

        if ($fila && password_verify($password, $fila['password'])) {
            session()->set([
                'usuario_id' => $fila['id'],
                'nombre'     => $fila['nombre'],
                'perfil'     => $fila['perfil'],
            ]);

            return redirect()->to($fila['perfil'] === 'admin' ? '/dashboard' : '/ventas');
        }

        return redirect()->back()
            ->with('error', 'Usuario o contraseña incorrectos.')
            ->withInput();
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
