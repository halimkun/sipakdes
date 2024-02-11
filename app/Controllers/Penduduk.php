<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Penduduk extends BaseController
{
    protected $pendudukModel;

    public function __construct()
    {
        $this->pendudukModel = new \App\Models\PendudukModel();
    }

    public function index()
    {
        $penduduk = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status, berkas_kk.keterangan')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->orderBy('penduduk.nama', 'ASC')
            ->findAll();

        return view('penduduk/index', [
            'penduduk' => $penduduk,
        ]);
    }
}
