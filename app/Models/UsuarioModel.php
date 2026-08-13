<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $allowedFields = ['usuario', 'password', 'nombre', 'perfil', 'activo'];
    protected $useTimestamps = true;
    protected $hidden        = ['password'];

    public function findByUsuario(string $usuario)
    {
        return $this->where('usuario', $usuario)->where('activo', 1)->first();
    }
}
