<?php

namespace App\Livewire\Rkap;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\ProjectRkapItem;
use App\Imports\RkapImport;
use App\Exports\RkapExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class RkapImportExport extends Component
{
    use WithFileUploads;

    public $projectId;
    public $project;
    public $file;
    public $rkapItems = [];
    public $errors = [];
    public $showErrors = false;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ];

    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::with('rkapItems')->findOrFail($project);
        $this->rkapItems = $this->project->rkapItems()->orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.rkap.rkap-import-export');
    }

    public function downloadTemplate()
    {
        return response()->download(Storage::disk('public')->path('templates/rkap_template.xlsx'));
    }

    public function downloadRkap()
    {
        if (!Gate::allows('exportRkap', $this->project)) {
            abort(403, 'Anda tidak memiliki akses download RKAP untuk proyek ini.');
        }

        return Excel::download(new RkapExport($this->projectId), 'RKAP_' . $this->project->code . '.xlsx');
    }

    public function importRkap()
    {
        if (!Gate::allows('importRkap', $this->project)) {
            abort(403, 'Anda tidak memiliki akses import RKAP untuk proyek ini.');
        }

        $this->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ]);

    try {
        $import = new RkapImport($this->projectId);
        Excel::import($import, $this->file);

        if ($import->hasErrors()) {
            $this->errors = $import->getErrors();
            $this->showErrors = true;
            session()->flash('warning', 'Import selesai dengan beberapa error. Silahkan periksa detail error.');
        } else {
            $this->rkapItems = $this->project->rkapItems()->orderBy('order')->get();
            session()->flash('message', 'Data RKAP berhasil diimport!');
        }

        $this->file = null;
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errorMessages = [];
        foreach ($failures as $failure) {
            $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
        session()->flash('error', 'Gagal mengimport data: ' . implode('; ', $errorMessages));
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal mengimport data: ' . $e->getMessage());
    }
    }

    public function deleteRkapItem($id)
    {
        if (!Gate::allows('importRkap', $this->project)) {
            abort(403, 'Anda tidak memiliki akses mengubah data RKAP untuk proyek ini.');
        }

        ProjectRkapItem::findOrFail($id)->delete();
        $this->rkapItems = $this->project->rkapItems()->orderBy('order')->get();
        session()->flash('message', 'Item RKAP berhasil dihapus.');
    }

    public function clearRkap()
    {
        if (!Gate::allows('importRkap', $this->project)) {
            abort(403, 'Anda tidak memiliki akses mengubah data RKAP untuk proyek ini.');
        }

        $this->project->rkapItems()->delete();
        $this->rkapItems = collect();
        session()->flash('message', 'Semua data RKAP berhasil dihapus.');
    }
}