<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\CompraModel;
use App\Models\EliminacionModel;
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
        $productoIds = (array) $this->request->getPost('producto_id');
        $cantidades  = (array) $this->request->getPost('cantidad');
        $precios     = (array) $this->request->getPost('precio');

        $lineas = [];
        foreach ($productoIds as $i => $productoId) {
            $cantidad = (int) ($cantidades[$i] ?? 0);
            $precio   = $this->limpiarMonto($precios[$i] ?? null) ?? 0;
            $producto = $this->productoModel->find((int) $productoId);

            if (!$producto || $cantidad <= 0 || $precio <= 0) continue;

            $lineas[] = ['producto' => $producto, 'cantidad' => $cantidad, 'precio' => $precio];
        }

        if (!$lineas) {
            return redirect()->to('/admin/compras/nueva')->with('error', 'Agregá al menos un producto válido a la compra.')->withInput();
        }

        $db = db_connect();
        $db->transStart();

        foreach ($lineas as $l) {
            $this->model->insert([
                'fecha'       => date('Y-m-d H:i:s'),
                'producto_id' => $l['producto']['id'],
                'cantidad'    => $l['cantidad'],
                'precio'      => $l['precio'],
                'usuario_id'  => session()->get('usuario_id'),
            ]);
            $this->productoModel->incrementarStock($l['producto']['id'], $l['cantidad']);
        }

        $db->transComplete();

        $cantidadLineas = count($lineas);
        $unidades       = array_sum(array_column($lineas, 'cantidad'));
        $mensaje        = $cantidadLineas === 1 ? 'Compra registrada.' : "Compra registrada: {$cantidadLineas} variantes, {$unidades} unidades en total.";

        return redirect()->to('/admin/compras')->with('ok', $mensaje);
    }

    public function eliminar(int $id)
    {
        $compra = $this->model->find($id);
        if (!$compra) {
            return redirect()->to('/admin/compras')->with('error', 'La compra no existe.');
        }

        $db = db_connect();
        $db->transStart();

        (new EliminacionModel())->registrar('compra', $compra);
        $this->productoModel->decrementarStock($compra['producto_id'], $compra['cantidad']);
        $this->model->delete($id);

        $db->transComplete();

        return redirect()->to('/admin/compras')->with('ok', 'Compra eliminada.');
    }
}
