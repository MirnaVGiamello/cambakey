<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddFotoToProductos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('productos', [
            'foto' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'observacion'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('productos', 'foto');
    }
}
