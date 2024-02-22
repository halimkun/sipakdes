<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiteSetting extends Seeder
{
    public function run()
    {
        service('settings')->set('App.siteName', 'SIPAKDES');
        
        // desa
        service('settings')->set('App.kepalaDesa', 'Solehudin S.Sos');
    }
}
