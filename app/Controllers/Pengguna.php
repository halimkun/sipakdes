<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pengguna extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \Myth\Auth\Models\UserModel();
    }

    public function index()
    {
        $users = $this->userModel->select('users.id as user_id, id_penduduk, username, email, active, auth_groups.name as role, penduduk.*')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('penduduk', 'penduduk.id = users.id_penduduk', 'left')
            ->findAll();

        return view('pengguna/index', [
            'title' => 'Data Pengguna',
            'breadcrumbs' => [
                ['title' => 'Admin', 'url' => '/admin'],
                ['title' => 'Pengguna', 'url' => '/admin/pengguna', 'active' => true],
            ],
            'users' => $users,
        ]);
    }

    // resetPassword
    public function resetPassword()
    {
        $id = $this->request->getPost('user_id');
        // $password = $this->request->getPost('password');
        
        $user = $this->userModel->find($id);

        if ($user) {
            // id_penduduk != null
            if ($user->id_penduduk) {
                $penduduk = $this->pendudukModel->find($user->id_penduduk);
                if ($penduduk) {
                    $pass = date('dmY', strtotime($penduduk->tanggal_lahir));
                } else {
                    $pass = '12345678';
                }
            } else {
                $pass = '12345678';
            }

            // setPassword from User Entity
            $user->setPassword($pass);
            $this->userModel->save($user);

            return redirect()->to('/admin/pengguna')->with('success', 'Password berhasil direset');
        }

        return redirect()->to('/admin/pengguna')->with('error', 'pengguna tidak ditemukan');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            $active = $user->active ? 0 : 1;
            $this->userModel->update($id, ['active' => $active]);
            return redirect()->to('/admin/pengguna');
        }
        return redirect()->to('/admin/pengguna');
    }
}
