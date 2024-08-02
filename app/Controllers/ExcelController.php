<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PendudukModel;
use App\Models\UserModel;

class ExcelController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function import()
    {
        // validate file
        $rules = [
            'berkas'    => 'uploaded[berkas]|ext_in[berkas,xlsx,xls]|mime_in[berkas,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // get file
        $file = $this->request->getFile('berkas');

        // read excel
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);
        
        // ignore first row
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        array_shift($rows);

        // get columns from table penduduks
        $pendudukModel = new PendudukModel();
        $columns = $pendudukModel->getFieldNames('penduduk');

        // remove id, created_at, updated_at, deleted_at
        $columns = array_diff($columns, ['id', 'created_at', 'updated_at', 'deleted_at']);
        
        // validate columns
        $columnsExcel = $rows[0];
        if (count($columns) != count($columnsExcel)) {
            return redirect()->back()->withInput()->with('errors', ['Columns in excel must be ' . count($columns)]);
        }

        // validate columns
        foreach ($columns as $key => $column) {
            if ($column != $columnsExcel[$key - 1]) {
                return redirect()->back()->withInput()->with('errors', ['Columns in excel must be ' . implode(', ', $columns)]);
            }
        }

        // remove first row
        array_shift($rows);

        // insert data
        $pendudukModel->transBegin();
        foreach ($rows as $row) {
            $data = [];
            foreach ($columns as $key => $column) {
                if ($column == 'tanggal_lahir') {
                    $data[$column] = date('Y-m-d', strtotime($row[$key - 1]));
                    continue;
                }
                $data[$column] = $row[$key - 1];
            }

            $pendudukModel->insert($data);

            // check if user exists
            $user = $this->userModel->where('username', $data['nik'])->first();
            if (!$user) {
                $this->userModel->withGroup(config(\Config\Auth::class)->defaultUserGroup)->save(new \App\Entities\User([
                    'id_penduduk' => $pendudukModel->getInsertID(),
                    'username'    => $data['nik'],
                    'password'    => date('dmY', strtotime($data['tanggal_lahir'])),
                    'email'       => $data['email'] ?? '-',
                    'active'      => 1
                ]));
            }
        }
        $pendudukModel->transCommit();

        return redirect()->back()->with('success', 'Data has been imported');
    }

    public function export()
    {
        // get columns from table penduduks
        $pendudukModel = new PendudukModel();
        $columns = $pendudukModel->getFieldNames('penduduk');

        // remove id, created_at, updated_at, deleted_at
        $columns = array_diff($columns, ['id', 'created_at', 'updated_at', 'deleted_at']);

        // create excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // set headers
        $sheet->fromArray($columns, NULL, 'A1');

        // set size columns to auto
        foreach ($columns as $key => $column) {
            $sheet->getColumnDimension($this->getAlphabetByNumber($key))->setAutoSize(true);
        }

        // set headers style
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setSize(14);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFill()->getStartColor()->setARGB('D3D3D3');

        // get data from table penduduks
        $penduduks = $pendudukModel->findAll();
        
        // penduduks to array
        $penduduks = array_map(function ($penduduk) use ($columns) {
            $data = [];
            foreach ($columns as $column) {
                $data[] = $penduduk->{$column};
            }
            return $data;
        }, $penduduks);

        // set data
        $sheet->fromArray($penduduks, NULL, 'A2');

        // format all columns to text
        $sheet->getStyle('A2:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // set response
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="penduduk.xlsx"')
            ->setBody($writer->save('php://output'))
            ->send();
    }

    public function template()
    {
        return $this->response->download(WRITEPATH . 'uploads/template.xlsx', null);
    }

    private function getAlphabetByNumber($number)
    {
        $alphabet = range('A', 'Z');
        $result = '';
        while ($number >= 0) {
            $result = $alphabet[$number % 26] . $result;
            $number = intval($number / 26) - 1;
        }
        return $result;
    }
}
