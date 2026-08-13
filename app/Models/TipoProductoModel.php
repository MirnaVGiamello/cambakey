<?php
namespace App\Models;
use CodeIgniter\Model;

class TipoProductoModel extends Model
{
    protected $table         = 'tipos_producto';
    protected $allowedFields = ['nombre', 'activo'];
    protected $useTimestamps = true;
}
