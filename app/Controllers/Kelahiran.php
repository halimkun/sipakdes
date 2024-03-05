<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kelahiran extends BaseController
{
    protected $kelahiranModel;
    protected $pendudukModel;

    protected $dompdf;

    protected $pendudukController;

    public function __construct()
    {
        $this->kelahiranModel = new \App\Models\Kelahiran();
        $this->pendudukModel = new \App\Models\PendudukModel();

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

        $this->pendudukController = new \App\Controllers\Penduduk();
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $dk = $this->kelahiranModel->select('kelahiran.*, penduduk.*, kelahiran.id as id_kelahiran')
            ->join('penduduk', 'penduduk.id = kelahiran.id_penduduk', 'left');

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

    public function new()
    {
        $data = [
            'title' => 'Kelahiran',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Surat', 'url' => '/surat'],
                ['title' => 'Kelahiran', 'url' => '/surat/kelahiran'],
                ['title' => 'Buat', 'url' => '/surat/kelahiran/new', 'active' => true],
            ],

            'fields' => $this->kelahiranFields(),
            'value' => [
                'hubungan' => 'Anak',
                'kewarganegaraan' => 'WNI',
                'status_perkawinan' => 'Belum Kawin',
            ],
        ];

        return view('kelahiran/new', $data);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'tempat_lahir' => 'required|min_length[3]',
            'tanggal_lahir' => 'required|valid_date',
            'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
            'hubungan' => 'required|in_list[Anak]',
            'kewarganegaraan' => 'required|in_list[WNI]',
            'status_perkawinan' => 'required|in_list[Belum Kawin]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $penduduk = new \App\Entities\Penduduk($this->request->getPost());
        
        if ($this->pendudukModel->save($penduduk)) {
            $kelahiran = new \App\Entities\Kelahiran();
            $kelahiran->id_penduduk = $this->pendudukModel->insertID();
            $kelahiran->status = 'pending';

            if ($this->kelahiranModel->save($kelahiran)) {
                return redirect()->to('/surat/kelahiran')->with('success', 'Data berhasil disimpan');
            } else {
                $errors = $this->kelahiranModel->errors();
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        } else {
            $errors = $this->pendudukModel->errors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    public function updateStatus($id)
    {
        $kelahiran = $this->kelahiranModel->find($id);
        if ($kelahiran) {
            $data = [
                'status' => $this->request->getPost('status'),
                'keterangan' => $this->request->getPost('keterangan') ?? '',
            ];

            $kelahiran->fill($data);

            if ($kelahiran->hasChanged()) {
                if ($this->kelahiranModel->save($kelahiran)) {
                    return redirect()->back()->with('success', 'Status surat kelahiran berhasil diubah');
                }

                return redirect()->back()->with('errors', $this->kelahiranModel->errors());
            }

            return redirect()->back()->with('errors', $this->kelahiranModel->errors());
        }

        return redirect()->back()->with('error', 'Surat kelahiran tidak ditemukan');
    }

    public function print($id)
    {
        $kelahiran = $this->kelahiranModel->select('penduduk.*, kelahiran.*')
        ->join('penduduk', 'penduduk.id = kelahiran.id_penduduk', 'left');
        
        // if warga, only approved kelahiran
        if (in_groups('warga')) {
            $kelahiran = $kelahiran->where('kelahiran.status', 'selesai');
        }
        
        $kelahiran = $kelahiran->find($id);
        
        if (!$kelahiran) {
            return redirect()->back()->with('error', 'Surat kelahiran tidak ditemukan, atau tidak dapat diakses');
        }

        // if kelahiran dont has kk 
        if (!$kelahiran->kk) {
            return redirect()->back()->with('error', 'Surat kelahiran tidak dapat dicetak, karena data KK tidak ditemukan');
        }

        $ibu = $this->pendudukModel->where('kk', $kelahiran->kk)->where('hubungan', 'ibu')->first();
        $ayah = $this->pendudukModel->where('kk', $kelahiran->kk)->where('hubungan', 'ayah')->first();

        $html = view('kelahiran/print', [
            'kelahiran' => $kelahiran,
            'noSurat' => $this->genNomorSurat($kelahiran->created_at),
            'ibu' => $ibu,
            'ayah' => $ayah,
        ]);
    
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream('Surat kelahiran - ' . $kelahiran->nama, ['Attachment' => 0]);

        exit(0);
    }

    protected function genNomorSurat($created_at)
    {
        // count all kematian on this year where < = created_at
        $count = $this->kelahiranModel->where('YEAR(created_at)', date('Y'))->where('created_at <=', $created_at)->countAllResults();
        $count = str_pad($count, 3, '0', STR_PAD_LEFT);

        // count / SKK / 2 digit day / 2 diigit month / year
        $nmr = $count . '/' . 'SKL' . '/' . date('d') . '/' . date('m') . '/' . date('Y');
        return $nmr;
    }

    protected function kelahiranFields()
    {
        return [
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => $this->pendudukController->optionJenisKelamin, 'required' => true],
            ['name' => 'hubungan', 'label' => 'Hubungan', 'type' => 'text', 'required' => true, 'readonly' => true],
            ['name' => 'kewarganegaraan', 'label' => 'Kewarganegaraan', 'type' => 'text', 'required' => true, 'readonly' => true],
            ['name' => 'status_perkawinan', 'label' => 'Status Perkawinan', 'type' => 'text', 'required' => true, 'readonly' => true],
        ];
    }
}
