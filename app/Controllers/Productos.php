<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ProveedorModel;
use App\Models\TipoProductoModel;
use App\Models\TalleModel;
use App\Models\ColorModel;

class Productos extends BaseController
{
    private ProductoModel $model;

    public function __construct()
    {
        $this->model = new ProductoModel();
    }

    private function combos(): array
    {
        return [
            'tipos'       => (new TipoProductoModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'proveedores' => (new ProveedorModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'talles'      => (new TalleModel())->where('activo', 1)->orderBy('nombre')->findAll(),
            'colores'     => (new ColorModel())->where('activo', 1)->orderBy('nombre')->findAll(),
        ];
    }

    public function index()
    {
        $filtros = [
            'tipo_producto_id' => $this->request->getGet('tipo_producto_id'),
            'proveedor_id'     => $this->request->getGet('proveedor_id'),
            'talle_id'         => $this->request->getGet('talle_id'),
            'color_id'         => $this->request->getGet('color_id'),
            'texto'            => $this->request->getGet('texto'),
            'stock'            => $this->request->getGet('stock'),
        ];

        return view('productos/index', array_merge($this->combos(), [
            'productos' => $this->model->filtrar($filtros),
            'filtros'   => $filtros,
        ]));
    }

    public function nuevo()
    {
        return view('productos/form', array_merge($this->combos(), [
            'producto' => null,
            'accion'   => 'Nuevo producto',
        ]));
    }

    private function datosComunes(): array
    {
        return [
            'tipo_producto_id' => $this->request->getPost('tipo_producto_id'),
            'proveedor_id'     => $this->request->getPost('proveedor_id'),
            'descripcion'      => $this->request->getPost('descripcion'),
            'observacion'      => $this->request->getPost('observacion'),
            'costo'            => $this->limpiarMonto($this->request->getPost('costo')) ?? 0,
            'precio_venta'     => $this->limpiarMonto($this->request->getPost('precio_venta')) ?? 0,
            'stock_minimo'     => $this->request->getPost('stock_minimo') ?: 0,
        ];
    }

    private function datosPost(): array
    {
        return array_merge($this->datosComunes(), [
            'talle_id' => $this->request->getPost('talle_id'),
            'color_id' => $this->request->getPost('color_id'),
        ]);
    }

    public function guardar()
    {
        $talleIds = (array) $this->request->getPost('talle_id');
        $colorIds = (array) $this->request->getPost('color_id');
        $comunes  = $this->datosComunes();
        $stockActual = $this->request->getPost('stock_actual') ?: 0;

        $creados = 0;
        $existentes = 0;

        foreach ($talleIds as $talleId) {
            foreach ($colorIds as $colorId) {
                if (!$talleId || !$colorId) continue;

                $yaExiste = $this->model
                    ->where('tipo_producto_id', $comunes['tipo_producto_id'])
                    ->where('proveedor_id', $comunes['proveedor_id'])
                    ->where('descripcion', $comunes['descripcion'])
                    ->where('talle_id', $talleId)
                    ->where('color_id', $colorId)
                    ->where('activo', 1)
                    ->first();

                if ($yaExiste) {
                    $existentes++;
                    continue;
                }

                $this->model->insert(array_merge($comunes, [
                    'talle_id'     => $talleId,
                    'color_id'     => $colorId,
                    'stock_actual' => $stockActual,
                    'activo'       => 1,
                ]));
                $creados++;
            }
        }

        if ($creados === 0) {
            return redirect()->to('/productos/nuevo')->with('error', 'Seleccioná al menos un talle y un color.')->withInput();
        }

        $mensaje = $creados === 1 ? '1 producto creado.' : "{$creados} productos creados (todas las combinaciones de talle y color).";
        if ($existentes > 0) {
            $mensaje .= " {$existentes} combinación(es) ya existían y se omitieron.";
        }

        return redirect()->to('/productos')->with('ok', $mensaje);
    }

    public function precios()
    {
        return view('productos/precios', [
            'productos' => $this->model->filtrar(),
        ]);
    }

    public function actualizarPrecios()
    {
        $descripcion = $this->request->getPost('descripcion');
        $costo       = $this->limpiarMonto($this->request->getPost('costo'));
        $precioVenta = $this->limpiarMonto($this->request->getPost('precio_venta'));

        $datos = [];
        if ($costo !== null)       $datos['costo'] = $costo;
        if ($precioVenta !== null) $datos['precio_venta'] = $precioVenta;

        if (!$descripcion || !$datos) {
            return redirect()->to('/productos/precios')->with('error', 'Elegí un producto e ingresá al menos un precio nuevo.')->withInput();
        }

        $variantes = $this->model->where('descripcion', $descripcion)->where('activo', 1)->findAll();
        foreach ($variantes as $v) {
            $this->model->update($v['id'], $datos);
        }

        $cantidad = count($variantes);
        $mensaje  = $cantidad === 1
            ? 'Precio actualizado en 1 producto.'
            : "Precios actualizados en {$cantidad} variantes de \"{$descripcion}\".";

        return redirect()->to('/productos')->with('ok', $mensaje);
    }

    public function editar(int $id)
    {
        return view('productos/form', array_merge($this->combos(), [
            'producto' => $this->model->find($id),
            'accion'   => 'Editar producto',
        ]));
    }

    public function actualizar(int $id)
    {
        $this->model->update($id, $this->datosPost());

        return redirect()->to('/productos')->with('ok', 'Producto actualizado.');
    }

    public function eliminar(int $id)
    {
        $this->model->update($id, ['activo' => 0]);

        return redirect()->to('/productos')->with('ok', 'Producto dado de baja.');
    }
}
