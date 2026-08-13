<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateProductos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'auto_increment' => true],
            'tipo_producto_id' => ['type' => 'INT'],
            'proveedor_id'     => ['type' => 'INT'],
            'descripcion'      => ['type' => 'VARCHAR', 'constraint' => 200],
            'talle_id'         => ['type' => 'INT'],
            'color_id'         => ['type' => 'INT'],
            'observacion'      => ['type' => 'TEXT', 'null' => true],
            'costo'            => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'precio_venta'     => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'stock_actual'     => ['type' => 'INT', 'default' => 0],
            'stock_minimo'     => ['type' => 'INT', 'default' => 0],
            'activo'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipo_producto_id', 'tipos_producto', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('proveedor_id', 'proveedores', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('talle_id', 'talles', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('color_id', 'colores', 'id', '', 'RESTRICT');
        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}
