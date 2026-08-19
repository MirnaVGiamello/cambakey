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
            return redirect()->to('/ventas/nueva')->with('error', 'Agregá al menos un producto válido a la venta.')->withInput();
        }

        $db = db_connect();
        $db->transStart();

        $avisos = [];
        foreach ($lineas as $l) {
            $this->model->insert([
                'fecha'       => date('Y-m-d H:i:s'),
                'producto_id' => $l['producto']['id'],
                'cantidad'    => $l['cantidad'],
                'precio'      => $l['precio'],
                'usuario_id'  => session()->get('usuario_id'),
            ]);
            $this->productoModel->decrementarStock($l['producto']['id'], $l['cantidad']);

            $stockResultante = $l['producto']['stock_actual'] - $l['cantidad'];
            if ($stockResultante < 0) {
                $avisos[] = "\"{$l['producto']['descripcion']}\" quedó con stock negativo ({$stockResultante}).";
            }
        }

        $db->transComplete();

        $mensaje = count($lineas) === 1 ? 'Venta registrada.' : 'Venta registrada: ' . count($lineas) . ' productos.';
        if ($avisos) {
            $mensaje .= ' Atención: ' . implode(' ', $avisos);
        }

        return redirect()->to('/ventas')->with('ok', $mensaje);
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
