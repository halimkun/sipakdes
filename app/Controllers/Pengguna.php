<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pengguna extends BaseController
{
    protected $userModel;
    protected $pendudukModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new \Myth\Auth\Models\UserModel();
        $this->pendudukModel = new \App\Models\PendudukModel();
        $this->groupModel = new \Myth\Auth\Models\GroupModel();
    }

    public function index()
    {
        $users = $this->userModel->select('users.id as user_id, id_penduduk, username, email, active, auth_groups.name as role, penduduk.*')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('penduduk', 'penduduk.id = users.id_penduduk', 'left')
            ->findAll();

        // myth auth get all roles
        $roles_data = $this->groupModel->findAll();

        return view('pengguna/index', [
            'title' => 'Data Pengguna',
            'breadcrumbs' => [
                ['title' => 'Admin', 'url' => '/'],
                ['title' => 'Pengguna', 'url' => '/pengguna', 'active' => true],
            ],
            'roles' => $roles_data,
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

            return redirect()->to('/pengguna')->with('success', 'Password berhasil direset');
        }

        return redirect()->to('/pengguna')->with('error', 'pengguna tidak ditemukan');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            $user->setActive(!$user->active);
            $this->userModel->save($user);

            return redirect()->to('/pengguna')->with('success', 'Status berhasil diubah');
        }
        return redirect()->to('/pengguna')->with('error', 'pengguna tidak ditemukan');
    }

    public function changeRole($id)
    {
        $user = $this->userModel->find($id);
        $role = $this->request->getPost('role');

        if (!$role) {
            $role = $this->groupModel->where('name', config(\Config\Auth::class)->defaultUserGroup)->first();
            $role = $role->id;
        }

        if ($user) {
            // clear group
            $this->groupModel->removeUserFromAllGroups($user->id);

            // add to group
            $this->groupModel->addUserToGroup($user->id, $role);

            return redirect()->to('/pengguna')->with('success', 'Role berhasil diubah');
        }

        return redirect()->to('/pengguna')->with('error', 'pengguna tidak ditemukan');
    }
}
