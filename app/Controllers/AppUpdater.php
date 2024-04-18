<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Gitonomy\Git\Exception\ProcessException;
use Gitonomy\Git\Repository;

class AppUpdater extends BaseController
{
    public function update()
    {
        $msg = '';
        $status = 0;
        if (!shell_exec('which git')) {
            return redirect()->back()->with('error', 'Git is not installed on your server.');
        }

        // shell exe git pull 
        $output = shell_exec('git pull origin main 2>&1');

        if (strpos($output, 'Already up to date.') !== false) {
            $msg = 'Already up to date.';
            $status = 1;
        } elseif (strpos($output, 'error:') !== false || strpos($output, 'fatal:') !== false || strpos($output, 'error occurred') !== false || strpos($output, 'conflict') !== false) {
            $msg = 'Error while updating.';
            $status = 0;
        } else {
            $msg = 'Updated successfully.';
            $status = 1;
        }

        return redirect()->back()->with($status ? 'success' : 'error', $msg);
    }
}
