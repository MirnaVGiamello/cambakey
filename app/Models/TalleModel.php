<?php
namespace App\Models;
use CodeIgniter\Model;

class TalleModel extends Model
{
    protected $table         = 'talles';
    protected $allowedFields = ['nombre', 'activo'];
    protected $useTimestamps = true;
}
