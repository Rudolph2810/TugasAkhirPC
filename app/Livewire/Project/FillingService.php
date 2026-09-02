<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use App\Events\ProjectStatusChanged;

class ProjectFillingService
{
    public function submitForReview(Project $project): void
    {
        // Update status to review dept head pelaksana
        $project->update([
            'status' => ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA->value,
            'revisi_notes' => null,
        ]);

        // Set next approver (Dept Head Pelaksana)
        $nextApprover = $this->getApproverForRole(RoleEnum::PELAKSANA, LevelEnum::DEPARTMENT_HEAD);
        if ($nextApprover) {
            $project->update(['current_approver_id' => $nextApprover->id]);
        }

        // Trigger notification
        event(new ProjectStatusChanged($project));
    }

    public function getApproverForRole(RoleEnum $role, ?LevelEnum $level = null): ?User
    {
        $query = User::where('role', $role->value)
            ->where('is_active', true);

        if ($level) {
            $query->where('level', $level->value);
        }

        return $query->first();
    }
}