<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Kematian extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getPelapor()
    {
        $pendudukModel = new \App\Models\PendudukModel();
        $penduduk = $pendudukModel->where('nik', $this->nik_pelapor)->first();

        return $penduduk ? $penduduk->nama : 'Tidak diketahui';
    }
}
