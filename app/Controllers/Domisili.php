<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Domisili extends BaseController
{
    protected $domisiliModel;
    protected $pendudukModel;

    protected $dompdf;

    public function __construct()
    {
        $this->domisiliModel = new \App\Models\Domisili();
        $this->pendudukModel = new \App\Models\PendudukModel();

        $this->dompdf = new \Dompdf\Dompdf([
            'dpi'                     => 96,
            'isPhpEnabled'            => true,
            'isRemoteEnabled'         => false,
            'isJavascriptEnabled'     => true,
            'isHtml5ParserEnabled'    => true,
            'isFontSubsettingEnabled' => true,
            'defaultPaperSize'        => 'A4',
            'defaultFont'             => 'times',
        ]);
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $dk   = $this->domisiliModel->select('domisili.*, penduduk.*, domisili.id as id_domisili')
            ->join('penduduk', 'penduduk.id = domisili.id_penduduk', 'left');

        if (user()->role == 'warga') {
            $dk->where('penduduk.kk', $user->pendudukData()->kk);
        }

        $dk = $dk->findAll();

        $data = [
            'title'       => 'Domisili',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Surat', 'url' => '/surat'],
                ['title' => 'Domisili', 'url' => '/surat/domisili', 'active' => true],
            ],

            'data' => $dk,
        ];

        return view('domisili/index', $data);
    }

    public function new()
    {
        $user     = new \App\Entities\User(user()->toArray());
        $penduduk = $this->pendudukModel->select('*');

        if (in_groups('warga')) {
            $penduduk->where('kk', $user->pendudukData()->kk);
        }

        $penduduk = $penduduk->findAll();

        return view('domisili/new', [
            'title'       => 'Domisili',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Surat', 'url' => '/surat'],
                ['title' => 'Domisili', 'url' => '/surat/domisili'],
                ['title' => 'Buat', 'url' => '/surat/domisili/new', 'active' => true],
            ],

            'penduduk' => $penduduk,
            'fields'   => $this->domisiliFields(),
        ]);
    }

    public function store()
    {
        $rules = [
            'id_penduduk' => 'required|is_not_unique[penduduk.id]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // find penduduk by id
        $penduduk = $this->pendudukModel->find($this->request->getPost('id_penduduk'));

        if (!$penduduk->isFullFilled()) {
            return redirect()->back()->withInput()->with('errors', ['Data penduduk belum lengkap']);
        }

        $data_domisili = [
            'id_penduduk' => $penduduk->id,
            'status'      => 'pending',
        ];

        if ($this->domisiliModel->save($data_domisili)) {
            return redirect()->to('/surat/domisili')->with('success', 'Data berhasil disimpan');
        }

        return redirect()->back()->withInput()->with('errors', ['Data gagal disimpan']);
    }

    public function batal($id)
    {
        $domisili = $this->domisiliModel->find($id);

        if (!$domisili) {
            return redirect()->back()->with('error', 'Surat domisili tidak ditemukan');
        }


        $data = [
            'status' => "batal",
            'keterangan' => $this->request->getPost('keterangan') ?? null
        ];

        $domisili->fill($data);

        if ($domisili->hasChanged() && !$this->domisiliModel->save($domisili)) {
            return redirect()->back()->with('errors', $this->domisiliModel->errors());
        }

        return redirect()->back()->with('success', 'Status surat domisili berhasil diubah');
    }

    public function updateStatus($id)
    {
        $pengantar = $this->domisiliModel->find($id);

        if (!$pengantar) {
            return redirect()->back()->with('error', 'Surat pengantar tidak ditemukan');
        }


        $data = [
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan') ?? null
        ];

        $pengantar->fill($data);

        if ($pengantar->hasChanged() && !$this->domisiliModel->save($pengantar)) {
            return redirect()->back()->with('errors', $this->domisiliModel->errors());
        }

        return redirect()->back()->with('success', 'Status surat pengantar berhasil diubah');
    }

    public function print($id)
    {
        $domisili = $this->domisiliModel->select('penduduk.*, domisili.*')
            ->join('penduduk', 'penduduk.id = domisili.id_penduduk', 'left');

        // if warga, only approved domisili
        if (in_groups('warga')) {
            $domisili = $domisili->where('domisili.status', 'approved');
        }

        $domisili = $domisili->find($id);

        if (!$domisili) {
            return redirect()->back()->with('error', 'Surat domisili tidak ditemukan, atau tidak dapat diakses');
        }

        $html = view('domisili/print', [
            'domisili' => $domisili,
            'noSurat'  => $this->genNomorSurat($domisili->created_at),
        ]);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream('Surat Domisili - ' . $domisili->nama, ['Attachment' => 0]);

        exit(0);
    }

    protected function genNomorSurat($created_at)
    {
        // Hitung semua surat KIA pada tahun ini yang dibuat sebelum atau pada tanggal $created_at
        $count = $this->domisiliModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('created_at <=', $created_at)
            ->countAllResults();

        // Format nomor surat: count / SKK / 2 digit hari / 2 digit bulan / tahun
        $count = str_pad($count, 3, '0', STR_PAD_LEFT);  // Pastikan count berjumlah 3 digit
        $nmr = $count . '/' . 'SKDOM' . '/' . date('d') . '/' . date('m') . '/' . date('Y');

        return $nmr;
    }

    protected function domisiliFields()
    {
        return [
            ['name' => 'id_penduduk', 'label' => 'Penduduk', 'type' => 'hidden'],
            ['name' => 'nama', 'label' => 'Nama Penduduk', 'type' => 'text', 'placeholder' => 'nama penduduk', 'readonly' => true]
        ];
    }
}
