<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Settings extends BaseController
{
    public function index()
    {
        // read app-info.json file
        $app_info = json_decode(file_get_contents(ROOTPATH . 'app-info.json'), true);
        $data = [
            'title' => 'Settings',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Settings', 'url' => '/settings', 'active' => true],
            ],

            "info" => $app_info,

            'settingsFields' => $this->settingsFields(),
            'settingDesaFields' => $this->settingDesaFields(),

            'settings' => [
                'App.siteName' => service('settings')->get('App.siteName'),
            ],

            'settingDesa' => [
                'App.desa' => service('settings')->get('App.desa'),
                'App.kecamatan' => service('settings')->get('App.kecamatan'),
                'App.kabupaten' => service('settings')->get('App.kabupaten'),

                'App.kepalaDesa' => service('settings')->get('App.kepalaDesa'),
            ],
        ];

        return view('setting/index', $data);
    }

    // main settings
    public function update()
    {
        $rules = [];
        foreach ($this->settingsFields() as $field) {
            // replace . with _ on field name
            $fname = str_replace('.', '_', $field['name']);
            $rules[$fname] = 'required|alpha_numeric_punct|strip_tags|max_length[255]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        foreach ($this->settingsFields() as $field) {
            // replace . with _ on field name
            $fname = str_replace('.', '_', $field['name']);
            service('settings')->set($field['name'], $this->request->getPost($fname));
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    // desa settings
    public function updateDesa()
    {
        $rules = [];
        foreach ($this->settingDesaFields() as $field) {
            // replace . with _ on field name
            $fname = str_replace('.', '_', $field['name']);
            $rules[$fname] = 'required|alpha_numeric_punct|strip_tags|max_length[255]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        foreach ($this->settingDesaFields() as $field) {
            // replace . with _ on field name
            $fname = str_replace('.', '_', $field['name']);
            service('settings')->set($field['name'], $this->request->getPost($fname));
        }

        return redirect()->back()->with('success', 'Settings desa updated successfully');
    }

    protected function settingsFields()
    {
        return [
            ['name' => 'App.siteName', 'label' => 'Site Name', 'type' => 'text', 'required' => true],
        ];
    }

    protected function settingDesaFields()
    {
        return [
            ['name' => 'App.desa', 'label' => 'Nama Desa', 'type' => 'text', 'required' => true],
            ['name' => 'App.kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'required' => true],
            ['name' => 'App.kabupaten', 'label' => 'Kabupaten', 'type' => 'text', 'required' => true],
            ['name' => 'App.kepalaDesa', 'label' => 'Kepala Desa', 'type' => 'text', 'required' => true],
        ];
    }
}
