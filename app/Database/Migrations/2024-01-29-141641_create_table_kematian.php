<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kematian extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                     => [ 'type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_penduduk'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tanggal'                => [ 'type' => 'DATETIME'],
            'tempat'                 => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sebab'                  => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            'nik_pelapor'            => [ 'type' => 'VARCHAR', 'constraint' => 16, 'null' => true],
            
            'status'                 => [ 'type' => 'ENUM', 'constraint' => [' ', 'invalid', 'pending'], 'default' => 'pending'],
            
            'created_at'             => [ 'type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at'             => [ 'type' => 'DATETIME', 'null' => true, 'default' => null],
            'deleted_at'             => [ 'type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_penduduk', 'penduduk', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('kematian', true);
    }

    public function down()
    {
        $this->forge->dropTable('kematian', true);
    }
}
