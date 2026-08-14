<?php
namespace App\Models;
use CodeIgniter\Model;

class GastoModel extends Model
{
    protected $table         = 'gastos';
    protected $allowedFields = ['tipo_gasto_id', 'fecha', 'importe', 'usuario_id'];
    protected $useTimestamps = false;

    public function filtrar(array $filtros = []): array
    {
        $builder = $this->select('gastos.*, tg.nombre AS tipo_nombre')
            ->join('tipos_gasto tg', 'tg.id = gastos.tipo_gasto_id');

        if (!empty($filtros['desde']))         $builder->where('gastos.fecha >=', $filtros['desde']);
        if (!empty($filtros['hasta']))         $builder->where('gastos.fecha <=', $filtros['hasta']);
        if (!empty($filtros['tipo_gasto_id'])) $builder->where('gastos.tipo_gasto_id', $filtros['tipo_gasto_id']);

        return $builder->orderBy('gastos.fecha', 'DESC')->findAll();
    }
}
