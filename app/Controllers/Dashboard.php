<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $pendudukModel;

    protected $metrics;
    protected $metricsSurat;

    protected $bgColors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];

    protected $kematian;
    protected $domisili;
    protected $kelahiran;
    protected $pengantar;

    public function __construct()
    {
        $this->pendudukModel = new \App\Models\PendudukModel();

        $this->kematian  = new \App\Models\KematianModel();
        $this->domisili  = new \App\Models\Domisili();
        $this->kelahiran = new \App\Models\Kelahiran();
        $this->pengantar = new \App\Models\Pengantar();
    }

    public function toIndex()
    {
        return redirect()->to('/dashboard', 301);
    }

    public function index()
    {
        // get all request
        $monthYear = $this->request->getGet('monthYear') ?? date('Y-m');
        $this->getSuratMetrics($monthYear);

        return view('dashboard', [
            'title'         => 'Dashboard',
            'breadcrumbs'   => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Dashboard', 'url' => '/dashboard', 'active' => true],
            ],

            'metrics'       => $this->getPendudukMetrics(),
            'metricsSurat'  => $this->metricsSurat,
            'monthYear'     => $monthYear,
        ]);
    }

    protected function getSuratMetrics($monthYear = null)
    {
        $carbon = \Carbon\Carbon::parse($monthYear ?? date('Y-m'));
        
        $year   = $carbon->year;
        $month  = $carbon->month;

        $domisili  = $this->mapSuratMetrics(array_column($this->domisili->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->groupBy('status')->get()->getResultArray(), 'total', 'status'));
        $kematian  = $this->mapSuratMetrics(array_column($this->kematian->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->groupBy('status')->get()->getResultArray(), 'total', 'status'));
        $kelahiran = $this->mapSuratMetrics(array_column($this->kelahiran->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->groupBy('status')->get()->getResultArray(), 'total', 'status'));
        $kia       = $this->mapSuratMetrics(array_column($this->pengantar->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->where('tipe', 'kia')->groupBy('status')->get()->getResultArray(), 'total', 'status'));
        $sktm      = $this->mapSuratMetrics(array_column($this->pengantar->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->where('tipe', 'sktm')->groupBy('status')->get()->getResultArray(), 'total', 'status'));
        $skck      = $this->mapSuratMetrics(array_column($this->pengantar->select('status, COUNT(status) as total')->where('YEAR(created_at)', $year)->where('MONTH(created_at)', $month)->where('tipe', 'skck')->groupBy('status')->get()->getResultArray(), 'total', 'status'));

        $this->metricsSurat = [
            'domisili'  => $domisili,
            'kematian'  => $kematian,
            'kelahiran' => $kelahiran,
            'kia'       => $kia,
            'sktm'      => $sktm,
            'skck'      => $skck,
        ];

        return $this->metricsSurat;
    }

    protected function getPendudukMetrics()
    {
        $countPenduduk          = $this->pendudukModel->countAll();
        $countPendudukAnak      = $this->pendudukModel->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) <=', 17)->countAllResults();
        $countPendudukDewasa    = $this->pendudukModel->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) >', 17)->countAllResults();
        $countPendudukMeninggal = $this->kematian->countAll();
        $countPendudukPerempuan = $this->pendudukModel->where('jenis_kelamin', 'Perempuan')->countAllResults();
        $countPendudukLakiLaki  = $this->pendudukModel->where('jenis_kelamin', 'Laki-laki')->countAllResults();

        $countPenduduklansia = $this->pendudukModel->where('TIMESTAMPDIFF(YEAR, penduduk.tanggal_lahir, CURDATE()) >=', 60)->countAllResults();

        // random bg color
        $this->metricsBuilder('penduduk', 'Jumlah Penduduk', $countPenduduk, ['icon' => 'fa fa-users', 'color' => 'bg-success']);
        $this->metricsBuilder('penduduk', 'Penduduk Anak', $countPendudukAnak, ['icon' => 'fa fa-child', 'color' => 'bg-primary']);
        $this->metricsBuilder('penduduk', 'Penduduk Dewasa', $countPendudukDewasa, ['icon' => 'fa fa-user', 'color' => 'bg-warning']);
        $this->metricsBuilder('penduduk', 'Penduduk Lansia', $countPenduduklansia, ['icon' => 'fa fa-wheelchair', 'color' => 'bg-info']);
        $this->metricsBuilder('penduduk', 'Penduduk Meninggal', $countPendudukMeninggal, ['icon' => 'fa fa-user-times', 'color' => 'bg-danger']);
        $this->metricsBuilder('penduduk', 'Penduduk Perempuan', $countPendudukPerempuan, ['icon' => 'fa fa-venus', 'color' => 'bg-secondary']);
        $this->metricsBuilder('penduduk', 'Penduduk Laki-laki', $countPendudukLakiLaki, ['icon' => 'fa fa-mars', 'color' => 'bg-secondary']);

        // ========== pekerjaan
        $pekerjaan = $this->pendudukModel->select('jenis_pekerjaan, COUNT(jenis_pekerjaan) as total')->groupBy('jenis_pekerjaan')->get()->getResultArray();
        foreach ($pekerjaan as $key => $value) {
            $this->metricsBuilder('pekerjaan', $value['jenis_pekerjaan'] == "-" ? "Lainnya" : $value['jenis_pekerjaan'], $value['total'], ['icon' => 'fa fa-briefcase', 'color' => 'bg-light']);
        }

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

    protected function mapSuratMetrics($data)
    {
        if (empty($data)) {
            return [
                'selesai' => 0,
                'pending' => 0,
                'ditolak' => 0,
                'total'   => 0,
            ];
        }
        
        $temp = [];
        foreach ($data as $key => $value) {
            $temp['selesai'] = isset($data['selesai']) ? $value : (isset($data['approved']) ? $value : 0);
            $temp['pending'] = isset($data['pending']) ? $value : 0;
            $temp['ditolak'] = isset($data['ditolak']) ? $value : (isset($data['rejected']) ? $value : (isset($data['batal']) ? $value : 0));
        }

        // total
        $temp['total'] = 0;
        foreach ($temp as $key => $value) {
            $temp['total'] += $value;
        }

        return $temp;
    }

    // funtion to get random bg color
    protected function randomBgColor()
    {
        return $this->bgColors[array_rand($this->bgColors)];
    }
}
