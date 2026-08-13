# Cómo subir Cambakey a producción (hosting compartido)

Pensado para un hosting compartido tipo Hostinger, con Gestor de archivos + phpMyAdmin
(sin SSH), igual que la bicicletería.

La carpeta del sitio en el servidor debe tener la misma estructura que este repo:
`app/`, `public/`, `vendor/`, `writable/`, `.env`, `.htaccess`.

## Primera instalación

1. Generar localmente el paquete completo (con `vendor/` incluido, ya que el hosting
   no tiene Composer): correr `composer install --no-dev --optimize-autoloader` antes
   de armar el zip.
2. Subir todo el contenido del proyecto a `public_html/cambakey/` (o el subdominio que
   corresponda), **excepto** `.git/`.
3. Renombrar `.htaccess.production` a `.htaccess` dentro de esa carpeta, y ajustar la
   ruta `/cambakey/` si el subdirectorio tiene otro nombre.
4. Copiar `env.template` a `.env` y completar los datos reales del hosting:
   - `CI_ENVIRONMENT = production`
   - `app.baseURL` con la URL final (ej. `https://tusitio.com/cambakey/`)
   - `database.default.*` con los datos de la base MySQL creada en el panel de Hostinger
5. Crear la base de datos MySQL desde el panel del hosting y, con phpMyAdmin, importar
   el `.sql` con la estructura (exportado desde el entorno local tras correr
   `php spark migrate --all` y `php spark db:seed MainSeeder`).
6. Dar permisos de escritura a la carpeta `writable/` (y sus subcarpetas
   `cache`, `logs`, `session`, `debugbar`) si el hosting lo requiere.
7. Probar el login con el usuario admin y cambiar la contraseña por defecto.

## Actualizaciones posteriores

**Regla general: solo se actualiza la carpeta `app/`.** `vendor/` y `public/` no cambian
salvo que se agregue una librería o assets nuevos. `writable/` y `.env` **nunca se tocan**.

1. Backup: renombrar `app` a `app_backup` (o `app_backup_FECHA`) en el Gestor de archivos.
2. Subir el zip con la carpeta `app/` actualizada y extraer.
3. Si hay una migración nueva: entrar a phpMyAdmin → pestaña **SQL** → correr el script
   indicado.
4. Probar el sitio. Si todo funciona, borrar `app_backup`; si algo se rompe, restaurar
   el backup.
