<?php

namespace App\Exports;

use App\Models\ProjectRkapItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RkapExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection()
    {
        return ProjectRkapItem::where('project_id', $this->projectId)
            ->orderBy('order')
            ->get();
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

    public function map($item): array
    {
        return [
            $item->no,
            $item->tahun,
            $item->kode_anggaran,
            $item->detail_rencana,
            $item->nilai_rkap,
        ];
    }
}