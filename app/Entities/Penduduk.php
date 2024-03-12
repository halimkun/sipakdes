<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Penduduk extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'nik' => 'string',
        'kk' => 'string',
    ];

    // fields
    // 'kk', 'nik', 'nama', 'tempat_lahir', 'kewarganegaraan',
    // 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'is_verified', 'is_kepala_keluarga',
    // 'agama', 'pendidikan', 'jenis_pekerjaan', 'hubungan', 'status_perkawinan',
    // 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi'

    // make fungsion to return true or false for data is full filled 
    public function isFullFilled()
    {
        return $this->kk && $this->nik && $this->nama && $this->tempat_lahir && $this->kewarganegaraan &&
            $this->tanggal_lahir && $this->jenis_kelamin && $this->golongan_darah && $this->agama &&
            $this->pendidikan && $this->jenis_pekerjaan && $this->hubungan && $this->status_perkawinan &&
            $this->rt && $this->rw && $this->kelurahan && $this->kecamatan && $this->kabupaten && $this->provinsi;
    }

    // function to return full address
    public function fullAddress()
    {
        return "RT " . $this->rt . " RW " . $this->rw . " " . $this->kelurahan . " " . $this->kecamatan . " " . $this->kabupaten . " " . $this->provinsi;
    }
}
