<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyPenduduk extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $fake_kk = [
            "8201225910076162",
            "5102011005066144",
            "9109784907972856",
            "6301024405194822",
            "3529272511174251",
            "1605895212064275",
            "9115190407097646",
            "1212462011085762",
            "3527365703229647",
            "7405554708981986",
            "3508841509125249",
        ];
        
        $userModel = new \App\Models\UserModel();
        $user = $userModel->findAll();

        foreach ($user as $key => $value) {
            $data = [
                'kk' => $faker->randomElement($fake_kk),
                'nik' => $faker->nik,
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date,
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'golongan_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                'pendidikan' => $faker->randomElement(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'S1', 'S2', 'S3']),
                'jenis_pekerjaan' => $faker->randomElement(['Tidak Bekerja', 'Pelajar/Mahasiswa', 'PNS', 'TNI', 'POLRI', 'Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Ibu Rumah Tangga', 'Lainnya']),
                'hubungan' => $faker->randomElement(['Kepala Keluarga', 'Ayah', 'Ibu', 'Anak']),
                'kewarganegaraan' => $faker->randomElement(['WNI', 'WNA']),
                'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
                'rt' => $faker->numberBetween(1, 8),
                'rw' => $faker->numberBetween(1, 4),
                'kelurahan' => 'cepagan',
                'kecamatan' => 'warungasem',
                'kabupaten' => 'batang',
                'provinsi' => 'jawa tengah',
                'is_verified' => $faker->boolean(50),
            ];

            // using penduduk model
            $penduduk = new \App\Models\PendudukModel();
            $penduduk->insert($data);

            $penduduk_id = $penduduk->getInsertID();
            $userModel->update($value->id, ['id_penduduk' => $penduduk_id]);
        }
    }
}
