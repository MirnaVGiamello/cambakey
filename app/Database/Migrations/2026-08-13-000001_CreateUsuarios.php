<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'usuario'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'perfil'       => ['type' => 'ENUM', 'constraint' => ['admin', 'ventas'], 'default' => 'ventas'],
            'activo'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('usuario');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
