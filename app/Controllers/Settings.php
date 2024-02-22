<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Settings extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Settings',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Settings', 'url' => '/settings', 'active' => true],
            ],

            'settingsFields' => $this->settingsFields(),
            'settingDesaFields' => $this->settingDesaFields(),

            'settings' => [
                'App.siteName' => service('settings')->get('App.siteName'),
            ],

            'settingDesa' => [
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
            ['name' => 'App.kepalaDesa', 'label' => 'Kepala Desa', 'type' => 'text', 'required' => true],
        ];
    }
}
