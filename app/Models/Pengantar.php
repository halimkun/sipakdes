<?php

namespace App\Models;

use CodeIgniter\Model;

class Pengantar extends Model
{
    protected $table            = 'pengantar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = '\App\Entities\Pengantar';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_penduduk', 'status', 'tipe', 'keperluan',
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
        'id_penduduk' => 'required|is_not_unique[penduduk.id]',
        'status'      => 'required|in_list[pending,selesai,ditolak,batal]',
        'tipe'        => 'required|in_list[-,skck,kia,sktm]',
        'keperluan'   => 'permit_empty|alpha_numeric_punct|min_length[10]',
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
