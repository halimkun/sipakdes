<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penduduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kk'              => ['type' => 'VARCHAR', 'constraint' => 16],
            'nik'             => ['type' => 'VARCHAR', 'constraint' => 16],
            'nama'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'tempat_lahir'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal_lahir'   => ['type' => 'DATE'],
            'jenis_kelamin'   => ['type' => 'enum("-","Laki-laki","Perempuan")', 'default' => '-'],
            'golongan_darah'  => ['type' => 'enum("-","A","B","AB","O")', 'default' => '-'],
            'agama'           => ['type' => 'enum("-", "Islam","Kristen","Katolik","Hindu","Budha","Konghucu")', 'default' => '-'],
            'pendidikan'      => ['type' => 'enum("-", "Tidak Sekolah","SD","SMP","SMA","Diploma","S1","S2","S3")', 'default' => '-'],
            'jenis_pekerjaan' => ['type' => 'enum("-", "Tidak Bekerja","Pelajar/Mahasiswa","PNS","TNI","POLRI","Swasta","Wiraswasta","Petani","Nelayan","Ibu Rumah Tangga","Lainnya")', 'default' => '-'],
            'hubungan'        => ['type' => 'enum("-", "Kepala Keluarga","Ayah","Ibu","Anak")', 'default' => '-'],
            'kewarganegaraan' => ['type' => 'enum("-", "WNI","WNA")', 'default' => '-'],
            'status_perkawinan' => ['type' => 'enum("-", "Belum Kawin","Kawin","Cerai Hidup","Cerai Mati")', 'default' => '-'],
            'rt'              => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true, 'default' => 0],
            'rw'              => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true, 'default' => 0],
            'kelurahan'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '-'],
            'kecamatan'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '-'],
            'kabupaten'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '-'],
            'provinsi'        => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '-'],
            'is_verified'     => ['type' => 'BOOLEAN', 'default' => false],
            'created_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('kk');
        $this->forge->createTable('penduduk', true);

        // query to add foreign key from users.kk reference on penduduk.kk
        $this->db->query('ALTER TABLE `users` ADD FOREIGN KEY (`id_penduduk`) REFERENCES `penduduk`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    public function down()
    {
        $this->forge->dropTable('penduduk', true);
    }
}
