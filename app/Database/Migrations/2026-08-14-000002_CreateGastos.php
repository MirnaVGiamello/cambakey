<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateGastos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'tipo_gasto_id' => ['type' => 'INT'],
            'fecha'         => ['type' => 'DATE'],
            'importe'       => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'usuario_id'    => ['type' => 'INT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipo_gasto_id', 'tipos_gasto', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', '', 'SET NULL');
        $this->forge->createTable('gastos');
    }

    public function down()
    {
        $this->forge->dropTable('gastos');
    }
}
