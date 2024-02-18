<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function toIndex()
    {
        return redirect()->to('/dashboard', 301);
    }

    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                ['title' => ucfirst(user()->username), 'url' => '/'],
                ['title' => 'Dashboard', 'url' => '/dashboard', 'active' => true],
            ],
        ]);
    }
}
