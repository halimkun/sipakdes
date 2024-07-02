<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Keluarga extends BaseController
{
    protected $berkasKkModel;
    protected $pendudukModel;
    protected $userModel;

    protected $pendudukController;

    public function __construct()
    {
        $this->berkasKkModel = new \App\Models\BerkasKkModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();

        $this->pendudukController = new \App\Controllers\Penduduk();
    }

    public function index()
    {
        $user = new \App\Entities\User(user()->toArray());
        $keluarga = $this->pendudukModel->select('penduduk.*, berkas_kk.berkas, berkas_kk.status as status_berkas, berkas_kk.keterangan as keterangan_berkas')
            ->join('berkas_kk', 'berkas_kk.kk = penduduk.kk', 'left')
            ->orderBy('penduduk.nama', 'ASC')
            ->where('penduduk.kk', $user->pendudukData()->kk)
            ->findAll();

        $kepalakeluarga = $this->pendudukModel->where('kk', $user->pendudukData()->kk)
            ->where('is_kepala_keluarga', 1)
            ->first();

        $berkasKk = $this->berkasKkModel->where('kk', $user->pendudukData()->kk)->first();

        return view('keluarga/index', [
            'title' => 'Data Anggota Keluarga Anda',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Keluarga', 'url' => '/keluarga', 'active' => true],
            ],

            'user' => $user,
            'berkasKk' => $berkasKk,
            'keluarga' => $keluarga,
            'kepalakeluarga' => $kepalakeluarga,
        ]);
    }

    public function new()
    {
        $user = new \App\Entities\User(user()->toArray());
        $fields = [
            ['name' => 'kk', 'label' => 'Nomor KK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true, 'readonly' => true], // hidden field 'kk
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true],
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => $this->pendudukController->optionJenisKelamin, 'required' => true],
            ['name' => 'golongan_darah', 'label' => 'Golongan Darah', 'type' => 'select', 'options' => $this->pendudukController->optionGolonganDarah, 'required' => true],
            ['name' => 'agama', 'label' => 'Agama', 'type' => 'select', 'options' => $this->pendudukController->optionAgama, 'required' => true],
            ['name' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'select', 'options' => $this->pendudukController->optionPendidikan, 'required' => true],
            ['name' => 'jenis_pekerjaan', 'label' => 'Jenis Pekerjaan', 'type' => 'select', 'options' => $this->pendudukController->optionJenisPekerjaan, 'required' => true],
            ['name' => 'hubungan', 'label' => 'Hubungan', 'type' => 'select', 'options' => $this->pendudukController->optionHubungan, 'required' => true],
            ['name' => 'kewarganegaraan', 'label' => 'Kebangsaan', 'type' => 'select', 'options' => $this->pendudukController->optionKewarganegaraan, 'required' => true],
            ['name' => 'status_perkawinan', 'label' => 'Stts Perkawinan', 'type' => 'select', 'options' => $this->pendudukController->optionStatusPerkawinan, 'required' => true],
            ['name' => 'is_kepala_keluarga', 'label' => 'Kepala Keluarga', 'type' => 'select', 'options' => $this->pendudukController->optionIsKepalaKeluarga, 'required' => true],
            ['name' => 'rt', 'label' => 'RT', 'type' => 'number', 'required' => true],
            ['name' => 'rw', 'label' => 'RW', 'type' => 'number', 'required' => true],
            ['name' => 'kelurahan', 'label' => 'Desa', 'type' => 'text', 'required' => true],
            ['name' => 'kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'required' => true],
            ['name' => 'kabupaten', 'label' => 'Kabupaten', 'type' => 'text', 'required' => true],
            ['name' => 'provinsi', 'label' => 'Provinsi', 'type' => 'text', 'required' => true],
        ];

        return view('keluarga/new', [
            'title'       => 'Tambah Keluarga',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Keluarga', 'url' => '/keluarga'],
                ['title' => 'Tambah', 'url' => '/keluarga/new', 'active' => true],
            ],

            'fields'   => $fields,
            'keluarga' => [
                'kk' => $user->pendudukData()->kk,
            ]
        ]);
    }

    public function edit($id)
    {
        if (!$id) return redirect()->to('/keluarga')->with('error', 'Data keluarga tidak ditemukan.');

        $keluarga = $this->pendudukModel->find($id);

        if (!$keluarga) return redirect()->to('/keluarga')->with('error', 'Data keluarga tidak ditemukan.');

        $fields = [
            ['name' => 'kk', 'label' => 'Nomor KK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true, 'readonly' => isMarkFilled($keluarga->kk), 'disabled' => isMarkFilled($keluarga->kk)],  // hidden field 'kk'
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'required' => true, 'readonly' => isMarkFilled($keluarga->nik), 'disabled' => isMarkFilled($keluarga->nik)],
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => $this->pendudukController->optionJenisKelamin, 'required' => true],
            ['name' => 'golongan_darah', 'label' => 'Golongan Darah', 'type' => 'select', 'options' => $this->pendudukController->optionGolonganDarah, 'required' => true],
            ['name' => 'agama', 'label' => 'Agama', 'type' => 'select', 'options' => $this->pendudukController->optionAgama, 'required' => true],
            ['name' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'select', 'options' => $this->pendudukController->optionPendidikan, 'required' => true],
            ['name' => 'jenis_pekerjaan', 'label' => 'Jenis Pekerjaan', 'type' => 'select', 'options' => $this->pendudukController->optionJenisPekerjaan, 'required' => true],
            ['name' => 'hubungan', 'label' => 'Hubungan', 'type' => 'select', 'options' => $this->pendudukController->optionHubungan, 'required' => true],
            ['name' => 'kewarganegaraan', 'label' => 'Kebangsaan', 'type' => 'select', 'options' => $this->pendudukController->optionKewarganegaraan, 'required' => true],
            ['name' => 'status_perkawinan', 'label' => 'Stts Perkawinan', 'type' => 'select', 'options' => $this->pendudukController->optionStatusPerkawinan, 'required' => true],
            ['name' => 'is_kepala_keluarga', 'label' => 'Kepala Keluarga', 'type' => 'select', 'options' => $this->pendudukController->optionIsKepalaKeluarga, 'required' => true],
            ['name' => 'rt', 'label' => 'RT', 'type' => 'number', 'required' => true],
            ['name' => 'rw', 'label' => 'RW', 'type' => 'number', 'required' => true],
            ['name' => 'kelurahan', 'label' => 'Desa', 'type' => 'text', 'required' => true],
            ['name' => 'kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'required' => true],
            ['name' => 'kabupaten', 'label' => 'Kabupaten', 'type' => 'text', 'required' => true],
            ['name' => 'provinsi', 'label' => 'Provinsi', 'type' => 'text', 'required' => true],
        ];

        return view('keluarga/edit', [
            'title' => 'Ubah Keluarga',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Keluarga', 'url' => '/keluarga'],
                ['title' => 'Ubah', 'url' => '/keluarga/edit/' . $id, 'active' => true],
            ],

            'id' => $id,
            'fields' => $fields,
            'keluarga' => $keluarga->toArray()
        ]);
    }

    public function store()
    {
        $user = new \App\Entities\User(user()->toArray());
        $rules = [
            // 'kk'                => 'required|numeric|exact_length[16]',
            'nik'               => 'required|numeric|exact_length[16]|is_unique[penduduk.nik]',
            'nama'              => 'required|alpha_space|min_length[5]',
            'tempat_lahir'      => 'required|alpha_space|min_length[5]',
            'tanggal_lahir'     => 'required|valid_date',
            'jenis_kelamin'     => 'required|in_list[Laki-laki,Perempuan]',
            'golongan_darah'    => 'required|in_list[-,A,B,AB,O]',
            'agama'             => 'required|in_list[-,Islam,Kristen,Katolik,Hindu,Budha,Konghucu]',
            'pendidikan'        => 'required|in_list[Tidak Sekolah,SD,SMP,SMA,Diploma,S1,S2,S3]',
            'jenis_pekerjaan'   => 'required|in_list[Tidak Bekerja,Pelajar/Mahasiswa,PNS,TNI,POLRI,Swasta,Wiraswasta,Petani,Nelayan,Ibu Rumah Tangga,Lainnya]',
            'hubungan'          => 'required|in_list[Ayah,Ibu,Anak]',
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

        $data = $this->request->getPost();

        // replace kk with authenticated user kk to avoid manipulation data 
        unset($data['kk']);
        $data['kk'] = $user->pendudukData()->kk;

        // check username (nik) already exist
        if ($this->userModel->where('username', $this->request->getPost('nik'))->countAllResults() > 0) {
            return redirect()->to('/keluarga')->with('error', 'Data penduduk sudah ada.');
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

            return redirect()->to('/keluarga')->with('success', 'Data penduduk berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->pendudukModel->errors())->withInput();
        }
    }

    public function update($id)
    {
        $user = new \App\Entities\User(user()->toArray());
        $rules = [
            'nama'              => 'required|alpha_space|min_length[5]',
            'tempat_lahir'      => 'required|alpha_space',
            'tanggal_lahir'     => 'required|valid_date',
            'jenis_kelamin'     => 'required|in_list[Laki-laki,Perempuan]',
            'golongan_darah'    => 'required|in_list[-,A,B,AB,O]',
            'agama'             => 'required|in_list[-,Islam,Kristen,Katolik,Hindu,Budha,Konghucu]',
            'pendidikan'        => 'required|in_list[Tidak Sekolah,SD,SMP,SMA,Diploma,S1,S2,S3]',
            'jenis_pekerjaan'   => 'required|in_list[Tidak Bekerja,Pelajar/Mahasiswa,PNS,TNI,POLRI,Swasta,Wiraswasta,Petani,Nelayan,Ibu Rumah Tangga,Lainnya]',
            'hubungan'          => 'required|in_list[Ayah,Ibu,Anak]',
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

        // if in post contain kk
        if ($this->request->getPost('kk')) {
            $rules['kk'] = 'required|numeric|exact_length[16]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->withInput();
        }

        // update pakai metode fill
        $penduduk = $this->pendudukModel->find($id);

        if ($penduduk) {
            if ($penduduk->kk != $user->pendudukData()->kk) {
                return redirect()->to('/keluarga')->with('error', 'Data yang anda ubah tidak termasuk dalam keluarga anda.');
            }

            $penduduk->fill($this->request->getPost());
            
            if (!$penduduk->hasChanged()) {
                return redirect()->back()->with('info', 'Tidak ada data yang diubah.');
            }

            if ($this->pendudukModel->save($penduduk)) {
                return redirect()->back()->with('success', 'Data penduduk berhasil diubah.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->pendudukModel->errors())->withInput();
            }
        } else {
            return redirect()->to('/keluarga')->with('errors', 'Data penduduk tidak ditemukan.');
        }
    }

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

            return redirect()->to('/keluarga')->with('success', 'Berkas KK berhasil diunggah.');
        } else {
            return redirect()->to('/keluarga')->with('errors', $this->berkasKkModel->errors());
        }
    }

    public function delete($id)
    {
        // penduduk
        $penduduk = $this->pendudukModel->find($id);

        if ($penduduk) {
            // fing all keluarga by kk if > then do delete procedure if == 1 dont delete
            $countKeluarga = $this->pendudukModel->where('kk', $penduduk->kk)->countAllResults();

            if ($countKeluarga > 1) {
                $user = $this->userModel->where('id_penduduk', $id)->first();

                if ($user) {
                    $this->userModel->delete($user->id);
                }

                if ($this->pendudukModel->delete($id)) {
                    return redirect()->to('/keluarga')->with('success', 'Data penduduk berhasil dihapus.');
                } else {
                    return redirect()->to('/keluarga')->with('errors', $this->pendudukModel->errors());
                }
            } else {
                return redirect()->to('/keluarga')->with('error', 'Data penduduk tidak bisa dihapus, setidaknya harus ada 1 anggota keluarga.');
            }
        } else {
            return redirect()->to('/keluarga')->with('error', 'Data penduduk tidak ditemukan.');
        }
    }
}
