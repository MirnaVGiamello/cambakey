<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\ProductoModel;
use App\Models\EliminacionModel;

class Ventas extends BaseController
{
    private VentaModel $model;
    private ProductoModel $productoModel;

    public function __construct()
    {
        $this->model         = new VentaModel();
        $this->productoModel = new ProductoModel();
    }

    public function index()
    {
        $filtros = [
            'desde'       => $this->request->getGet('desde'),
            'hasta'       => $this->request->getGet('hasta'),
            'descripcion' => $this->request->getGet('descripcion'),
            'producto_id' => $this->request->getGet('producto_id'),
        ];

        $ventas = $this->model->filtrar($filtros);

        return view('ventas/index', [
            'ventas'     => $ventas,
            'filtros'    => $filtros,
            'productos'  => $this->productoModel->filtrar(),
            'totalUnidades' => array_sum(array_column($ventas, 'cantidad')),
            'totalVentas'   => array_sum(array_map(fn ($v) => $v['cantidad'] * $v['precio'], $ventas)),
            'resumen'    => $this->model->resumenPorProducto($filtros),
        ]);
    }

    public function nueva()
    {
        return view('ventas/form', [
            'productos' => $this->productoModel->filtrar(),
        ]);
    }

    public function guardar()
    {
        $productoId = (int) $this->request->getPost('producto_id');
        $cantidad   = (int) $this->request->getPost('cantidad');
        $precio     = $this->limpiarMonto($this->request->getPost('precio')) ?? 0;

        $producto = $this->productoModel->find($productoId);
        if (!$producto || $cantidad <= 0) {
            return redirect()->to('/ventas/nueva')->with('error', 'Datos de venta inválidos.')->withInput();
        }

        $db = db_connect();
        $db->transStart();

        $this->model->insert([
            'fecha'       => date('Y-m-d H:i:s'),
            'producto_id' => $productoId,
            'cantidad'    => $cantidad,
            'precio'      => $precio,
            'usuario_id'  => session()->get('usuario_id'),
        ]);
        $this->productoModel->decrementarStock($productoId, $cantidad);

        $db->transComplete();

        $stockResultante = $producto['stock_actual'] - $cantidad;
        if ($stockResultante < 0) {
            return redirect()->to('/ventas')->with('ok', "Venta registrada. Atención: el stock de \"{$producto['descripcion']}\" quedó negativo ({$stockResultante}).");
        }

        return redirect()->to('/ventas')->with('ok', 'Venta registrada.');
    }

    public function eliminar(int $id)
    {
        $venta = $this->model->find($id);
        if (!$venta) {
            return redirect()->to('/ventas')->with('error', 'La venta no existe.');
        }

        $db = db_connect();
        $db->transStart();

        (new EliminacionModel())->registrar('venta', $venta);
        $this->productoModel->incrementarStock($venta['producto_id'], $venta['cantidad']);
        $this->model->delete($id);

        $db->transComplete();

        return redirect()->to('/ventas')->with('ok', 'Venta eliminada.');
    }
}
