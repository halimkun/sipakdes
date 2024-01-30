<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReqKelahiran extends Migration
{
    public function up()
    {
        // this for table request surat kelahiran (req_kelahiran)
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_penduduk' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'    => ['type' => 'ENUM', 'constraint' => ['pending', 'selesai', 'ditolak'], 'default' => 'pending'],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
            'deleted_at' => ['type' => 'DATETIME'],
        ]); 

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_penduduk', 'penduduk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('req_kelahiran', true);
    }

    public function down()
    {
        $this->forge->dropTable('req_kelahiran', true);
    }
}
