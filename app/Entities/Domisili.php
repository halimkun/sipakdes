<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Domisili extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'int',
        'id_penduduk' => 'int',
    ];

    // get penduduk data by id_penduduk
    public function pendudukData()
    {
        return (new \App\Models\PendudukModel())->find($this->id_penduduk);
    }
}
