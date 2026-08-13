<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\CompraModel;
use App\Models\ProductoModel;
use App\Models\ProveedorModel;

class Compras extends BaseController
{
    private CompraModel $model;
    private ProductoModel $productoModel;

    public function __construct()
    {
        $this->model         = new CompraModel();
        $this->productoModel = new ProductoModel();
    }

    public function index()
    {
        $filtros = [
            'desde'        => $this->request->getGet('desde'),
            'hasta'        => $this->request->getGet('hasta'),
            'proveedor_id' => $this->request->getGet('proveedor_id'),
            'descripcion'  => $this->request->getGet('descripcion'),
            'producto_id'  => $this->request->getGet('producto_id'),
        ];

        $compras = $this->model->filtrar($filtros);

        return view('admin/compras/index', [
            'compras'       => $compras,
            'filtros'       => $filtros,
            'proveedores'   => (new ProveedorModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'productos'     => $this->productoModel->filtrar(),
            'totalCompras'  => array_sum(array_map(fn ($c) => $c['cantidad'] * $c['precio'], $compras)),
            'totalUnidades' => array_sum(array_column($compras, 'cantidad')),
        ]);
    }

    public function nueva()
    {
        return view('admin/compras/form', [
            'productos' => $this->productoModel->filtrar(),
        ]);
    }

    public function guardar()
    {
        $precio     = $this->limpiarMonto($this->request->getPost('precio')) ?? 0;
        $cantidades = (array) $this->request->getPost('cantidades');

        $items = [];
        foreach ($cantidades as $productoId => $cantidad) {
            $cantidad = (int) $cantidad;
            if ($cantidad > 0) {
                $items[(int) $productoId] = $cantidad;
            }
        }

        if (!$items || $precio <= 0) {
            return redirect()->to('/admin/compras/nueva')->with('error', 'Ingresá el precio y al menos una cantidad.')->withInput();
        }

        $db = db_connect();
        $db->transStart();

        foreach ($items as $productoId => $cantidad) {
            if (!$this->productoModel->find($productoId)) continue;

            $this->model->insert([
                'fecha'       => date('Y-m-d H:i:s'),
                'producto_id' => $productoId,
                'cantidad'    => $cantidad,
                'precio'      => $precio,
                'usuario_id'  => session()->get('usuario_id'),
            ]);
            $this->productoModel->incrementarStock($productoId, $cantidad);
        }

        $db->transComplete();

        $lineas    = count($items);
        $unidades  = array_sum($items);
        $mensaje   = $lineas === 1 ? 'Compra registrada.' : "Compra registrada: {$lineas} variantes, {$unidades} unidades en total.";

        return redirect()->to('/admin/compras')->with('ok', $mensaje);
    }
}
