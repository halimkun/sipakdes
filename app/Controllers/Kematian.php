<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kematian extends BaseController
{
    protected $kematianModel;
    protected $pendudukModel;
    protected $userModel;

    protected $optionStatus = [
        ["value" => "pending", "name" => "Pending"],
        ["value" => "invalid", "name" => "Invalid"],
    ];

    public function __construct()
    {
        $this->kematianModel = new \App\Models\KematianModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $dk = $this->kematianModel->select('kematian.*, penduduk.nama')
            ->join('penduduk', 'penduduk.id = kematian.id_penduduk')
            ->findAll();

        $data = [
            'title' => 'Permintaan Surat Kematian Anda',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Kematian', 'url' => '/surat/kematian', 'active' => true],
            ],

            'data' => $dk,
        ];

        return view('kematian/index', $data);
    }

    public function new()
    {
        $user = new \App\Entities\User(user()->toArray());
        $keluarga = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status as status_berkas, berkas_kk.keterangan as keterangan_berkas')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->orderBy('penduduk.nama', 'ASC')
            ->where('penduduk.kk', $user->pendudukData()->kk)
            ->findAll();

        $data = [
            'title' => 'Buat Permintaan Surat Kematian',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Kematian', 'url' => '/surat/kematian', 'active' => true],
                ['title' => 'Buat', 'url' => '/surat/kematian/new', 'active' => true],
            ],

            'kematianFields' => $this->kematianFields(),
            'keluarga' => $keluarga,
        ];

        return view('kematian/new', $data);
    }

    public function store()
    {
        $rules = [
            'id_penduduk' => 'required',
            'tanggal' => 'required|valid_date',
            'tempat' => 'required|max_length[255]|alpha_numeric_space',
            'sebab' => 'required|max_length[255]|alpha_numeric_space',
            'nik_pelapor' => 'required|exact_length[16]|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->withInput();
        }
        
        // check if nik_pelapor have same kk with id_penduduk
        if (!$this->checkNikPelapor($this->request->getPost('id_penduduk'), $this->request->getPost('nik_pelapor'))) {
            return redirect()->back()->withInput()->with('errors', ['nik_pelapor' => 'NIK pelapor tidak terdaftar dalam KK yang sama'])->withInput();
        }
        
        if (request()->getPost('id_penduduk') == user()->id_penduduk) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Tidak boleh memilih diri sendiri'])->withInput();
        }

        
        // transaction
        $this->kematianModel->transBegin();
        
        if ($this->kematianModel->save($this->request->getPost())) {
            // fing the user using id_penduduk
            $user = $this->userModel->where('id_penduduk', $this->request->getPost('id_penduduk'))->first();
            if ($user) {
                $user->deactivate();
                $user->ban("Penduduk dengan user ini telah meninggal, akun tidak dapat digunakan.");
                $this->userModel->save($user);
            }
        } else {
            $this->kematianModel->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->kematianModel->errors())->withInput();
        }

        $this->kematianModel->transCommit();
        
        return redirect()->to('/surat/kematian')->with('success', 'Surat kematian berhasil dibuat');
    }

    // check with same kk
    protected function checkNikPelapor($id_penduduk, $nik_pelapor)
    {
        $pm = $this->pendudukModel->find($id_penduduk);
        $pl = $this->pendudukModel->where('nik', $nik_pelapor)->first();

        if ($pm && $pl) {
            return $pm->kk == $pl->kk;
        }

        return false;
    }

    protected function kematianFields()
    {
        return [
            ['name' => 'id_penduduk', 'label' => 'Penduduk', 'type' => 'hidden'],
            ['name' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date'],
            ['name' => 'tempat', 'label' => 'Tempat', 'type' => 'text', 'placeholder' => 'tempat pemakaman'],
            ['name' => 'sebab', 'label' => 'Sebab', 'type' => 'text', 'placeholder' => 'sakit, kecelakaan, dll'],
            ['name' => 'nik_pelapor', 'label' => 'NIK Pelapor', 'type' => 'text', 'placeholder' => 'NIK pelapor'],
        ];
    }
}
