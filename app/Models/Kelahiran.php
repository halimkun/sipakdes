<?php

namespace App\Models;

use CodeIgniter\Model;

class Kelahiran extends Model
{
    protected $table            = 'kelahiran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = '\App\Entities\Kelahiran';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_penduduk', 'status', 'keterangan'
    ];

    protected bool $allowEmptyInserts = false;

      // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

      // Validation
    protected $validationRules      = [
        'id_penduduk' => 'required|is_not_unique[kelahiran.id_penduduk]',
        'status'      => 'required|in_list[pending,selesai,ditolak]',
        'keterangan'  => 'permit_empty|string|max_length[255]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

      // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
