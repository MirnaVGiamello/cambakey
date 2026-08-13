<?php
namespace App\Models;
use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table         = 'compras';
    protected $allowedFields = ['fecha', 'producto_id', 'cantidad', 'precio', 'usuario_id'];
    protected $useTimestamps = false;

    public function filtrar(array $filtros = []): array
    {
        $builder = $this->select('compras.*, productos.descripcion, pr.nombre AS proveedor_nombre, ta.nombre AS talle_nombre, co.nombre AS color_nombre')
            ->join('productos', 'productos.id = compras.producto_id')
            ->join('proveedores pr', 'pr.id = productos.proveedor_id')
            ->join('talles ta', 'ta.id = productos.talle_id')
            ->join('colores co', 'co.id = productos.color_id');

        if (!empty($filtros['desde']))        $builder->where('compras.fecha >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta']))        $builder->where('compras.fecha <=', $filtros['hasta'] . ' 23:59:59');
        if (!empty($filtros['proveedor_id'])) $builder->where('productos.proveedor_id', $filtros['proveedor_id']);
        if (!empty($filtros['descripcion']))  $builder->where('productos.descripcion', $filtros['descripcion']);
        if (!empty($filtros['producto_id']))  $builder->where('compras.producto_id', $filtros['producto_id']);

        return $builder->orderBy('compras.fecha', 'DESC')->findAll();
    }
}
