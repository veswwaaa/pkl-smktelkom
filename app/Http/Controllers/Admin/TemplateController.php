<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TemplateController extends Controller
{
    public function downloadSiswaTemplate()
    {
        $filename = "template_import_siswa.xlsx";

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['nis', 'nama', 'kelas', 'jenis_kelamin', 'tahun_ajaran', 'jurusan'];
        foreach ($headers as $key => $header) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
        }

        // Contoh data
        $data = [
            ['123456789', 'Ahmad Siswa', 'XII RPL 1', 'Laki-laki', '2023/2024', 'RPL'],
            ['987654321', 'Siti Siswi', 'XII TKJ 2', 'Perempuan', '2023/2024', 'TKJ']
        ];

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                // Gunakan setCellValueExplicit untuk NIS agar tidak dianggap angka/scientific notation
                if ($colIndex === 0) {
                    $sheet->setCellValueExplicitByColumnAndRow($colIndex + 1, $rowIndex + 2, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 2, $value);
                }
            }
        }

        // Auto size columns
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // Header styling
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename);
    }
}
