<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Profile extends BaseController
{
    protected $pendudukController;

    protected $pendudukModel;
    protected $userModel;

    public function __construct()
    {
        $this->pendudukController = new \App\Controllers\Penduduk();

        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Profile',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Profile', 'url' => '/profile', 'active' => true],
            ],

            'pendudukFields' => $this->pendudukFields(),
            'userFields' => $this->userFields(),

            'penduduk' => $this->pendudukModel->find(user()->id_penduduk)->toArray(),
            'user' => $this->userModel->find(user()->id)->toArray(),
        ];

        return view('profile/index', $data);
    }

    public function userUpdate()
    {
        $rules = [
            'id' => 'required|is_not_unique[users.id]',
            'email' => 'required|valid_email',
        ];

        // if in post contain password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[8]|strong_password|matches[password_confirmation]';
            $rules['password_confirmation'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $this->request->getPost('id'),
            'email' => $this->request->getPost('email'),
        ];

        // if in post contain password
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // entity user
        $entityUser = new \App\Entities\User($data);

        // save user
        if (!$this->userModel->save($entityUser)) {
            return redirect()->back()->withInput()->with('error', $this->userModel->errors());
        }

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui.');
    }

    protected function userFields()
    {
        return [
            ['name' => 'username', 'label' => 'Username', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
            
            // password field
            ['name' => 'password', 'label' => 'Password', 'type' => 'password',],
            ['name' => 'password_confirmation', 'label' => 'Konfirmasi Password', 'type' => 'password'],
        ];
    }

    protected function pendudukFields()
    {
        return [
            ['name' => 'kk', 'label' => 'KK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'disabled' => true, 'readonly' => true],
            ['name' => 'nik', 'label' => 'NIK', 'type' => 'number', 'min' => 0, 'maxlength' => 16, 'disabled' => true, 'readonly' => true],
            ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'disabled' => true, 'readonly' => true],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => $this->pendudukController->optionJenisKelamin, 'disabled' => true, 'readonly' => true],
            ['name' => 'golongan_darah', 'label' => 'Golongan Darah', 'type' => 'select', 'options' => $this->pendudukController->optionGolonganDarah, 'disabled' => true, 'readonly' => true],
            ['name' => 'agama', 'label' => 'Agama', 'type' => 'select', 'options' => $this->pendudukController->optionAgama, 'disabled' => true, 'readonly' => true],
            ['name' => 'pendidikan', 'label' => 'Pendidikan', 'type' => 'select', 'options' => $this->pendudukController->optionPendidikan, 'disabled' => true, 'readonly' => true],
            ['name' => 'jenis_pekerjaan', 'label' => 'Jenis Pekerjaan', 'type' => 'select', 'options' => $this->pendudukController->optionJenisPekerjaan, 'disabled' => true, 'readonly' => true],
            ['name' => 'hubungan', 'label' => 'Hubungan', 'type' => 'select', 'options' => $this->pendudukController->optionHubungan, 'disabled' => true, 'readonly' => true],
            ['name' => 'kewarganegaraan', 'label' => 'Kebangsaan', 'type' => 'select', 'options' => $this->pendudukController->optionKewarganegaraan, 'disabled' => true, 'readonly' => true],
            ['name' => 'status_perkawinan', 'label' => 'Stts Perkawinan', 'type' => 'select', 'options' => $this->pendudukController->optionStatusPerkawinan, 'disabled' => true, 'readonly' => true],
            ['name' => 'is_kepala_keluarga', 'label' => 'Kepala Keluarga', 'type' => 'select', 'options' => $this->pendudukController->optionIsKepalaKeluarga, 'disabled' => true, 'readonly' => true],
            ['name' => 'rt', 'label' => 'RT', 'type' => 'number', 'disabled' => true, 'readonly' => true],
            ['name' => 'rw', 'label' => 'RW', 'type' => 'number', 'disabled' => true, 'readonly' => true],
            ['name' => 'kelurahan', 'label' => 'Kelurahan', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'kabupaten', 'label' => 'Kabupaten', 'type' => 'text', 'disabled' => true, 'readonly' => true],
            ['name' => 'provinsi', 'label' => 'Provinsi', 'type' => 'text', 'disabled' => true, 'readonly' => true],
        ];
    }
}
