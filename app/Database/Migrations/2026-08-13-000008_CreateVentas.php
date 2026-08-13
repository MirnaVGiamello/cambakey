<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateVentas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'fecha'       => ['type' => 'DATETIME'],
            'producto_id' => ['type' => 'INT'],
            'cantidad'    => ['type' => 'INT'],
            'precio'      => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'usuario_id'  => ['type' => 'INT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('producto_id', 'productos', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', '', 'SET NULL');
        $this->forge->createTable('ventas');
    }

    public function down()
    {
        $this->forge->dropTable('ventas');
    }
}
