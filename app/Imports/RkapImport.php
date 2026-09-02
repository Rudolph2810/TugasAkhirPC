<?php

namespace App\Imports;

use App\Models\ProjectRkapItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RkapImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected $projectId;
    protected $errors = [];
    protected $rowNumber = 0;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
        // Clear existing RKAP items
        ProjectRkapItem::where('project_id', $projectId)->delete();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->rowNumber++;

            // Normalize column names (support both uppercase and lowercase)
            $data = [
                'no' => $row['no'] ?? $row['No'] ?? null,
                'tahun' => $row['tahun'] ?? $row['TAHUN'] ?? null,
                'kode_anggaran' => $row['kode_anggaran'] ?? $row['KODE ANGGARAN'] ?? null,
                'detail_rencana' => $row['detail_rencana'] ?? $row['DETAIL RENCANA'] ?? null,
                'nilai_rkap' => $row['nilai_rkap'] ?? $row['NILAI RKAP'] ?? null,
            ];

            // Skip empty rows
            if (empty($data['no']) && empty($data['tahun']) && empty($data['kode_anggaran'])) {
                continue;
            }

            // Validate required fields
            $hasError = false;
            $errors = [];

            if (empty($data['no'])) {
                $errors[] = 'Kolom "No" wajib diisi pada baris ' . ($this->rowNumber + 1);
                $hasError = true;
            }
            if (empty($data['tahun'])) {
                $errors[] = 'Kolom "TAHUN" wajib diisi pada baris ' . ($this->rowNumber + 1);
                $hasError = true;
            }
            if (empty($data['kode_anggaran'])) {
                $errors[] = 'Kolom "KODE ANGGARAN" wajib diisi pada baris ' . ($this->rowNumber + 1);
                $hasError = true;
            }
            if (empty($data['detail_rencana'])) {
                $errors[] = 'Kolom "DETAIL RENCANA" wajib diisi pada baris ' . ($this->rowNumber + 1);
                $hasError = true;
            }
            if (empty($data['nilai_rkap']) || !is_numeric($data['nilai_rkap'])) {
                $errors[] = 'Kolom "NILAI RKAP" wajib diisi dengan angka pada baris ' . ($this->rowNumber + 1);
                $hasError = true;
            }

            if ($hasError) {
                $this->errors = array_merge($this->errors, $errors);
                continue;
            }

            ProjectRkapItem::create([
                'project_id' => $this->projectId,
                'no' => $data['no'],
                'tahun' => $data['tahun'],
                'kode_anggaran' => $data['kode_anggaran'],
                'detail_rencana' => $data['detail_rencana'],
                'nilai_rkap' => $data['nilai_rkap'],
                'order' => $this->rowNumber,
            ]);
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}