<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableUsers extends Migration
{
    public function up()
    {
        // 'id_penduduk'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        $this->forge->addColumn('users', [
            'id_penduduk' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id'
            ]
        ]);

        // index for 'id_penduduk'
        $this->forge->addKey('id_penduduk');

        // drop key for email columns
        $this->forge->dropKey('users', 'email');
    }

    public function down()
    {
        // $this->forge->dropColumn('users', 'id_penduduk');
    }
}
