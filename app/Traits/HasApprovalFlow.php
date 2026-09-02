<?php

namespace App\Traits;

use App\Models\ProjectApproval;
use App\Models\User;
use App\Enums\ProjectStatusEnum;
use App\Enums\ApprovalActionEnum;
use App\Enums\LevelEnum;


trait HasApprovalFlow
{
    protected function processApproval(string $action, User $approver, ?string $notes = null): void
    {
        $project = $this;

        // Create approval record
        ProjectApproval::create([
            'project_id' => $project->id,
            'approver_id' => $approver->id,
            'role' => $approver->role,
            'level' => $approver->level,
            'action' => $action,
            'notes' => $notes,
        ]);

        // Process based on action
        switch ($action) {
            case ApprovalActionEnum::APPROVE->value:
                $this->handleApprove();
                break;

            case ApprovalActionEnum::CANCEL->value:
                $this->handleCancel($notes);
                break;

            case ApprovalActionEnum::REVISI->value:
                $this->handleRevisi($notes);
                break;
        }
    }

    protected function handleApprove(): void
    {
        $currentStatus = ProjectStatusEnum::from($this->status);
        $nextStatus = $currentStatus->nextStatus();

        if ($nextStatus && $nextStatus !== ProjectStatusEnum::RILIS) {
            $this->update([
                'status' => $nextStatus->value,
                'revisi_notes' => null,
            ]);

            // Set next approver
            $nextApprover = $this->getNextApprover();
            if ($nextApprover) {
                $this->update(['current_approver_id' => $nextApprover->id]);
            }
        } else {
            // Project released
            $this->update([
                'status' => ProjectStatusEnum::RILIS->value,
                'released_at' => now(),
                'revisi_notes' => null,
                'current_approver_id' => null,
            ]);
        }

        // Trigger notification event
        event(new \App\Events\ProjectStatusChanged($this));
    }

    protected function handleCancel(?string $notes): void
    {
        $this->update([
            'status' => ProjectStatusEnum::DIBATALKAN->value,
            'revisi_notes' => $notes,
            'current_approver_id' => null,
        ]);

        event(new \App\Events\ProjectStatusChanged($this));
    }

    protected function handleRevisi(?string $notes): void
    {
        $this->update([
            'status' => ProjectStatusEnum::REVISI->value,
            'revisi_notes' => $notes,
            'current_approver_id' => null,
        ]);

        event(new \App\Events\ProjectStatusChanged($this));
    }
}