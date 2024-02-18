<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Keluarga extends BaseController
{
    protected $berkasKkModel;
    protected $pendudukModel;
    protected $userModel;

    public function __construct()
    {
        $this->berkasKkModel = new \App\Models\BerkasKkModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $keluarga = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status as status_berkas, berkas_kk.keterangan as keterangan_berkas')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->orderBy('penduduk.nama', 'ASC')
            ->where('penduduk.kk', $user->pendudukData()->kk)
            ->findAll();

        // hunungan in [Ayah, Kepala Keluarga]
        $kepalakeluarga = $this->pendudukModel->where('kk', $user->pendudukData()->kk)
            ->whereIn('hubungan', ['Ayah', 'Kepala Keluarga'])
            ->first();

        return view('keluarga/index', [
            'title' => 'Data Keluarga',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Keluarga', 'url' => '/keluarga', 'active' => true],
            ],

            'user' => $user,
            'keluarga' => $keluarga,
            'kepalakeluarga' => $kepalakeluarga,
        ]);
    }
}
