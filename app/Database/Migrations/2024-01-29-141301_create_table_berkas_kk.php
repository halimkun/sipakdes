<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableBerkasKk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'kk'            => ['type' => 'VARCHAR', 'constraint' => 16],
            'berkas'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'        => ['type' => 'ENUM', 'constraint' => ['valid', 'invalid', 'pending'], 'default' => 'pending'],
            'keterangan'    => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kk', 'penduduk', 'kk', 'CASCADE', 'CASCADE');
        $this->forge->createTable('berkas_kk', true);
    }

    public function down()
    {
        $this->forge->dropTable('berkas_kk', true);
    }
}
