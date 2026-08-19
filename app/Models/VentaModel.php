<?php
namespace App\Models;
use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table         = 'ventas';
    protected $allowedFields = ['fecha', 'producto_id', 'cantidad', 'precio', 'usuario_id'];
    protected $useTimestamps = false;

    public function filtrar(array $filtros = []): array
    {
        $builder = $this->select('ventas.*, productos.descripcion, ta.nombre AS talle_nombre, co.nombre AS color_nombre')
            ->join('productos', 'productos.id = ventas.producto_id')
            ->join('talles ta', 'ta.id = productos.talle_id')
            ->join('colores co', 'co.id = productos.color_id');

        if (!empty($filtros['desde']))       $builder->where('ventas.fecha >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta']))       $builder->where('ventas.fecha <=', $filtros['hasta'] . ' 23:59:59');
        if (!empty($filtros['descripcion'])) $builder->where('productos.descripcion', $filtros['descripcion']);
        if (!empty($filtros['producto_id'])) $builder->where('ventas.producto_id', $filtros['producto_id']);

        return $builder->orderBy('ventas.fecha', 'DESC')->findAll();
    }

    public function resumenPorProducto(array $filtros = []): array
    {
        $builder = $this->select('productos.descripcion, ta.nombre AS talle_nombre, co.nombre AS color_nombre, SUM(ventas.cantidad) AS unidades, SUM(ventas.cantidad * ventas.precio) AS total')
            ->join('productos', 'productos.id = ventas.producto_id')
            ->join('talles ta', 'ta.id = productos.talle_id')
            ->join('colores co', 'co.id = productos.color_id');

        if (!empty($filtros['desde']))       $builder->where('ventas.fecha >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta']))       $builder->where('ventas.fecha <=', $filtros['hasta'] . ' 23:59:59');
        if (!empty($filtros['descripcion'])) $builder->where('productos.descripcion', $filtros['descripcion']);
        if (!empty($filtros['producto_id'])) $builder->where('ventas.producto_id', $filtros['producto_id']);

        return $builder->groupBy('ventas.producto_id')->orderBy('total', 'DESC')->findAll();
    }

    public function rankingPorDescripcion(array $filtros = []): array
    {
        $builder = $this->select('productos.descripcion, SUM(ventas.cantidad) AS unidades, SUM(ventas.cantidad * ventas.precio) AS total')
            ->join('productos', 'productos.id = ventas.producto_id');

        if (!empty($filtros['desde'])) $builder->where('ventas.fecha >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta'])) $builder->where('ventas.fecha <=', $filtros['hasta'] . ' 23:59:59');

        return $builder->groupBy('productos.descripcion')->orderBy('total', 'DESC')->findAll();
    }

    public function serieTemporal(array $filtros = [], string $corte = 'dia'): array
    {
        $formato = $corte === 'mes' ? '%Y-%m' : '%Y-%m-%d';

        $builder = $this->select("DATE_FORMAT(ventas.fecha, '{$formato}') AS periodo, SUM(ventas.cantidad) AS cantidad, SUM(ventas.cantidad * ventas.precio) AS total");

        if (!empty($filtros['desde'])) $builder->where('ventas.fecha >=', $filtros['desde'] . ' 00:00:00');
        if (!empty($filtros['hasta'])) $builder->where('ventas.fecha <=', $filtros['hasta'] . ' 23:59:59');

        return $builder->groupBy('periodo')->orderBy('periodo', 'ASC')->findAll();
    }
}
