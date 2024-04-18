<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }

    public function faker()
    {
        $faker   = \Faker\Factory::create('id_ID');
        $fake_kk = [
            '3204110101010001',
            '3204110101010002',
            '3204110101010003',
            '3204110101010004',
            '3204110101010005',
            '3204110101010006',
            '3204110101010007',
            '3204110101010008',
            '3204110101010009',
            '3204110101010010',
        ];


        $data = [
            'kk'                => $faker->randomElement($fake_kk),
            'nik'               => $faker->nik,
            'nama'              => $faker->firstName . ' ' . $faker->lastName,
            'email'             => $faker->email,
            'tempat_lahir'      => $faker->city,
            'tanggal_lahir'     => $faker->date,
            'jenis_kelamin'     => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'golongan_darah'    => $faker->randomElement(['A', 'B', 'AB', 'O']),
            'agama'             => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
            'pendidikan'        => $faker->randomElement(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'S1', 'S2', 'S3']),
            'jenis_pekerjaan'   => $faker->randomElement(['Tidak Bekerja', 'Pelajar/Mahasiswa', 'PNS', 'TNI', 'POLRI', 'Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Ibu Rumah Tangga', 'Lainnya']),
            'hubungan'          => $faker->randomElement(['Kepala Keluarga', 'Ayah', 'Ibu', 'Anak']),
            'kewarganegaraan'   => $faker->randomElement(['WNI', 'WNA']),
            'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'rt'                => $faker->numberBetween(1, 8),
            'rw'                => $faker->numberBetween(1, 4),
            'kelurahan'         => 'cepagan',
            'kecamatan'         => 'warungasem',
            'kabupaten'         => 'batang',
            'provinsi'          => 'jawa tengah',
            'is_verified'       => $faker->boolean(50),
        ];

        return json_encode($data);
    }
}
