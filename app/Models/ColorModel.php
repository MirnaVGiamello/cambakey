<?php
namespace App\Models;
use CodeIgniter\Model;

class ColorModel extends Model
{
    protected $table         = 'colores';
    protected $allowedFields = ['nombre', 'activo'];
    protected $useTimestamps = true;
}
