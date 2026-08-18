<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateEliminaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'tipo'          => ['type' => 'VARCHAR', 'constraint' => 10],
            'producto_id'   => ['type' => 'INT'],
            'cantidad'      => ['type' => 'INT'],
            'precio'        => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'fecha'         => ['type' => 'DATETIME'],
            'usuario_id'    => ['type' => 'INT', 'null' => true],
            'eliminado_por' => ['type' => 'INT', 'null' => true],
            'eliminado_en'  => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('producto_id', 'productos', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', '', 'SET NULL');
        $this->forge->addForeignKey('eliminado_por', 'usuarios', 'id', '', 'SET NULL');
        $this->forge->createTable('eliminaciones');
    }

    public function down()
    {
        $this->forge->dropTable('eliminaciones');
    }
}
