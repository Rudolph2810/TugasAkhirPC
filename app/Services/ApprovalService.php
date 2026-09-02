<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectApproval;
use App\Enums\ProjectStatusEnum;
use App\Enums\ApprovalActionEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use App\Events\ProjectStatusChanged;
use App\Policies\ProjectPolicy;
use App\Traits\HasApprovalFlow;

class ApprovalService
{
    use HasApprovalFlow;

    public function processApproval(Project $project, User $approver, string $action, ?string $notes = null): void
    {
        if (!$this->canApprove($project, $approver)) {
            throw new \Exception('User tidak memiliki akses untuk melakukan approval.');
        }

        ProjectApproval::create([
            'project_id' => $project->id,
            'approver_id' => $approver->id,
            'role' => $approver->role,
            'level' => $approver->level,
            'action' => $action,
            'notes' => $notes,
        ]);

        switch ($action) {
            case ApprovalActionEnum::APPROVE->value:
                $this->handleApprove($project);
                break;

            case ApprovalActionEnum::CANCEL->value:
                $this->handleCancel($project, $notes);
                break;

            case ApprovalActionEnum::REVISI->value:
                $this->handleRevisi($project, $notes);
                break;
        }

        event(new ProjectStatusChanged($project));
    }

    private function handleApprove(Project $project): void
    {
        $currentStatus = ProjectStatusEnum::from($project->status);
        $nextStatus = $currentStatus->nextStatus();

        if (!$nextStatus) {
            throw new \Exception('Tahapan approval berikutnya tidak ditemukan untuk status saat ini.');
        }

        if ($nextStatus === ProjectStatusEnum::RILIS) {
            $project->update([
                'status' => ProjectStatusEnum::RILIS->value,
                'released_at' => now(),
                'revisi_notes' => null,
                'current_approver_id' => null,
            ]);
            return;
        }

        $nextApprover = $this->getApproverByStatus($nextStatus);
        if (!$nextApprover) {
            throw new \Exception('Tidak ada approver aktif untuk status "' . $nextStatus->label() . '".');
        }

        $project->update([
            'status' => $nextStatus->value,
            'revisi_notes' => null,
            'current_approver_id' => $nextApprover->id,
        ]);
    }

    private function handleCancel(Project $project, ?string $notes): void
    {
        $project->update([
            'status' => ProjectStatusEnum::DIBATALKAN->value,
            'revisi_notes' => $notes,
            'current_approver_id' => null,
        ]);
    }

    private function handleRevisi(Project $project, ?string $notes): void
    {
        $pelaksanaStaff = $this->getApproverByStatus(ProjectStatusEnum::REVISI);
        if (!$pelaksanaStaff) {
            throw new \Exception('Tidak ada Pelaksana Staff aktif untuk menerima revisi.');
        }

        $project->update([
            'status' => ProjectStatusEnum::REVISI->value,
            'revisi_notes' => $notes,
            'current_approver_id' => $pelaksanaStaff->id,
        ]);
    }

    private function getApproverByStatus(ProjectStatusEnum $status): ?User
    {
        return match ($status) {
            ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL => $this->getApprover(RoleEnum::COMERCIL, LevelEnum::DEPARTMENT_HEAD),
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_COMERCIL => $this->getApprover(RoleEnum::COMERCIL, LevelEnum::DIVISION_HEAD),
            ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA => $this->getApprover(RoleEnum::PELAKSANA, LevelEnum::STAFF),
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA => $this->getApprover(RoleEnum::PELAKSANA, LevelEnum::DEPARTMENT_HEAD),
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PELAKSANA => $this->getApprover(RoleEnum::PELAKSANA, LevelEnum::DIVISION_HEAD),
            ProjectStatusEnum::REVIEW_PCCM => $this->getApprover(RoleEnum::PCCM, LevelEnum::STAFF),
            ProjectStatusEnum::REVIEW_DEPT_HEAD_PCCM => $this->getApprover(RoleEnum::PCCM, LevelEnum::DEPARTMENT_HEAD),
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_PCCM => $this->getApprover(RoleEnum::PCCM, LevelEnum::DIVISION_HEAD),
            ProjectStatusEnum::REVIEW_FINANCE => $this->getApprover(RoleEnum::FINANCE, LevelEnum::STAFF),
            ProjectStatusEnum::REVIEW_DEPT_HEAD_FINANCE => $this->getApprover(RoleEnum::FINANCE, LevelEnum::DEPARTMENT_HEAD),
            ProjectStatusEnum::REVIEW_DIVISION_HEAD_FINANCE => $this->getApprover(RoleEnum::FINANCE, LevelEnum::DIVISION_HEAD),
            ProjectStatusEnum::REVIEW_DIREKSI => $this->getApprover(RoleEnum::DIREKSI),
            ProjectStatusEnum::REVISI => $this->getApprover(RoleEnum::PELAKSANA, LevelEnum::STAFF),
            default => null,
        };
    }

    private function getApprover(RoleEnum $role, ?LevelEnum $level = null): ?User
    {
        $query = User::where('role', $role->value)
            ->where('is_active', true);

        if ($level) {
            $query->where('level', $level->value);
        }

        return $query->first();
    }

    public function canApprove(Project $project, User $user): bool
    {
        return app(ProjectPolicy::class)->approve($user, $project);
    }
}