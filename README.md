# Cambakey — Sistema de gestión de ventas

Sistema web para llevar el control de productos, proveedores, compras, ventas y stock
del emprendimiento de indumentaria masculina Cambakey. Pensado para usarse
principalmente desde el celular.

Stack: PHP 8.2 (CodeIgniter 4) + MySQL 8, corriendo con Docker para desarrollo.
Pensado para desplegarse igual que los demás sistemas: subiendo `app/`, `public/`,
`vendor/` y `writable/` a un hosting compartido (ver [DEPLOY.md](DEPLOY.md)).

## Requisitos

- Docker Desktop

## Levantar el proyecto (desarrollo)

Primera vez:

```
setup.bat
```

Esto construye la imagen, instala CodeIgniter 4, levanta los contenedores y corre las
migraciones + carga de datos iniciales.

Las próximas veces, para volver a levantar todo:

```
iniciar.bat
```

- App: http://localhost:8090
- phpMyAdmin: http://localhost:8091

### Usuarios de prueba

| Usuario | Contraseña | Perfil |
|---|---|---|
| admin  | admin123  | Administrador (todo el sistema) |
| ventas | ventas123 | Ventas (solo vender y consultar) |

**Cambiar estas contraseñas antes de pasar a producción.**

## Estructura

- `app/Controllers` — lógica de cada módulo (Productos, Ventas, Admin\Compras, etc.)
- `app/Models` — acceso a datos (proveedores, productos, compras, ventas, etc.)
- `app/Database/Migrations` — estructura de la base de datos
- `app/Database/Seeds/MainSeeder.php` — usuario admin y datos base (tipos, talles, colores)
- `app/Views` — vistas Bootstrap 5, mobile-first (layout con menú lateral tipo drawer)
- `app/Filters` — `AuthFilter` (requiere login) y `AdminFilter` (solo perfil admin)

## Módulos (etapa 1)

Proveedores, tipos de producto, talles, colores, productos, compras y ventas, con
actualización automática de stock (las compras suman, las ventas restan y permiten
quedar en negativo con aviso).

## Módulos (etapa 2)

Listado de productos bajo stock mínimo agrupado por proveedor, informes de compras y
ventas con filtros y totales (integrados en las mismas pantallas de Compras/Ventas), y
dashboard con el resumen del negocio (solo perfil admin).

## Comandos útiles dentro del contenedor

```
docker compose exec app php spark migrate --all
docker compose exec app php spark db:seed MainSeeder
docker compose exec app php spark routes
```
