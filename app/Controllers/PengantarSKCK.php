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
        $data_pengajuan = $this->pengantarModel->select('pengantar.id as pengantar_id, pengantar.id_penduduk, pengantar.keterangan, pengantar.status, pengantar.tipe, pengantar.keperluan, pengantar.created_at, penduduk.*')
            ->join('penduduk', 'penduduk.id = pengantar.id_penduduk')
            ->where('pengantar.tipe', 'skck')
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
        $user     = new \App\Entities\User(user()->toArray());
        $penduduk = $this->pendudukModel->select('penduduk.*, users.active')
            ->join('users', 'users.id_penduduk = penduduk.id', 'left')
            ->where('users.active', '1');

        if (in_groups('warga')) {
            $penduduk->where('kk', $user->pendudukData()->kk);
        }

        $penduduk = $penduduk->findAll();

        return view('pengantar/skck/new', [
            'title'       => 'Pengajuan Surat Pengantar SKCK',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengantar', 'url' => '/pengantar'],
                ['title' => 'SKCK', 'url' => '/pengantar/skck'],
                ['title' => 'buat', 'url' => '/pengantar/skck/new', 'active' => true]
            ],
            'penduduk' => $penduduk,
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
        if ($pengantar) {
            $data = [
                'status' => $this->request->getPost('status'),
                'keterangan' => $this->request->getPost('keterangan') ?? null,
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
        $carbon = \Carbon\Carbon::parse($created_at);
        $count = $this->pengantarModel
            ->where('tipe', 'skck')
            ->where('YEAR(created_at)', $carbon->from('Y'))
            ->where('created_at <=', $created_at)
            ->countAllResults();

        $count = str_pad($count == 0 ? $count + 1 : $count, 3, '0', STR_PAD_LEFT); // Pastikan count berjumlah 3 digit
        $nmr = $count . '/' . 'SP-SKCK' . '/' . $carbon->format('d') . '/' . $carbon->format('m') . '/' . $carbon->format('Y');
        
        return $nmr;
    }

    public function fieldsPengantarSkck()
    {
        return [
            ['name' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'placeholder' => 'keperluan skck', 'required' => true]
        ];
    }
}
