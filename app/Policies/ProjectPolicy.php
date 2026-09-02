<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;

class ProjectPolicy
{
    /**
     * View any projects
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * View project detail
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Initiate project - Comercil Staff
     */
    public function initiate(User $user): bool
    {
        return $user->role === RoleEnum::COMERCIL->value
            && $user->level === LevelEnum::STAFF->value;
    }

    /**
     * Fill project data - Pelaksana Staff
     */
    public function fill(User $user, Project $project): bool
    {
        return $user->role === RoleEnum::PELAKSANA->value
            && $user->level === LevelEnum::STAFF->value
            && in_array($project->status, [
                ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA->value,
                ProjectStatusEnum::REVISI->value,
            ]);
    }

    /**
     * Approve project
     */
    public function approve(User $user, Project $project): bool
    {
        if (!$user->is_active || $user->role === RoleEnum::ADMIN->value) {
            return false;
        }

        $status = ProjectStatusEnum::tryFrom($project->status);
        if (!$status || !$status->isApprovalStatus()) {
            return false;
        }

        $approverInfo = $status->getApproverInfo();
        if (!$approverInfo || $user->role !== $approverInfo['role']) {
            return false;
        }

        if (!empty($approverInfo['level']) && $user->level !== $approverInfo['level']) {
            return false;
        }

        return true;
    }

    /**
     * Export RKAP - All users
     */
    public function exportRkap(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Import RKAP - Pelaksana Staff
     */
    public function importRkap(User $user, Project $project): bool
    {
        return $user->role === RoleEnum::PELAKSANA->value
            && $user->level === LevelEnum::STAFF->value;
    }
}