<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PengantarKIA extends BaseController
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
        $data_pengajuan = $this->pengantarModel->select('pengantar.id as pengantar_id, pengantar.id_penduduk, pengantar.keterangan, pengantar.status, pengantar.tipe, pengantar.keperluan, pengantar.created_at, penduduk.*')
            ->join('penduduk', 'penduduk.id = pengantar.id_penduduk')
            ->where('pengantar.tipe', 'kia')
            ->findAll();

        return view('pengantar/kia/index', [
            'title' => 'Pengantar KIA',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengantar', 'url' => '/pengantar'],
                ['title' => 'KIA', 'url' => '/pengantar/kia', 'active' => true]
            ],

            'data' => $data_pengajuan
        ]);
    }

    public function new()
    {
        // $data_penduduk = $this->pendudukModel->select('penduduk.*')
        //     ->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) <=', 17)
        //     ->findAll();

        $user     = new \App\Entities\User(user()->toArray());
        $penduduk = $this->pendudukModel->select('penduduk.*, users.active')
            ->join('users', 'users.id_penduduk = penduduk.id', 'left')
            ->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) <=', 17)
            ->where('users.active', '1');

        if (in_groups('warga')) {
            $penduduk->where('kk', $user->pendudukData()->kk);
        }

        $penduduk = $penduduk->findAll();

        return view('pengantar/kia/new', [
            'title'       => 'Pengajuan Surat Pengantar KIA',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengantar', 'url' => '/pengantar'],
                ['title' => 'KIA', 'url' => '/pengantar/kia'],
                ['title' => 'buat', 'url' => '/pengantar/kia/new', 'active' => true]
            ],
            'penduduk' => $penduduk,
            'fields'   => $this->fieldsPengantarKIA()
        ]);
    }

    public function store()
    {
        $data_pengajuan = [
            'id_penduduk' => $this->request->getPost('id_penduduk'),
            'keperluan'   => $this->request->getPost('keperluan'),
            'status'      => 'pending',
            'tipe'        => 'kia'
        ];

        $penduduk = $this->pendudukModel->find($data_pengajuan['id_penduduk']);
        if (!$penduduk) {
            return redirect()->back()->withInput()->with('error', "Data tidak ditemukan, mohon periksa kembali")->withInput();
        }

        if (!$penduduk->isFullFilled()) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Data penduduk belum lengkap, silahkan lengkapi data penduduk terlebih dahulu'])->withInput();
        }

        if (!$penduduk->is_verified) {
            return redirect()->back()->withInput()->with('errors', ['id_penduduk' => 'Data penduduk belum diverifikasi, silahkan hubungi pihak desa'])->withInput();
        }

        if (!$this->pengantarModel->save($data_pengajuan)) {
            return redirect()->back()->withInput()->with('errors', $this->pengantarModel->errors());
        }

        return redirect()->to('/surat/pengantar/kia')->with('success', 'Pengajuan surat pengantar KIA berhasil dikirim');
    }

    public function batal($id)
    {
        $domisili = $this->pengantarModel->find($id);

        if (!$domisili) {
            return redirect()->back()->with('error', 'Surat domisili tidak ditemukan');
        }


        $data = [
            'status' => "batal",
            'keterangan' => $this->request->getPost('keterangan') ?? 'dibalikkan oleh pemohon'
        ];

        $domisili->fill($data);

        if ($domisili->hasChanged() && !$this->pengantarModel->save($domisili)) {
            return redirect()->back()->with('errors', $this->pengantarModel->errors());
        }

        return redirect()->back()->with('success', 'Status surat domisili berhasil diubah');
    }

    public function updateStatus($id)
    {
        $pengantar = $this->pengantarModel->find($id);

        if (!$pengantar) {
            return redirect()->back()->with('error', 'Surat pengantar tidak ditemukan');
        }


        $data = [
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan') ?? null
        ];

        $pengantar->fill($data);

        if ($pengantar->hasChanged() && !$this->pengantarModel->save($pengantar)) {
            return redirect()->back()->with('errors', $this->pengantarModel->errors());
        }

        return redirect()->back()->with('success', 'Status surat pengantar berhasil diubah');
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

        $html = view('pengantar/kia/print', [
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
        $carbon = \Carbon\Carbon::parse($created_at);
        $count = $this->pengantarModel
            ->where('tipe', 'kia')
            ->where('YEAR(created_at)', $carbon->format('Y'))
            ->where('created_at <=', $created_at)
            ->countAllResults();

        $count = str_pad($count == 0 ? $count + 1 : $count, 3, '0', STR_PAD_LEFT); // Pastikan count berjumlah 3 digit
        $nmr = $count . '/' . 'SP-KIA' . '/' . $carbon->format('d') . '/' . $carbon->format('m') . '/' . $carbon->format('Y');

        return $nmr;
    }

    public function fieldsPengantarKIA()
    {
        return [
            ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'placeholder' => 'keperluan pembuatan KIA', 'required' => true]
        ];
    }
}
