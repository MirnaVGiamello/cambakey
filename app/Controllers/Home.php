<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('usuario_id')) {
            return redirect()->to('/login');
        }

        return redirect()->to(session()->get('perfil') === 'admin' ? '/dashboard' : '/ventas');
    }
}
