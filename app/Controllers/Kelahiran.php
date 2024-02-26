<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kelahiran extends BaseController
{
    protected $kelahiranModel;

    public function __construct()
    {
        $this->kelahiranModel = new \App\Models\Kelahiran();
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $dk = $this->kelahiranModel->select('kelahiran.*, penduduk.*')
            ->join('penduduk', 'penduduk.id = kelahiran.id_penduduk');

        if (user()->role == 'warga') {
            $dk->where('penduduk.kk', $user->pendudukData()->kk);
        }

        $dk = $dk->findAll();


        $data = [
            'title' => 'Kelahiran',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Surat', 'url' => '/surat'],
                ['title' => 'Kelahiran', 'url' => '/surat/kelahiran', 'active' => true],
            ],

            'data' => $dk,
        ];

        return view('kelahiran/index', $data);
    }

    protected function kelahiranFields()
    {
        return [
            ['name' => 'id_penduduk', 'label' => 'Penduduk', 'type' => 'hidden', 'required' => true],
        ];
    }
}
