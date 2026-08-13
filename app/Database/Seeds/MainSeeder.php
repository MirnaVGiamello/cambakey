<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Usuario admin por defecto
        $this->db->table('usuarios')->insert([
            'usuario'    => 'admin',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'nombre'     => 'Administrador',
            'perfil'     => 'admin',
            'activo'     => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Usuario de ventas de ejemplo
        $this->db->table('usuarios')->insert([
            'usuario'    => 'ventas',
            'password'   => password_hash('ventas123', PASSWORD_DEFAULT),
            'nombre'     => 'Vendedor/a',
            'perfil'     => 'ventas',
            'activo'     => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (['Remera', 'Pantalón', 'Buzo', 'Camisaco', 'Campera', 'Otros'] as $nombre) {
            $this->db->table('tipos_producto')->insert([
                'nombre' => $nombre, 'activo' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (['S', 'M', 'L', 'XL', 'XXL'] as $nombre) {
            $this->db->table('talles')->insert([
                'nombre' => $nombre, 'activo' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (['Negro', 'Blanco', 'Azul', 'Rojo', 'Verde'] as $nombre) {
            $this->db->table('colores')->insert([
                'nombre' => $nombre, 'activo' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        echo "  Usuarios, tipos de producto, talles y colores iniciales cargados.\n";
    }
}
