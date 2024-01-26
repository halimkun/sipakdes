<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyKeluarga extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');


        // id, kk, nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, golongan_darah, agama, pendidikan, jenis_pekerjaan, hubungan
        for ($i=0; $i < 3; $i++) { 
            $data = [
                'kk' => $faker->nik,
                'nik' => $faker->nik,
                'nama' => $faker->name,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'golongan_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                'pendidikan' => $faker->randomElement(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'S1', 'S2', 'S3']),
                'jenis_pekerjaan' => $faker->randomElement(['Tidak Bekerja', 'Pelajar/Mahasiswa', 'PNS', 'TNI', 'POLRI', 'Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Ibu Rumah Tangga', 'Lainnya']),
                'hubungan' => $faker->randomElement(['Kepala Keluarga', 'Ayah', 'Ibu', 'Anak']),
            ];

            // using keluarga model
            $keluarga = new \App\Models\KeluargaModel();
            $keluarga->insert($data);
        }
    }
}
