<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfReleaseService
{
    public function generateSuratRilis(Project $project)
    {
        $data = [
            'project' => $project,
            'logo' => $this->getLogo(),
            'approvals' => $project->approvals()->with('approver')->orderBy('created_at')->get(),
        ];

        $pdf = Pdf::loadView('pdf.surat-rilis.main', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function downloadSuratRilis(Project $project)
    {
        $pdf = $this->generateSuratRilis($project);
        return $pdf->download('Surat_Rilis_' . $project->code . '.pdf');
    }

    public function streamSuratRilis(Project $project)
    {
        $pdf = $this->generateSuratRilis($project);
        return $pdf->stream('Surat_Rilis_' . $project->code . '.pdf');
    }

    private function getLogo(): ?string
    {
        $setting = Setting::where('key', 'logo_path')->first();
        if ($setting && $setting->value) {
            return Storage::url($setting->value);
        }
        return null;
    }
}