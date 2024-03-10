<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PengantarSKCK extends BaseController
{
    protected $pengantarModel;
    protected $pendudukModel;

    protected $dompdf;

    public function __construct()
    {
        $this->pengantarModel = new \App\Models\Pengantar();
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
    }

    public function index()
    {
        $data_pengajuan = $this->pengantarModel->select('pengantar.id as pengantar_id, pengantar.id_penduduk, pengantar.status, pengantar.tipe, pengantar.keperluan, pengantar.created_at, penduduk.*')
            ->join('penduduk', 'penduduk.id = pengantar.id_penduduk')
            ->findAll();

        return view('pengantar/skck/index', [
            'title' => 'Pengantar SKCK',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengantar', 'url' => '/pengantar'],
                ['title' => 'SKCK', 'url' => '/pengantar/skck', 'active' => true]
            ],

            'data' => $data_pengajuan
        ]);
    }

    public function new()
    {
        $data_penduduk = $this->pendudukModel->select('penduduk.*, users.active')
            ->join('users', 'users.id_penduduk = penduduk.id', 'left')
            ->where('users.active', '1')
            ->findAll();

        return view('pengantar/skck/new', [
            'title'       => 'Pengajuan Surat Pengantar SKCK',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengantar', 'url' => '/pengantar'],
                ['title' => 'SKCK', 'url' => '/pengantar/skck'],
                ['title' => 'buat', 'url' => '/pengantar/skck/new', 'active' => true]
            ],
            'penduduk' => $data_penduduk,
            'fields'   => $this->fieldsPengantarSkck()
        ]);
    }

    public function store()
    {
        $data_pengajuan = [
            'id_penduduk' => $this->request->getPost('id_penduduk'),
            'keperluan'   => $this->request->getPost('keperluan'),
            'status'      => 'pending',
            'tipe'        => 'skck'
        ];

        $penduduk = $this->pendudukModel->find($data_pengajuan['id_penduduk']);
        if (!$penduduk) {
            return redirect()->back()->withInput()->with('error', "Data tidak ditemukan, mohon periksa kembali")->withInput();
        }

        if (!$penduduk->is_verified) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Data penduduk belum diverifikasi, silahkan hubungi pihak desa'])->withInput();
        }

        if (!$this->pengantarModel->save($data_pengajuan)) {
            return redirect()->back()->withInput()->with('errors', $this->pengantarModel->errors());
        }

        return redirect()->to('/surat/pengantar/skck')->with('success', 'Pengajuan surat pengantar SKCK berhasil dikirim');
    }

    public function updateStatus($id)
    {
        $pengantar = $this->pengantarModel->find($id);
        if ($pengantar) {
            $data = [
                'status' => $this->request->getPost('status'),
                'keterangan' => $this->request->getPost('keterangan') ?? '',
            ];

            $pengantar->fill($data);

            if ($pengantar->hasChanged()) {
                if ($this->pengantarModel->save($pengantar)) {
                    return redirect()->back()->with('success', 'Status surat pengantar berhasil diubah');
                }

                return redirect()->back()->with('errors', $this->pengantarModel->errors());
            }

            return redirect()->back()->with('errors', $this->pengantarModel->errors());
        }

        return redirect()->back()->with('error', 'Surat pengantar tidak ditemukan');
    }

    public function print($id)
    {
        $pengantar = $this->pengantarModel->select('pengantar.id as pengantar_id, pengantar.id_penduduk, pengantar.status, pengantar.tipe, pengantar.keperluan, pengantar.created_at as pengantar_created_at, penduduk.*')
            ->join('penduduk', 'penduduk.id = pengantar.id_penduduk', 'left');

        // if warga, only approved pengantar
        if (in_groups('warga')) {
            $pengantar = $pengantar->where('pengantar.status', 'selesai');
        }

        $pengantar = $pengantar->find($id);

        if (!$pengantar) {
            return redirect()->back()->with('error', 'Surat pengantar tidak ditemukan, atau tidak dapat diakses');
        }

        // if pengantar dont has kk 
        if (!$pengantar->kk) {
            return redirect()->back()->with('error', 'Surat pengantar tidak dapat dicetak, karena data KK tidak ditemukan');
        }

        $html = view('pengantar/skck/print', [
            'pengantar' => $pengantar,
            'noSurat' => $this->genNomorSurat($pengantar->pengantar_created_at),
        ]);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream('Surat pengantar - ' . $pengantar->nama, ['Attachment' => 0]);

        exit(0);
    }

    protected function genNomorSurat($created_at)
    {
        // count all kematian on this year where < = created_at
        $count = $this->pengantarModel->where('tipe', 'skck')->where('YEAR(created_at)', date('Y'))->where('created_at <=', $created_at)->countAllResults();
        $count = str_pad($count, 3, '0', STR_PAD_LEFT);

        // count / SKK / 2 digit day / 2 diigit month / year
        $nmr = $count . '/' . 'SP-SKCK' . '/' . date('d') . '/' . date('m') . '/' . date('Y');
        return $nmr;
    }

    public function fieldsPengantarSkck()
    {
        return [
            ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'placeholder' => 'keperluan skck', 'required' => true]
        ];
    }
}
