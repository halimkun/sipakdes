<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Kelahiran extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getIbu()
    {
        $pendudukModel = new \App\Models\PendudukModel();
        $penduduk = $pendudukModel->where('kk', $this->kk)->where('hubungan', 'Ibu')->first();

        return $penduduk ? $penduduk->nama : '-';
    }
}
