<?php

namespace App\Models;

use CodeIgniter\Model;

class KematianModel extends Model
{
    protected $table            = 'kematian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = '\App\Entities\Kematian';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_penduduk', 'tanggal', 'tempat', 'sebab',
        'nik_pelapor', 'status', 'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_penduduk' => 'required|is_not_unique[penduduk.id]',
        'tanggal'     => 'required|valid_date',
        'tempat'      => 'permit_empty|max_length[255]',
        'sebab'       => 'permit_empty|max_length[255]',
        'nik_pelapor' => 'permit_empty|exact_length[16]',
        'status'      => 'permit_empty|in_list[pending,approved,rejected,batal]',
        'keterangan'  => 'permit_empty|max_length[255]',
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
