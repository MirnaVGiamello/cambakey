<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table         = 'productos';
    protected $allowedFields = [
        'tipo_producto_id', 'proveedor_id', 'descripcion', 'talle_id', 'color_id',
        'observacion', 'foto', 'costo', 'precio_venta', 'stock_actual', 'stock_minimo', 'activo',
    ];
    protected $useTimestamps = true;

    private function baseSelect()
    {
        return $this->select('productos.*, tp.nombre AS tipo_nombre, pr.nombre AS proveedor_nombre, ta.nombre AS talle_nombre, co.nombre AS color_nombre')
            ->join('tipos_producto tp', 'tp.id = productos.tipo_producto_id')
            ->join('proveedores pr', 'pr.id = productos.proveedor_id')
            ->join('talles ta', 'ta.id = productos.talle_id')
            ->join('colores co', 'co.id = productos.color_id');
    }

    public function filtrar(array $filtros = []): array
    {
        $builder = $this->baseSelect()->where('productos.activo', 1);

        if (!empty($filtros['tipo_producto_id'])) $builder->where('productos.tipo_producto_id', $filtros['tipo_producto_id']);
        if (!empty($filtros['proveedor_id']))     $builder->where('productos.proveedor_id', $filtros['proveedor_id']);
        if (!empty($filtros['talle_id']))         $builder->where('productos.talle_id', $filtros['talle_id']);
        if (!empty($filtros['color_id']))         $builder->where('productos.color_id', $filtros['color_id']);
        if (!empty($filtros['descripcion']))      $builder->where('productos.descripcion', $filtros['descripcion']);
        if (!empty($filtros['texto']))            $builder->like('productos.descripcion', $filtros['texto']);
        if (($filtros['stock'] ?? '') === 'bajo') $builder->where('productos.stock_actual <=', 'productos.stock_minimo', false);
        if (($filtros['stock'] ?? '') === 'ok')   $builder->where('productos.stock_actual >', 'productos.stock_minimo', false);

        return $builder->orderBy('productos.descripcion')->findAll();
    }

    public function conDetalle(int $id)
    {
        return $this->baseSelect()->where('productos.id', $id)->first();
    }

    public function bajoStockPorProveedor(): array
    {
        $productos = $this->baseSelect()
            ->where('productos.activo', 1)
            ->where('productos.stock_actual <=', 'productos.stock_minimo', false)
            ->orderBy('pr.nombre')
            ->orderBy('productos.descripcion')
            ->findAll();

        $porProveedor = [];
        foreach ($productos as $p) {
            $porProveedor[$p['proveedor_nombre']][] = $p;
        }

        return $porProveedor;
    }

    public function stockValorizado(bool $agrupado = false): array
    {
        $filas = $this->filtrar();

        foreach ($filas as &$f) {
            $f['valor'] = $f['costo'] * $f['stock_actual'];
        }
        unset($f);

        if (!$agrupado) {
            return $filas;
        }

        $grupos = [];
        foreach ($filas as $f) {
            $key = $f['descripcion'];
            if (!isset($grupos[$key])) {
                $grupos[$key] = [
                    'descripcion'      => $f['descripcion'],
                    'tipo_nombre'      => $f['tipo_nombre'],
                    'proveedor_nombre' => $f['proveedor_nombre'],
                    'stock_actual'     => 0,
                    'valor'            => 0,
                ];
            }
            $grupos[$key]['stock_actual'] += $f['stock_actual'];
            $grupos[$key]['valor']        += $f['valor'];
        }

        return array_values($grupos);
    }

    public function incrementarStock(int $id, int $cantidad): void
    {
        $this->set('stock_actual', 'stock_actual + ' . (int) $cantidad, false)->where('id', $id)->update();
    }

    public function decrementarStock(int $id, int $cantidad): void
    {
        $this->set('stock_actual', 'stock_actual - ' . (int) $cantidad, false)->where('id', $id)->update();
    }
}
