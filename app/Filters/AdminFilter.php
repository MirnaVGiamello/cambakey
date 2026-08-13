<?php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('usuario_id')) {
            return redirect()->to('/login');
        }
        if (session()->get('perfil') !== 'admin') {
            return redirect()->to('/ventas')->with('error', 'Acceso restringido a administradores.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
