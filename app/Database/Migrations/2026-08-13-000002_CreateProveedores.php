<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateProveedores extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'domicilio'   => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'contacto'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'observacion' => ['type' => 'TEXT', 'null' => true],
            'activo'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('proveedores');
    }

    public function down()
    {
        $this->forge->dropTable('proveedores');
    }
}
