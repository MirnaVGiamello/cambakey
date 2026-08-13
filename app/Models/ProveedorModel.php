<?php
namespace App\Models;
use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table         = 'proveedores';
    protected $allowedFields = ['nombre', 'domicilio', 'contacto', 'observacion', 'activo'];
    protected $useTimestamps = true;

    public function buscar(?string $texto = null)
    {
        $builder = $this->orderBy('nombre');

        if ($texto) {
            $builder = $builder->groupStart()
                ->like('nombre', $texto)
                ->orLike('contacto', $texto)
                ->groupEnd();
        }

        return $builder->findAll();
    }
}
