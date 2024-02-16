<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Penduduk extends BaseController
{
    protected $pendudukModel;
    protected $userModel;

    // Options 
    protected $optionJenisKelamin = [["value" => "Laki-laki", "name" => "Laki-laki"], ["value" => "Perempuan", "name" => "Perempuan"]];
    protected $optionGolonganDarah = [["value" => "A", "name" => "A"], ["value" => "B", "name" => "B"], ["value" => "AB", "name" => "AB"], ["value" => "O", "name" => "O"]];
    protected $optionAgama = [["value" => "Islam", "name" => "Islam"], ["value" => "Kristen", "name" => "Kristen"], ["value" => "Katolik", "name" => "Katolik"], ["value" => "Hindu", "name" => "Hindu"], ["value" => "Budha", "name" => "Budha"], ["value" => "Konghucu", "name" => "Konghucu"]];
    protected $optionPendidikan = [["value" => "Tidak Sekolah", "name" => "Tidak Sekolah"], ["value" => "SD", "name" => "SD"], ["value" => "SMP", "name" => "SMP"], ["value" => "SMA", "name" => "SMA"], ["value" => "Diploma", "name" => "Diploma"], ["value" => "S1", "name" => "S1"], ["value" => "S2", "name" => "S2"], ["value" => "S3", "name" => "S3"]];
    protected $optionJenisPekerjaan = [["value" => "Tidak Bekerja", "name" => "Tidak Bekerja"], ["value" => "Pelajar/Mahasiswa", "name" => "Pelajar/Mahasiswa"], ["value" => "PNS", "name" => "PNS"], ["value" => "TNI", "name" => "TNI"], ["value" => "POLRI", "name" => "POLRI"], ["value" => "Swasta", "name" => "Swasta"], ["value" => "Wiraswasta", "name" => "Wiraswasta"], ["value" => "Petani", "name" => "Petani"], ["value" => "Nelayan", "name" => "Nelayan"], ["value" => "Ibu Rumah Tangga", "name" => "Ibu Rumah Tangga"], ["value" => "Lainnya", "name" => "Lainnya"]];
    protected $optionHubungan = [["value" => "Kepala Keluarga", "name" => "Kepala Keluarga"], ["value" => "Ayah", "name" => "Ayah"], ["value" => "Ibu", "name" => "Ibu"], ["value" => "Anak", "name" => "Anak"]];
    protected $optionKewarganegaraan = [["value" => "WNI", "name" => "WNI"], ["value" => "WNA", "name" => "WNA"]];
    protected $optionStatusPerkawinan = [["value" => "Belum Kawin", "name" => "Belum Kawin"], ["value" => "Kawin", "name" => "Kawin"], ["value" => "Cerai Hidup", "name" => "Cerai Hidup"], ["value" => "Cerai Mati", "name" => "Cerai Mati"]];

    public function __construct()
    {
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();
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

    public function new()
    {
        $fields = [
            ['name' => 'kk', 'label' => 'KK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true],
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true],
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => $this->optionJenisKelamin, 'required' => true],
            ['name' => 'golongan_darah', 'label' => 'Golongan Darah', 'type' => 'select', 'options' => $this->optionGolonganDarah, 'required' => true],
            ['name' => 'agama', 'label' => 'Agama', 'type' => 'select', 'options' => $this->optionAgama, 'required' => true],
            ['name' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'select', 'options' => $this->optionPendidikan, 'required' => true],
            ['name' => 'jenis_pekerjaan', 'label' => 'Jenis Pekerjaan', 'type' => 'select', 'options' => $this->optionJenisPekerjaan, 'required' => true],
            ['name' => 'hubungan', 'label' => 'Hubungan', 'type' => 'select', 'options' => $this->optionHubungan, 'required' => true],
            ['name' => 'kewarganegaraan', 'label' => 'Kebangsaan', 'type' => 'select', 'options' => $this->optionKewarganegaraan, 'required' => true],
            ['name' => 'status_perkawinan', 'label' => 'Stts Perkawinan', 'type' => 'select', 'options' => $this->optionStatusPerkawinan, 'required' => true],
            ['name' => 'rt', 'label' => 'RT', 'type' => 'number', 'required' => true],
            ['name' => 'rw', 'label' => 'RW', 'type' => 'number', 'required' => true],
            ['name' => 'kelurahan', 'label' => 'Kelurahan', 'type' => 'text', 'required' => true],
            ['name' => 'kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'required' => true],
            ['name' => 'kabupaten', 'label' => 'Kabupaten', 'type' => 'text', 'required' => true],
            ['name' => 'provinsi', 'label' => 'Provinsi', 'type' => 'text', 'required' => true],
        ];

        return view('penduduk/new', [
            'fields' => $fields,
        ]);
    }

}
