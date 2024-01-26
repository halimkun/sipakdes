<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Keluarga extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kk'                => ['type' => 'VARCHAR', 'constraint' => 16],
            'nik'               => ['type' => 'VARCHAR', 'constraint' => 16],
            'nama'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'tempat_lahir'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal_lahir'     => ['type' => 'DATE'],
            'jenis_kelamin'     => ['type' => 'enum("-","Laki-laki","Perempuan")', 'default' => '-'],
            'golongan_darah'    => ['type' => 'enum("-","A","B","AB","O")', 'default' => '-'],
            'agama'             => ['type' => 'enum("-", "Islam","Kristen","Katolik","Hindu","Budha","Konghucu")', 'default' => '-'],
            'pendidikan'        => ['type' => 'enum("-", "Tidak Sekolah","SD","SMP","SMA","Diploma","S1","S2","S3")', 'default' => '-'],
            'jenis_pekerjaan'   => ['type' => 'enum("-", "Tidak Bekerja","Pelajar/Mahasiswa","PNS","TNI","POLRI","Swasta","Wiraswasta","Petani","Nelayan","Ibu Rumah Tangga","Lainnya")', 'default' => '-'],
            'hubungan'          => ['type' => 'enum("-", "Kepala Keluarga","Ayah","Ibu","Anak")', 'default' => '-'],
            'created_at'        => ['type' => 'DATETIME'],
            'updated_at'        => ['type' => 'DATETIME'],
            'deleted_at'        => ['type' => 'DATETIME'],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('keluarga', true);
    }

    public function down()
    {
        $this->forge->dropTable('keluarga', true);
    }
}
