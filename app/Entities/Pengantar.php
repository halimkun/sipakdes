<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pengantar extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function pendudukData()
    {
        return $this->attributes['penduduk'] = (new \App\Models\PendudukModel())->find($this->id_penduduk);
    }
}
