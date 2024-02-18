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
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengguna', 'url' => '/pengguna', 'active' => true],
            ],
            'roles' => $roles_data,
            'users' => $users,
        ]);
    }

    // new 
    public function new()
    {
        $roles_data = $this->groupModel->select('*, id as value')->findAll();
        $roles_data = array_map(function ($role) {
            return $role->toArray();
        }, $roles_data);

        $fields = [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'username', 'label' => 'Username', 'type' => 'text', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => $roles_data, 'required' => true]
        ];

        return view('pengguna/new', [
            'title' => 'Tambah Pengguna',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengguna', 'url' => '/pengguna'],
                ['title' => 'Tambah', 'url' => '/pengguna/new', 'active' => true],
            ],
            'fields' => $fields,
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $role = $this->request->getPost('role');
        $data['active'] = 1;

        if (!$role) {
            $role = $this->groupModel->where('name', config(\Config\Auth::class)->defaultUserGroup)->first();
            $role = $role->id;
        }

        unset($data['role']);

        $user = new \Myth\Auth\Entities\User($data);

        if ($this->userModel->save($user)) {
            $user_id = $this->userModel->insertID();
            $this->groupModel->addUserToGroup($user_id, $role);

            return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil ditambahkan');
        }
    }

    // edit
    public function edit($id)
    {
        $user = $this->userModel->select('users.id as user_id, id_penduduk, username, email, active, auth_groups.name as role, auth_groups.id as role_id')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->find($id)->toArray();

        $roles_data = $this->groupModel->select('*, id as value')->findAll();
        $roles_data = array_map(function ($role) {
            return $role->toArray();
        }, $roles_data);

        $fields = [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true,],
            ['name' => 'username', 'label' => 'Username', 'type' => 'text', 'required' => true, 'readonly' => true,],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => $roles_data, 'selected' => $user['role_id'], 'required' => true,]
        ];

        return view('pengguna/edit', [
            'title' => 'Edit Pengguna',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Pengguna', 'url' => '/pengguna'],
                ['title' => 'Edit', 'url' => '/pengguna/' . $id . '/edit', 'active' => true],
            ],
            'fields' => $fields,
            'user' => $user,
            'id' => $id,
        ]);
    }

    // update
    public function update($id)
    {
        $data = $this->request->getPost();
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role' => 'required',
        ];

        unset($data['username']);

        if (!$this->validate($rules)) {
            return redirect()->to('/pengguna/' . $id . '/edit')->withInput()->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');

        if (!$role) {
            $role = $this->groupModel->where('name', config(\Config\Auth::class)->defaultUserGroup)->first();
            $role = $role->id;
        }

        // unset role
        unset($data['role']);

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/pengguna/' . $id . '/edit')->with('error', 'pengguna tidak ditemukan');
        }

        // check if same valur from data and user then unset
        foreach ($data as $key => $value) {
            if ($user->$key == $value) {
                unset($data[$key]);
            }
        }

        // tidak ada perubahan
        if (empty($data)) {
            $this->groupModel->removeUserFromAllGroups($id);
            $this->groupModel->addUserToGroup($id, $role);

            return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil diubah');
        }

        $user->fill($data);

        if ($this->userModel->save($user)) {
            $this->groupModel->removeUserFromAllGroups($id);
            $this->groupModel->addUserToGroup($id, $role);

            return redirect()->to('/pengguna')->with('success', 'Pengguna berhasil diubah');
        }
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
