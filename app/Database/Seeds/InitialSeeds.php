<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class InitialSeeds extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        // --- ----- Groups users (admin, operator_kelurahan, admin_posyandu, warga) 

        // table auth_groups
        $data_auth_groups = [
            ['name' => 'admin', 'description' => 'System Administrator',],
            ['name' => 'operator_kelurahan', 'description' => 'Operator Kelurahan',],
            ['name' => 'warga', 'description' => 'Warga',],
        ];

        // check if table auth_groups is empty then insert data
        if ($this->db->table('auth_groups')->countAllResults() == 0) {
            $this->db->table('auth_groups')->insertBatch($data_auth_groups);
        }

        // --- ----- Users (admin)

        $data_users = [
            'email' => 'admin@mail.com',
            'username' => 'admin',
            'password' => 'admin',
            'active' => '1',
        ];

        // entitiy user
        $user = new \Myth\Auth\Entities\User($data_users);

        // if admin user not exist then insert admin user
        if ($this->db->table('users')->countAllResults() == 0 && $this->db->table('users')->where('email', $user->email)->countAllResults() == 0 && $this->db->table('users')->where('username', $user->username)->countAllResults() == 0) {
            $this->db->table('users')->insert($user->toArray());
        }


        // --- ----- Add admin to group admin

        // get id group admin
        $groupModel = new \Myth\Auth\Models\GroupModel();
        $group = $groupModel->where('name', 'admin')->first();

        // get id user admin
        $userModel = new \Myth\Auth\Models\UserModel();
        $user = $userModel->where('username', 'admin')->first();

        // check if admin not in group admin then add admin to group admin
        if ($this->db->table('auth_groups_users')->where('group_id', $group->id)->where('user_id', $user->id)->countAllResults() == 0) {
            $this->db->table('auth_groups_users')->insert(['group_id' => $group->id, 'user_id' => $user->id]);
        }


        // --- ----- Add other users (operator_kelurahan, operator_posyandu)
        $data_others_users = [
            [
                'email' => $faker->email,
                'username' => 'operator_kelurahan',
                'password' => 'operator_kelurahan',
                'active' => '1',
            ],
        ];

        // insert other users
        foreach ($data_others_users as $data_user) {
            $user = new \Myth\Auth\Entities\User($data_user);

            if ($this->db->table('users')->where('email', $user->email)->countAllResults() == 0 && $this->db->table('users')->where('username', $user->username)->countAllResults() == 0) {
                $this->db->table('users')->insert($user->toArray());
            }
        }


        // --- ----- Add other users to group (operator_kelurahan, operator_posyandu)
        foreach ($data_others_users as $data_user) {
            $user = new \Myth\Auth\Entities\User($data_user);

            // get id group
            $groupModel = new \Myth\Auth\Models\GroupModel();
            $group = $groupModel->where('name', $user->username)->first();

            // get id user
            $userModel = new \Myth\Auth\Models\UserModel();
            $user = $userModel->where('username', $user->username)->first();

            // check if user not in group then add user to group
            if ($this->db->table('auth_groups_users')->where('group_id', $group->id)->where('user_id', $user->id)->countAllResults() == 0) {
                $this->db->table('auth_groups_users')->insert(['group_id' => $group->id, 'user_id' => $user->id]);
            }
        }
    }
}
