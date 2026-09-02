<?php

namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Exports\RkapExport;
use App\Services\PdfReleaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;
use App\Traits\HasApprovalFlow;;

class DetailPage extends Component
{
    use WithFileUploads, HasApprovalFlow; 
    
    public $projectId;
    public $project;
    public $showAttachmentModal = false;
    public $attachmentFile;
    public $attachmentType;
    public $attachmentNumber;
    public $attachmentDate;
    public $attachmentDescription;
    public $editingAttachmentId = null;

    protected $rules = [
        'attachmentFile' => 'nullable|file|mimes:pdf|max:5120',
        'attachmentType' => 'required|string|max:255',
        'attachmentNumber' => 'nullable|string|max:255',
        'attachmentDate' => 'nullable|date',
        'attachmentDescription' => 'nullable|string',
    ];

    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::with([
            'detail',
            'schedules',
            'budgets',
            'billingPlans',
            'milestones',
            'rkapItems',
            'attachments',
            'approvals.approver',
            'creator',
            'businessSegment',
            'currentApprover'
        ])->findOrFail($project);

        // Check if user can view this project
        if (!Gate::allows('view', $this->project)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat proyek ini.');
        }
    }

    public function render()
    {
        $canApprove = Gate::allows('approve', $this->project);

        return view('livewire.project.detail-page', [
            'canInitiate' => Gate::allows('initiate'),
            'canFill' => Gate::allows('fill', $this->project),
            'canApprove' => Gate::allows('approve', $this->project),
            'canExportRkap' => Gate::allows('exportRkap', $this->project),
            'canImportRkap' => Gate::allows('importRkap', $this->project),
        ]);
    }
    public function approve()
{
    $project = Project::findOrFail($this->projectId);

    // Cek apakah user berhak melakukan approval
    $this->authorize('approve', $project);

    // Proses approval menggunakan workflow
    $this->processApproval(
        'approve',
        auth()->user()
    );

    // Refresh data project
    $this->project = Project::with([
        'detail',
        'schedules',
        'budgets',
        'billingPlans',
        'milestones',
        'rkapItems',
        'attachments',
        'approvals.approver',
        'creator',
        'businessSegment',
        'currentApprover'
    ])->findOrFail($this->projectId);

    session()->flash('message', 'Project berhasil di-approve.');
}

    // Attachment Methods
    public function openAttachmentModal()
    {
        $this->resetAttachmentForm();
        $this->showAttachmentModal = true;
    }

    public function editAttachment($id)
    {
        $attachment = ProjectAttachment::findOrFail($id);
        $this->editingAttachmentId = $id;
        $this->attachmentType = $attachment->document_type;
        $this->attachmentNumber = $attachment->document_number;
        $this->attachmentDate = $attachment->document_date?->format('Y-m-d');
        $this->attachmentDescription = $attachment->description;
        $this->attachmentFile = null;
        $this->showAttachmentModal = true;
    }

    public function saveAttachment()
{
    $this->validate();

    $data = [
        'document_type' => $this->attachmentType,
        'document_number' => $this->attachmentNumber,
        'document_date' => $this->attachmentDate,
        'description' => $this->attachmentDescription,
    ];

    // Jika ada file baru (create atau update dengan file baru)
    if ($this->attachmentFile) {
        $path = $this->attachmentFile->store('project-attachments', 'public');
        $data['file_path'] = $path;
        $data['original_filename'] = $this->attachmentFile->getClientOriginalName();
        $data['uploaded_by'] = auth()->id();
        $data['uploaded_at'] = now();
    } else {
        // Jika edit dan tidak ada file baru, kita harus mempertahankan file lama
        if ($this->editingAttachmentId) {
            // Ambil attachment lama untuk mengambil file_path
            $oldAttachment = ProjectAttachment::find($this->editingAttachmentId);
            if ($oldAttachment) {
                $data['file_path'] = $oldAttachment->file_path;
                $data['original_filename'] = $oldAttachment->original_filename;
                $data['uploaded_by'] = $oldAttachment->uploaded_by;
                $data['uploaded_at'] = $oldAttachment->uploaded_at;
            }
        } else {
            // Jika create baru tanpa file, throw error
            throw new \Exception('File wajib diupload.');
        }
    }

    if ($this->editingAttachmentId) {
        $attachment = ProjectAttachment::findOrFail($this->editingAttachmentId);
        $attachment->update($data);
        session()->flash('message', 'Lampiran berhasil diupdate.');
    } else {
        $data['project_id'] = $this->project->id;
        ProjectAttachment::create($data);
        session()->flash('message', 'Lampiran berhasil ditambahkan.');
    }

    $this->showAttachmentModal = false;
    $this->resetAttachmentForm();
    $this->project = $this->project->fresh(['attachments']);
}

    public function deleteAttachment($id)
    {
        $attachment = ProjectAttachment::findOrFail($id);
        
        // Delete file from storage
        if ($attachment->file_path) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $attachment->delete();
        session()->flash('message', 'Lampiran berhasil dihapus.');
        $this->project = $this->project->fresh(['attachments']);
    }

    public function downloadAttachment($id)
    {
        $attachment = ProjectAttachment::findOrFail($id);
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download($attachment->file_path, $attachment->original_filename);
        }
        session()->flash('error', 'File tidak ditemukan.');
    }

    public function previewAttachment($id)
    {
        $attachment = ProjectAttachment::findOrFail($id);
        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            return response()->file(Storage::disk('public')->path($attachment->file_path));
        }
        session()->flash('error', 'File tidak ditemukan.');
    }

    private function resetAttachmentForm()
    {
        $this->editingAttachmentId = null;
        $this->attachmentFile = null;
        $this->attachmentType = '';
        $this->attachmentNumber = '';
        $this->attachmentDate = '';
        $this->attachmentDescription = '';
    }

    public function closeAttachmentModal()
    {
        $this->showAttachmentModal = false;
        $this->resetAttachmentForm();
    }

    // Download Surat Rilis
    public function downloadSuratRilis()
    {
        if ($this->project->status !== 'rilis') {
            session()->flash('error', 'Project Charter belum rilis.');
            return;
        }

        $service = new PdfReleaseService();
        return $service->downloadSuratRilis($this->project);
    }

    public function downloadRkap()
    {
        if (!Gate::allows('exportRkap', $this->project)) {
            abort(403, 'Anda tidak memiliki akses download RKAP untuk proyek ini.');
        }

        return Excel::download(new RkapExport($this->projectId), 'RKAP_' . $this->project->code . '.xlsx');
    }

    // Get Status Badge
    public function getStatusBadgeClass($status)
    {
        return \App\Enums\ProjectStatusEnum::tryFrom($status)?->badgeColor() ?? 'gray';
    }
}  