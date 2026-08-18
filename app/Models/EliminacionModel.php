<?php
namespace App\Models;
use CodeIgniter\Model;

class EliminacionModel extends Model
{
    protected $table         = 'eliminaciones';
    protected $allowedFields = ['tipo', 'producto_id', 'cantidad', 'precio', 'fecha', 'usuario_id', 'eliminado_por', 'eliminado_en'];
    protected $useTimestamps = false;

    public function registrar(string $tipo, array $registro): void
    {
        $this->insert([
            'tipo'          => $tipo,
            'producto_id'   => $registro['producto_id'],
            'cantidad'      => $registro['cantidad'],
            'precio'        => $registro['precio'],
            'fecha'         => $registro['fecha'],
            'usuario_id'    => $registro['usuario_id'] ?? null,
            'eliminado_por' => session()->get('usuario_id'),
            'eliminado_en'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function filtrar(array $filtros = []): array
    {
        $builder = $this->select('eliminaciones.*, productos.descripcion, ta.nombre AS talle_nombre, co.nombre AS color_nombre, ub.nombre AS eliminado_por_nombre, uc.nombre AS cargado_por_nombre')
            ->join('productos', 'productos.id = eliminaciones.producto_id')
            ->join('talles ta', 'ta.id = productos.talle_id')
            ->join('colores co', 'co.id = productos.color_id')
            ->join('usuarios ub', 'ub.id = eliminaciones.eliminado_por', 'left')
            ->join('usuarios uc', 'uc.id = eliminaciones.usuario_id', 'left');

        if (!empty($filtros['tipo']))  $builder->where('eliminaciones.tipo', $filtros['tipo']);
        if (!empty($filtros['desde'])) $builder->where('eliminaciones.eliminado_en >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta'])) $builder->where('eliminaciones.eliminado_en <=', $filtros['hasta'] . ' 23:59:59');

        return $builder->orderBy('eliminaciones.eliminado_en', 'DESC')->findAll();
    }
}
