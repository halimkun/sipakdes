<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StartHere extends Seeder
{
    public function run()
    {
        $this->call('InitialSeeds');
        $this->call('SiteSetting');
        $this->call('DummyPenduduk');
    }
}
