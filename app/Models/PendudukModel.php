<?php

namespace App\Models;

use CodeIgniter\Model;

class PendudukModel extends Model
{
    protected $table            = 'penduduk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = '\App\Entities\Penduduk';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kk', 'nik', 'nama', 'tempat_lahir', 'kewarganegaraan',
        'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'is_verified', 'is_kepala_keluarga',
        'agama', 'pendidikan', 'jenis_pekerjaan', 'hubungan', 'status_perkawinan',
        'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
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

    // firstOrNew
    public function firstOrNew($where, $data = [])
    {
        $penduduk = $this->where($where)->first();
        if ($penduduk) {
            return $penduduk;
        }

        // insert and retun data
        $penduduk = new \App\Entities\Penduduk($data->toArray());
        $this->insert($penduduk);
        
        // return $penduduk;

        // return last inserted data
        return $this->find($this->insertID());
    }
}
