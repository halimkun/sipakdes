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
}
