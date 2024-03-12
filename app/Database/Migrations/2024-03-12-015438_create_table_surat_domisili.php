<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSuratDomisili extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_penduduk' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'selesai', 'ditolak', 'batal'],
                'default' => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_penduduk', 'penduduk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('domisili', true);
    }

    public function down()
    {
        $this->forge->dropTable('domisili');
    }
}
