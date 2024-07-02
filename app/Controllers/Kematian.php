<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kematian extends BaseController
{
    protected $kematianModel;
    protected $pendudukModel;
    protected $userModel;

    protected $dompdf;

    protected $optionStatus = [
        ["value" => "pending", "name" => "Pending"],
        ["value" => "invalid", "name" => "Invalid"],
    ];

    public function __construct()
    {
        $this->kematianModel = new \App\Models\KematianModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();

        $this->dompdf = new \Dompdf\Dompdf([
            'dpi' => 96,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => false,
            'isJavascriptEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultPaperSize' => 'A4',
            'defaultFont' => 'times',
        ]);
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $dk = $this->kematianModel->select('kematian.*, penduduk.nama, penduduk.tanggal_lahir')
            ->join('penduduk', 'penduduk.id = kematian.id_penduduk');

        if (in_groups('warga')) {
            $dk = $dk->where('penduduk.kk', $user->pendudukData()->kk);
        }

        $dk = $dk->orderBy('kematian.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Permintaan Surat Kematian' . (in_groups('warga') ? ' Anda' : ''),
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
        $keluarga = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status as status_berkas, berkas_kk.keterangan as keterangan_berkas, kematian.id as kematian_id, kematian.created_at as kematian_created_at')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->join('kematian', 'kematian.id_penduduk = penduduk.id', 'left');

        // if (in_groups('warga')) {
        //     $keluarga = $keluarga->where('penduduk.kk', $user->pendudukData()->kk);
        // }

        $keluarga = $keluarga->where('kematian.id', null)
            ->orderBy('penduduk.nama', 'ASC')
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
        // if (!in_groups('admin') && !$this->checkNikPelapor($this->request->getPost('id_penduduk'), $this->request->getPost('nik_pelapor'))) {
        //     return redirect()->back()->withInput()->with('errors', ['nik_pelapor' => 'NIK pelapor tidak terdaftar dalam KK yang sama'])->withInput();
        // }

        if (request()->getPost('id_penduduk') == user()->id_penduduk) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Tidak boleh memilih diri sendiri'])->withInput();
        }

        $penduduk = $this->pendudukModel->find($this->request->getPost('id_penduduk'));
        if (!$penduduk) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Penduduk tidak ditemukan'])->withInput();
        }

        if (in_groups('admin')) {
            // update is_verified 
            $penduduk->is_verified = 1;
            $this->pendudukModel->save($penduduk);

            $penduduk = $this->pendudukModel->find($this->request->getPost('id_penduduk'));
        }

        if (!$penduduk->is_verified) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Data penduduk belum diverifikasi, silahkan hubungi pihak desa'])->withInput();
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

    public function print($id)
    {
        $kematian = $this->kematianModel->select('penduduk.*, kematian.*')
            ->join('penduduk', 'penduduk.id = kematian.id_penduduk', 'left');

        // if warga, only approved kematian
        if (in_groups('warga')) {
            $kematian = $kematian->where('kematian.status', 'approved');
        }

        $kematian = $kematian->find($id);

        if (!$kematian) {
            return redirect()->back()->with('error', 'Surat kematian tidak ditemukan, atau tidak dapat diakses');
        }

        $pelapor = $this->pendudukModel->select('nama')->where('nik', $kematian->nik_pelapor)->first();

        $html = view('kematian/print', [
            'kematian' => $kematian,
            'noSurat' => $this->genNomorSurat($kematian->created_at),
            'pelapor' => $pelapor,
        ]);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream('Surat Kematian - ' . $kematian->nama, ['Attachment' => 0]);

        exit(0);
    }

    public function batal($id)
    {
        $kematian = $this->kematianModel->find($id);
        if ($kematian) {
            $data = ['status' => 'batal'];
            $kematian->fill($data);

            if ($kematian->hasChanged()) {
                $user = $this->userModel->where('id_penduduk', $kematian->id_penduduk)->first();
                if ($user) {
                    $user->activate();
                    $user->unban();
                    $this->userModel->save($user);
                }

                if ($this->kematianModel->save($kematian)) {
                    return redirect()->back()->with('success', 'Surat kematian berhasil dibatalkan');
                }
            }

            return redirect()->back()->with('errors', $this->kematianModel->errors());
        }

        return redirect()->back()->with('error', 'Surat kematian tidak ditemukan');
    }

    // updateStatus
    public function updateStatus($id)
    {
        $kematian = $this->kematianModel->find($id);
        if ($kematian) {
            $data = [
                'status' => $this->request->getPost('status'),
                'keterangan' => $this->request->getPost('keterangan') ?? '',
            ];
            $kematian->fill($data);

            if ($kematian->hasChanged()) {
                // if status is approved, then deactivate the user
                $user = $this->userModel->where('id_penduduk', $kematian->id_penduduk)->first();
                if ($kematian->status == 'approved') {
                    if ($user) {
                        $user->deactivate();
                        $user->ban("Penduduk dengan user ini telah meninggal, akun tidak dapat digunakan.");
                        $this->userModel->save($user);
                    }
                } else {
                    if ($user) {
                        $user->activate();
                        $user->unban();
                        $this->userModel->save($user);
                    }
                }

                if ($this->kematianModel->save($kematian)) {
                    return redirect()->back()->with('success', 'Status surat kematian berhasil diubah');
                }

                return redirect()->back()->with('errors', $this->kematianModel->errors());
            }

            return redirect()->back()->with('errors', $this->kematianModel->errors());
        }

        return redirect()->back()->with('errors', 'Surat kematian tidak ditemukan');
    }

    protected function genNomorSurat($created_at)
    {
        // count all kematian on this year where < = created_at
        $count = $this->kematianModel->where('YEAR(created_at)', date('Y'))->where('created_at <=', $created_at)->countAllResults();
        $count = str_pad($count, 3, '0', STR_PAD_LEFT);

        // count / SKK / 2 digit day / 2 diigit month / year
        $nmr = $count . '/' . 'SKK' . '/' . date('d') . '/' . date('m') . '/' . date('Y');
        return $nmr;
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
            ['name' => 'tempat', 'label' => 'Tempat', 'type' => 'text', 'placeholder' => 'tempat pemakaman'],
            ['name' => 'tanggal', 'label' => 'Tanggal Meninggal', 'type' => 'text', 'placeholder' => 'tanggal kematian'],
            ['name' => 'sebab', 'label' => 'Sebab', 'type' => 'text', 'placeholder' => 'sakit, kecelakaan, dll'],
            ['name' => 'nik_pelapor', 'label' => 'NIK Pelapor', 'type' => 'text', 'placeholder' => 'NIK pelapor'],
        ];
    }
}
