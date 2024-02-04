<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Penduduk extends BaseController
{
    public function index()
    {
        return view('penduduk/index');
    }
}
