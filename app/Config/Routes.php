<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Home::index');

// Autenticación
$routes->get('login',  'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Páginas accesibles para ambos perfiles (admin y ventas): vender y consultar
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('productos', 'Productos::index');

    $routes->get('stock/bajo', 'Stock::bajo');

    $routes->get( 'ventas',         'Ventas::index');
    $routes->get( 'ventas/nueva',   'Ventas::nueva');
    $routes->post('ventas/guardar', 'Ventas::guardar');
});

// Borrado de ventas: solo admin, aunque la carga sea para ambos perfiles.
$routes->group('', ['filter' => 'admin'], function ($routes) {
    $routes->post('ventas/eliminar/(:num)', 'Ventas::eliminar/$1');
});

// Administración: solo perfil admin
$routes->group('', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    // ABM de productos (el listado ya es público para ambos perfiles arriba)
    $routes->get( 'productos/nuevo',             'Productos::nuevo');
    $routes->post('productos/guardar',           'Productos::guardar');
    $routes->get( 'productos/precios',           'Productos::precios');
    $routes->post('productos/precios/actualizar', 'Productos::actualizarPrecios');
    $routes->get( 'productos/editar/(:num)',     'Productos::editar/$1');
    $routes->post('productos/actualizar/(:num)', 'Productos::actualizar/$1');
    $routes->post('productos/eliminar/(:num)',   'Productos::eliminar/$1');

    $routes->group('admin', function ($routes) {
        $routes->get( 'proveedores',                'Admin\Proveedores::index');
        $routes->get( 'proveedores/nuevo',          'Admin\Proveedores::nuevo');
        $routes->post('proveedores/guardar',        'Admin\Proveedores::guardar');
        $routes->get( 'proveedores/editar/(:num)',  'Admin\Proveedores::editar/$1');
        $routes->post('proveedores/actualizar/(:num)', 'Admin\Proveedores::actualizar/$1');
        $routes->post('proveedores/eliminar/(:num)',   'Admin\Proveedores::eliminar/$1');

        $routes->get( 'tipos-producto',                'Admin\TiposProducto::index');
        $routes->get( 'tipos-producto/nuevo',          'Admin\TiposProducto::nuevo');
        $routes->post('tipos-producto/guardar',        'Admin\TiposProducto::guardar');
        $routes->get( 'tipos-producto/editar/(:num)',  'Admin\TiposProducto::editar/$1');
        $routes->post('tipos-producto/actualizar/(:num)', 'Admin\TiposProducto::actualizar/$1');
        $routes->post('tipos-producto/eliminar/(:num)',   'Admin\TiposProducto::eliminar/$1');

        $routes->get( 'talles',                'Admin\Talles::index');
        $routes->get( 'talles/nuevo',          'Admin\Talles::nuevo');
        $routes->post('talles/guardar',        'Admin\Talles::guardar');
        $routes->get( 'talles/editar/(:num)',  'Admin\Talles::editar/$1');
        $routes->post('talles/actualizar/(:num)', 'Admin\Talles::actualizar/$1');
        $routes->post('talles/eliminar/(:num)',   'Admin\Talles::eliminar/$1');

        $routes->get( 'colores',                'Admin\Colores::index');
        $routes->get( 'colores/nuevo',          'Admin\Colores::nuevo');
        $routes->post('colores/guardar',        'Admin\Colores::guardar');
        $routes->get( 'colores/editar/(:num)',  'Admin\Colores::editar/$1');
        $routes->post('colores/actualizar/(:num)', 'Admin\Colores::actualizar/$1');
        $routes->post('colores/eliminar/(:num)',   'Admin\Colores::eliminar/$1');

        $routes->get( 'compras',         'Admin\Compras::index');
        $routes->get( 'compras/nueva',   'Admin\Compras::nueva');
        $routes->post('compras/guardar', 'Admin\Compras::guardar');
        $routes->post('compras/eliminar/(:num)', 'Admin\Compras::eliminar/$1');

        $routes->get( 'usuarios',                'Admin\Usuarios::index');
        $routes->get( 'usuarios/nuevo',          'Admin\Usuarios::nuevo');
        $routes->post('usuarios/guardar',        'Admin\Usuarios::guardar');
        $routes->get( 'usuarios/editar/(:num)',  'Admin\Usuarios::editar/$1');
        $routes->post('usuarios/actualizar/(:num)', 'Admin\Usuarios::actualizar/$1');
        $routes->post('usuarios/eliminar/(:num)',   'Admin\Usuarios::eliminar/$1');

        $routes->get( 'tipos-gasto',                'Admin\TiposGasto::index');
        $routes->get( 'tipos-gasto/nuevo',          'Admin\TiposGasto::nuevo');
        $routes->post('tipos-gasto/guardar',        'Admin\TiposGasto::guardar');
        $routes->get( 'tipos-gasto/editar/(:num)',  'Admin\TiposGasto::editar/$1');
        $routes->post('tipos-gasto/actualizar/(:num)', 'Admin\TiposGasto::actualizar/$1');
        $routes->post('tipos-gasto/eliminar/(:num)',   'Admin\TiposGasto::eliminar/$1');

        $routes->get( 'gastos',         'Admin\Gastos::index');
        $routes->get( 'gastos/nuevo',   'Admin\Gastos::nuevo');
        $routes->post('gastos/guardar', 'Admin\Gastos::guardar');

        $routes->get('informes/stock-valorizado', 'Admin\Informes::stockValorizado');
        $routes->get('informes/ventas-grafico',   'Admin\Informes::ventasGrafico');

        $routes->get('eliminaciones', 'Admin\Eliminaciones::index');
    });
});
