<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Penduduk extends BaseController
{
    protected $berkasKkModel;
    protected $pendudukModel;
    protected $userModel;

    // Options 
    protected $optionJenisKelamin = [["value" => "Laki-laki", "name" => "Laki-laki"], ["value" => "Perempuan", "name" => "Perempuan"]];
    protected $optionGolonganDarah = [["value" => "-", "name" => "-"], ["value" => "A", "name" => "A"], ["value" => "B", "name" => "B"], ["value" => "AB", "name" => "AB"], ["value" => "O", "name" => "O"]];
    protected $optionAgama = [["value" => "Islam", "name" => "Islam"], ["value" => "Kristen", "name" => "Kristen"], ["value" => "Katolik", "name" => "Katolik"], ["value" => "Hindu", "name" => "Hindu"], ["value" => "Budha", "name" => "Budha"], ["value" => "Konghucu", "name" => "Konghucu"]];
    protected $optionPendidikan = [["value" => "Tidak Sekolah", "name" => "Tidak Sekolah"], ["value" => "SD", "name" => "SD"], ["value" => "SMP", "name" => "SMP"], ["value" => "SMA", "name" => "SMA"], ["value" => "Diploma", "name" => "Diploma"], ["value" => "S1", "name" => "S1"], ["value" => "S2", "name" => "S2"], ["value" => "S3", "name" => "S3"]];
    protected $optionJenisPekerjaan = [["value" => "Tidak Bekerja", "name" => "Tidak Bekerja"], ["value" => "Pelajar/Mahasiswa", "name" => "Pelajar/Mahasiswa"], ["value" => "PNS", "name" => "PNS"], ["value" => "TNI", "name" => "TNI"], ["value" => "POLRI", "name" => "POLRI"], ["value" => "Swasta", "name" => "Swasta"], ["value" => "Wiraswasta", "name" => "Wiraswasta"], ["value" => "Petani", "name" => "Petani"], ["value" => "Nelayan", "name" => "Nelayan"], ["value" => "Ibu Rumah Tangga", "name" => "Ibu Rumah Tangga"], ["value" => "Lainnya", "name" => "Lainnya"]];
    protected $optionHubungan = [["value" => "Kepala Keluarga", "name" => "Kepala Keluarga"], ["value" => "Ayah", "name" => "Ayah"], ["value" => "Ibu", "name" => "Ibu"], ["value" => "Anak", "name" => "Anak"]];
    protected $optionKewarganegaraan = [["value" => "WNI", "name" => "WNI"], ["value" => "WNA", "name" => "WNA"]];
    protected $optionStatusPerkawinan = [["value" => "Belum Kawin", "name" => "Belum Kawin"], ["value" => "Kawin", "name" => "Kawin"], ["value" => "Cerai Hidup", "name" => "Cerai Hidup"], ["value" => "Cerai Mati", "name" => "Cerai Mati"]];

    public function __construct()
    {
        $this->berkasKkModel = new \App\Models\BerkasKkModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $penduduk = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status as status_berkas, berkas_kk.keterangan as keterangan_berkas')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->orderBy('penduduk.nama', 'ASC')
            ->findAll();

        return view('penduduk/index', [
            'title' => 'Data Penduduk',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Penduduk', 'url' => '/penduduk', 'active' => true],
            ],
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
            'title' => 'Tambah Penduduk',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Penduduk', 'url' => '/penduduk'],
                ['title' => 'Tambah', 'url' => '/penduduk/new', 'active' => true],
            ],
            'fields' => $fields,
        ]);
    }

    // store
    public function store()
    {
        $rules = [
            'kk'                => 'required|numeric|exact_length[16]',
            'nik'               => 'required|numeric|exact_length[16]|is_unique[penduduk.nik]',
            'nama'              => 'required|alpha_space|min_length[5]',
            'tempat_lahir'      => 'required|alpha_space|min_length[5]',
            'tanggal_lahir'     => 'required|valid_date',
            'jenis_kelamin'     => 'required|in_list[Laki-laki,Perempuan]',
            'golongan_darah'    => 'required|in_list[-,A,B,AB,O]',
            'agama'             => 'required|in_list[-,Islam,Kristen,Katolik,Hindu,Budha,Konghucu]',
            'pendidikan'        => 'required|in_list[Tidak Sekolah,SD,SMP,SMA,Diploma,S1,S2,S3]',
            'jenis_pekerjaan'   => 'required|in_list[Tidak Bekerja,Pelajar/Mahasiswa,PNS,TNI,POLRI,Swasta,Wiraswasta,Petani,Nelayan,Ibu Rumah Tangga,Lainnya]',
            'hubungan'          => 'required|in_list[Kepala Keluarga,Ayah,Ibu,Anak]',
            'kewarganegaraan'   => 'required|in_list[WNI,WNA]',
            'status_perkawinan' => 'required|in_list[Belum Kawin,Kawin,Cerai Hidup,Cerai Mati]',
            'rt'                => 'required|numeric',
            'rw'                => 'required|numeric',
            'kelurahan'         => 'required|alpha_numeric_punct',
            'kecamatan'         => 'required|alpha_numeric_punct',
            'kabupaten'         => 'required|alpha_numeric_punct',
            'provinsi'          => 'required|alpha_numeric_punct',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->withInput();
        }

        if ($this->pendudukModel->save($this->request->getPost())) {
            $tgl_lahir = $this->request->getPost('tanggal_lahir');
            $user_data = [
                'id_penduduk'   => $this->pendudukModel->getInsertID(),
                'username'      => $this->request->getPost('nik'),
                'email'         => '-',
                'password'      => date('dmY', strtotime($tgl_lahir ?? 'now')),
                'active'        => 1,
            ];

            // entitiy user
            $user = new \App\Entities\User($user_data);

            // if user not exist then insert user
            if ($this->userModel->where('username', $user->username)->countAllResults() == 0) {
                $this->userModel->withGroup(config(\Config\Auth::class)->defaultUserGroup)->save($user);
            }

            return redirect()->to('/penduduk')->with('success', 'Data penduduk berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->pendudukModel->errors())->withInput();
        }
    }

    // edit
    public function edit($id)
    {
        if (!$id) return redirect()->to('/penduduk')->with('error', 'Data penduduk tidak ditemukan.');

        $penduduk = $this->pendudukModel->find($id);

        if (!$penduduk) return redirect()->to('/penduduk')->with('error', 'Data penduduk tidak ditemukan.');

        $fields = [
            ['name' => 'id', 'label' => 'ID', 'type' => 'hidden', 'required' => true], // hidden field 'id
            ['name' => 'kk', 'label' => 'KK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true],
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true, 'readonly' => true, 'disabled' => true],
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

        return view('penduduk/edit', [
            'title' => 'Ubah Penduduk',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Penduduk', 'url' => '/penduduk'],
                ['title' => 'Ubah', 'url' => '/penduduk/edit/' . $id, 'active' => true],
            ],

            'id' => $id,
            'fields' => $fields,
            'penduduk' => $penduduk->toArray()
        ]);
    }

    // update
    public function update($id)
    {
        $rules = [
            'kk'                => 'required|numeric|exact_length[16]',
            'nama'              => 'required|alpha_space|min_length[5]',
            'tempat_lahir'      => 'required|alpha_space|min_length[5]',
            'tanggal_lahir'     => 'required|valid_date',
            'jenis_kelamin'     => 'required|in_list[Laki-laki,Perempuan]',
            'golongan_darah'    => 'required|in_list[-,A,B,AB,O]',
            'agama'             => 'required|in_list[-,Islam,Kristen,Katolik,Hindu,Budha,Konghucu]',
            'pendidikan'        => 'required|in_list[Tidak Sekolah,SD,SMP,SMA,Diploma,S1,S2,S3]',
            'jenis_pekerjaan'   => 'required|in_list[Tidak Bekerja,Pelajar/Mahasiswa,PNS,TNI,POLRI,Swasta,Wiraswasta,Petani,Nelayan,Ibu Rumah Tangga,Lainnya]',
            'hubungan'          => 'required|in_list[Kepala Keluarga,Ayah,Ibu,Anak]',
            'kewarganegaraan'   => 'required|in_list[WNI,WNA]',
            'status_perkawinan' => 'required|in_list[Belum Kawin,Kawin,Cerai Hidup,Cerai Mati]',
            'rt'                => 'required|numeric',
            'rw'                => 'required|numeric',
            'kelurahan'         => 'required|alpha_numeric_punct',
            'kecamatan'         => 'required|alpha_numeric_punct',
            'kabupaten'         => 'required|alpha_numeric_punct',
            'provinsi'          => 'required|alpha_numeric_punct',
        ];

        // if in post contain nik
        if ($this->request->getPost('nik')) {
            $rules['nik'] = 'required|numeric|exact_length[16]|is_unique[penduduk.nik,id,' . $id . ']';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->withInput();
        }

        // update pakai metode fill
        $penduduk = $this->pendudukModel->find($id);

        if ($penduduk) {
            $penduduk->fill($this->request->getPost());

            if ($this->pendudukModel->save($penduduk)) {
                return redirect()->to('/penduduk')->with('success', 'Data penduduk berhasil diubah.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->pendudukModel->errors())->withInput();
            }
        } else {
            return redirect()->to('/penduduk')->with('errors', 'Data penduduk tidak ditemukan.');
        }
    }

    // toggle is_verified
    public function toggle_verified($id)
    {
        if (!$id) return redirect()->to('/penduduk')->with('errors', 'Data penduduk tidak ditemukan.');

        $penduduk = $this->pendudukModel->find($id);

        // is_verified field 0 and 1
        if ($penduduk) {
            $penduduk->is_verified = $penduduk->is_verified == 0 ? 1 : 0;

            if ($this->pendudukModel->save($penduduk)) {
                return redirect()->to('/penduduk')->with('success', 'Data penduduk berhasil ' . ($penduduk->is_verified == 1 ? 'diverifikasi.' : 'dibatalkan verifikasi.'));
            } else {
                return redirect()->to('/penduduk')->with('errors', $this->pendudukModel->errors());
            }
        } else {
            return redirect()->to('/penduduk')->with('errors', 'Data penduduk tidak ditemukan.');
        }
    }

    // upload kk
    public function uploadKk()
    {
        $rules = [
            'kk'     => 'required|numeric|exact_length[16]',
            'berkas' => 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,jpg,jpeg,png]|mime_in[berkas,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // move with random name
        $file_name = $this->request->getFile('berkas')->getRandomName();

        // find old data by kk
        $old_data = $this->berkasKkModel->where('kk', $this->request->getPost('kk'))->first();

        $data = [
            'kk'         => $this->request->getPost('kk'),
            'berkas'     => $file_name,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($old_data) {
            $data['id'] = $old_data->id;
        }

        if ($this->berkasKkModel->save($data)) {
            // delete old data if exist
            if ($old_data && file_exists('uploads/bkk/' . $old_data->berkas)) {
                unlink('uploads/bkk/' . $old_data->berkas);
            }

            // move file to uploads/bkk
            $this->request->getFile('berkas')->move('uploads/bkk', $file_name);
            
            return redirect()->to('/penduduk')->with('success', 'Berkas KK berhasil diunggah.');
        } else {
            return redirect()->to('/penduduk')->with('errors', $this->berkasKkModel->errors());
        }
    }


    // delete
    public function delete($id)
    {
        // penduduk
        $penduduk = $this->pendudukModel->find($id);

        if ($penduduk) {
            // user
            $user = $this->userModel->where('id_penduduk', $id)->first();

            if ($user) {
                $this->userModel->delete($user->id);
            }

            if ($this->pendudukModel->delete($id)) {
                return redirect()->to('/penduduk')->with('success', 'Data penduduk berhasil dihapus.');
            } else {
                return redirect()->to('/penduduk')->with('errors', $this->pendudukModel->errors());
            }
        } else {
            return redirect()->to('/penduduk')->with('errors', 'Data penduduk tidak ditemukan.');
        }
    }
}
