<?php

namespace App\Livewire\Approval;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectApproval;
use App\Enums\ProjectStatusEnum;
use App\Enums\ApprovalActionEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Gate;

class ApprovalReviewPage extends Component
{
    public $projectId;
    public $project;
    public $action;
    public $notes = '';
    public $showConfirmModal = false;
    public $isStaff = false;

    protected $rules = [
        'action' => 'required|in:approve,cancel,revisi',
        'notes' => 'required_if:action,revisi|nullable|string|min:5',
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
            'creator'
        ])->findOrFail($project);

        $user = auth()->user();
        
        // ✅ Cek apakah user adalah Staff (PCCM atau Finance) - bisa review & approve
        $this->isStaff = $user->level === LevelEnum::STAFF->value;

        // Check if user can approve
        if (!Gate::allows('approve', $this->project)) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan approval pada proyek ini.');
        }
    }

    public function render()
    {
        return view('livewire.approval.approval-review-page', [
            'approvals' => $this->project->approvals()
                ->with('approver')
                ->orderBy('created_at', 'asc')
                ->get(),
            'canApprove' => Gate::allows('approve', $this->project),
            'isStaff' => $this->isStaff,
        ]);
    }

    public function confirmAction($action)
    {
        if ($action === 'revisi' && !$this->notes) {
            session()->flash('error', 'Catatan revisi wajib diisi.');
            return;
        }

        $this->action = $action;
        $this->showConfirmModal = true;
    }

    public function processApproval()
    {
        $this->validate();

        $service = new ApprovalService();
        $service->processApproval($this->project, auth()->user(), $this->action, $this->notes);

        $this->showConfirmModal = false;

        if ($this->action === 'revisi') {
            session()->flash('message', 'Proyek dikembalikan untuk revisi ke Pelaksana Staff.');
        } else {
            session()->flash('message', 'Approval berhasil diproses.');
        }
        
        return redirect()->route('dashboard');
    }

    public function closeModal()
    {
        $this->showConfirmModal = false;
        $this->notes = '';
    }

    public function getStatusBadgeClass($status)
    {
        return ProjectStatusEnum::tryFrom($status)?->badgeColor() ?? 'gray';
    }

    public function getActionLabel($action)
    {
        return ApprovalActionEnum::tryFrom($action)?->label() ?? $action;
    }
}