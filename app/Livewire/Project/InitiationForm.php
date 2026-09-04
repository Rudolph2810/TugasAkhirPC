<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\BusinessSegment;
use App\Services\ProjectInitiationService;
use App\Enums\ProjectStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\ProjectType;
use App\Models\SegmentCode;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;


class InitiationForm extends Component
{
    use WithFileUploads;

    // Project Data
    public $projectCode;
    public $title;
    public $client;
    public $nama_manager;
    public $businessSegmentId;
    public $location;
    public $startDate;
    public $endDate;
    public $contractValue;
    public $projectType;
    public $segmentCode;

    // Attachments
    public $attachments = [];
    public $attachmentTypes = [];
    public $attachmentNumbers = [];
    public $attachmentDates = [];
    public $attachmentDescriptions = [];

    // Status
    public $isSubmitting = false;
    public $showSuccessModal = false;
    public $createdProject = null;
    public $autoApproved = false;

    public $jenisProyek = [
    'Internal' => 'I',
    'Eksternal' => 'E',
    // tambahkan sesuai kebutuhan
    ];
    public $kodeSegmen = [
    'DESIGN & BUILD' => 'DB',
    'ASSET MANAGEMENT' => 'AM',
    'TRANSPORTASI' => 'TR',
    'BUILDING MANAGEMENT' => 'BD',
    'EXECUTIVE LOUNGE' => 'EL',
    // tambahkan sesuai kebutuhan
    ];


    protected $rules = [
        'projectCode' => 'required|string|unique:projects,code|max:50',
        'title' => 'required|string|max:255',
        'nama_manager' => 'nullable|string|max:255',
        'client' => 'required|string|max:255',
        'businessSegmentId' => 'required|exists:business_segments,id',
        'location' => 'required|string|max:500',
        'startDate' => 'required|date|before_or_equal:endDate',
        'endDate' => 'required|date|after_or_equal:startDate',
        'contractValue' => 'required|numeric|min:0|max:999999999999.99',
        'attachments.*' => 'nullable|file|mimes:pdf|max:5120',
        'attachmentTypes.*' => 'nullable|string|max:255',
        'attachmentNumbers.*' => 'nullable|string|max:255',
        'attachmentDates.*' => 'nullable|date',
        'attachmentDescriptions.*' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'projectCode.required' => 'Kode proyek wajib diisi.',
        'projectCode.unique' => 'Kode proyek sudah digunakan.',
        'projectCode.max' => 'Kode proyek maksimal 50 karakter.',
        'title.required' => 'Judul pekerjaan wajib diisi.',
        'client.required' => 'Nama client wajib diisi.',
        'businessSegmentId.required' => 'Segmen bisnis wajib dipilih.',
        'businessSegmentId.exists' => 'Segmen bisnis yang dipilih tidak valid.',
        'location.required' => 'Lokasi pekerjaan wajib diisi.',
        'startDate.required' => 'Tanggal mulai wajib diisi.',
        'startDate.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
        'endDate.required' => 'Tanggal selesai wajib diisi.',
        'endDate.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        'contractValue.required' => 'Nilai kontrak wajib diisi.',
        'contractValue.min' => 'Nilai kontrak tidak boleh negatif.',
        'contractValue.max' => 'Nilai kontrak terlalu besar.',
        'attachments.*.mimes' => 'File lampiran harus berformat PDF.',
        'attachments.*.max' => 'Ukuran file lampiran maksimal 5MB.',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // ✅ HANYA Comercil Staff yang bisa inisiasi
        if ($user->role !== RoleEnum::COMERCIL->value || $user->level !== LevelEnum::STAFF->value) {
            abort(403, 'Hanya Comercil Staff yang dapat menginisiasi proyek.');
        }

        if (!$user->is_active) {
            abort(403, 'Akun Anda belum diaktifkan.');
        }

        $this->generateProjectCode();
    }

    public function generateProjectCode()
    {
        $typeCode = $this->projectType ? ProjectType::find($this->projectType)?->code : 'XX';
    $segCode = $this->segmentCode ? SegmentCode::find($this->segmentCode)?->code : 'XX';
    $date = now()->format('dmY');
    
    $baseCode = $typeCode . '-' . $segCode . '-' . $date;
    $counter = 1;
    $newCode = $baseCode;
    
    while (Project::where('code', $newCode)->exists()) {
        $newCode = $baseCode . '-' . str_pad($counter, 2, '0', STR_PAD_LEFT);
        $counter++;
    }
    
    $this->projectCode = $newCode;
    }

    public function render()
    {
        return view('livewire.project.initiation-form', [
        'businessSegments' => BusinessSegment::where('is_active', true)->orderBy('name')->get(),
        'projectTypes' => ProjectType::where('is_active', true)->orderBy('name')->get(),
        'segmentCodes' => SegmentCode::where('is_active', true)->orderBy('name')->get()   
        ])->layout('layouts.app');
    }

    // ============ ATTACHMENT METHODS ============
    public function addAttachment()
    {
        $this->attachments[] = null;
        $this->attachmentTypes[] = '';
        $this->attachmentNumbers[] = '';
        $this->attachmentDates[] = '';
        $this->attachmentDescriptions[] = '';
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index]) && $this->attachments[$index]) {
            $this->attachments[$index]->delete();
        }
        
        unset($this->attachments[$index]);
        unset($this->attachmentTypes[$index]);
        unset($this->attachmentNumbers[$index]);
        unset($this->attachmentDates[$index]);
        unset($this->attachmentDescriptions[$index]);
        
        $this->attachments = array_values($this->attachments);
        $this->attachmentTypes = array_values($this->attachmentTypes);
        $this->attachmentNumbers = array_values($this->attachmentNumbers);
        $this->attachmentDates = array_values($this->attachmentDates);
        $this->attachmentDescriptions = array_values($this->attachmentDescriptions);
    }

    // ============ SAVE ============
    public function save()
    {
        $this->isSubmitting = true;
    
    try {
        $this->validate();

        // 1. Buat proyek dulu
        $project = Project::create([
            'code' => $this->projectCode,
            'jenis_proyek' => $this->projectType ?? null,
            'kode_segmen' => $this->segmentCode ?? null,
            'title' => $this->title,
            'client' => $this->client,
            'nama_manager' => $this->nama_manager,
            'business_segment_id' => $this->businessSegmentId,
            'location' => $this->location,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'contract_value' => $this->contractValue,
            'status' => ProjectStatusEnum::DRAFT_INISIASI->value,
            'created_by' => auth()->id(),
        ]);

        // 2. Simpan lampiran
        foreach ($this->attachments as $index => $file) {
            if ($file) {
                // ✅ Simpan file ke storage
                $path = $file->store('project-attachments', 'public');
                
                // ✅ Simpan data ke database dengan file_path
                $project->attachments()->create([
                    'document_type' => $this->attachmentTypes[$index] ?? 'Dokumen',
                    'document_number' => $this->attachmentNumbers[$index] ?? '',
                    'document_date' => $this->attachmentDates[$index] ?? null,
                    'description' => $this->attachmentDescriptions[$index] ?? '',
                    'file_path' => $path,  // ✅ WAJIB DIISI
                    'original_filename' => $file->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        // 3. Auto approve
        $service = new ProjectInitiationService();
        $service->autoApprove($project);

        $this->createdProject = $project;
        $this->autoApproved = true;
        $this->showSuccessModal = true;

        session()->flash('success', 'Proyek berhasil diinisiasi dan otomatis masuk ke approval!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->isSubmitting = false;
        throw $e;
    } catch (\Exception $e) {
        $this->isSubmitting = false;
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    private function getAttachmentsData(): array
    {
        $attachmentsData = [];
        foreach ($this->attachments as $index => $file) {
            if ($file) {
                $attachmentsData[] = [
                    'file' => $file,
                    'type' => $this->attachmentTypes[$index] ?? 'Dokumen',
                    'number' => $this->attachmentNumbers[$index] ?? '',
                    'date' => $this->attachmentDates[$index] ?? null,
                    'description' => $this->attachmentDescriptions[$index] ?? '',
                ];
            }
        }
        return $attachmentsData;
    }

    // ============ HELPER METHODS ============
    public function resetForm()
    {
        $this->reset([
            'title',
            'client',
            'nama_manager',
            'businessSegmentId',
            'location',
            'startDate',
            'endDate',
            'contractValue',
            'attachments',
            'attachmentTypes',
            'attachmentNumbers',
            'attachmentDates',
            'attachmentDescriptions',
        ]);
        $this->generateProjectCode();
        $this->isSubmitting = false;
        $this->showSuccessModal = false;
        $this->createdProject = null;
        $this->autoApproved = false;
        $this->nama_manager = '';
    }

    public function goToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function goToDetail()
    {
        if ($this->createdProject) {
            return redirect()->route('project.detail', $this->createdProject->id);
        }
        return redirect()->route('dashboard');
    }
    public function updatedJenisProyek()
{
    $this->generateProjectCode();
}

public function updatedKodeSegmen()
{
    $this->generateProjectCode();
}
}