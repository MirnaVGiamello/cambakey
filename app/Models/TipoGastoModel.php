<?php
namespace App\Models;
use CodeIgniter\Model;

class TipoGastoModel extends Model
{
    protected $table         = 'tipos_gasto';
    protected $allowedFields = ['nombre', 'activo'];
    protected $useTimestamps = true;
}
