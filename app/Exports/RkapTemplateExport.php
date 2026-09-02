<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RkapTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['1', date('Y'), 'KODE001', 'Contoh Rencana 1', '1000000'],
            ['2', date('Y'), 'KODE002', 'Contoh Rencana 2', '2000000'],
            ['3', date('Y'), 'KODE003', 'Contoh Rencana 3', '3000000'],
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'TAHUN',
            'KODE ANGGARAN',
            'DETAIL RENCANA',
            'NILAI RKAP',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}