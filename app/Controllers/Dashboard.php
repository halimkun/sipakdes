<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $pendudukModel;
    protected $kematianModel;
    protected $metrics;
    protected $bgColors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];

    public function __construct()
    {
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->kematianModel = new \App\Models\KematianModel();
    }

    public function toIndex()
    {
        return redirect()->to('/dashboard', 301);
    }

    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Dashboard', 'url' => '/dashboard', 'active' => true],
            ],

            'metrics' => $this->getPendudukMetrics()
        ]);
    }

    protected function getSuratMetrics()
    {
        // ... query to get the metrics
    }

    protected function getPendudukMetrics()
    {
        $countPenduduk = $this->pendudukModel->countAll();
        $countPendudukAnak = $this->pendudukModel->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) <=', 17)->countAllResults();
        $countPendudukDewasa = $this->pendudukModel->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) >', 17)->countAllResults();
        $countPendudukMeninggal = $this->kematianModel->countAll();
        $countPendudukPerempuan = $this->pendudukModel->where('jenis_kelamin', 'Perempuan')->countAllResults();
        $countPendudukLakiLaki = $this->pendudukModel->where('jenis_kelamin', 'Laki-laki')->countAllResults();

        // random bg color
        $this->metricsBuilder('penduduk', 'Jumlah Penduduk', $countPenduduk, ['icon' => 'fa fa-users', 'color' => 'bg-success']);
        $this->metricsBuilder('penduduk', 'Penduduk Anak', $countPendudukAnak, ['icon' => 'fa fa-child', 'color' => 'bg-primary']);
        $this->metricsBuilder('penduduk', 'Penduduk Dewasa', $countPendudukDewasa, ['icon' => 'fa fa-user', 'color' => 'bg-warning']);
        $this->metricsBuilder('penduduk', 'Penduduk Meninggal', $countPendudukMeninggal, ['icon' => 'fa fa-user-times', 'color' => 'bg-danger']);
        $this->metricsBuilder('penduduk', 'Penduduk Perempuan', $countPendudukPerempuan, ['icon' => 'fa fa-venus', 'color' => 'bg-secondary']);
        $this->metricsBuilder('penduduk', 'Penduduk Laki-laki', $countPendudukLakiLaki, ['icon' => 'fa fa-mars', 'color' => 'bg-secondary']);
        
        return $this->metrics;
    }

    protected function metricsBuilder($key, $title, $jumlah, $options = [])
    {
        $this->metrics[$key][] = [
            'title'   => $title,
            'count'   => $jumlah,
            'options' => $options,
        ];
    }

    // funtion to get random bg color
    protected function randomBgColor()
    {
        return $this->bgColors[array_rand($this->bgColors)];
    }
}
