<?php

namespace App\Listeners;

use App\Events\ProjectStatusChanged;
use App\Models\User;
use App\Models\Project; 
use App\Notifications\ProjectStatusNotification;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendApprovalNotification
{
    
    public function handle(ProjectStatusChanged $event): void
    {
        Log::info('=== NOTIFICATION LISTENER TRIGGERED ===', [
                'project_id' => $event->project->id,
                'project_code' => $event->project->code,
                'status' => $event->project->status,
            ]);
        $project = $event->project;
        $status = ProjectStatusEnum::from($project->status);

        $recipients = $this->getRecipients($project, $status);

        Log::info('=== RECIPIENTS ===', [
            'count' => count($recipients),
            'users' => array_map(fn($u) => $u->nip . ' - ' . $u->name, $recipients),
        ]);

        foreach ($recipients as $recipient) {
            try {
                $recipient->notify(new ProjectStatusNotification($project, $status));
                Log::info('✅ Notification sent to: ' . $recipient->nip);
            } catch (\Exception $e) {
                Log::error('❌ Failed to send: ' . $e->getMessage());
            }

        }
    }

    private function getRecipients(Project $project, ProjectStatusEnum $status): array
    {
        $recipients = [];

        
        switch ($status) {
            case ProjectStatusEnum::DRAFT_INISIASI:
                // Notify Dept Head Comercil
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::COMERCIL, LevelEnum::DEPARTMENT_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL:
                // Notify Division Head Comercil
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::COMERCIL, LevelEnum::DIVISION_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DIVISION_HEAD_COMERCIL:
                // Notify Pelaksana (staff)
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PELAKSANA, LevelEnum::STAFF);
                break;

            case ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA:
                // Notify Pelaksana staff to fill data
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PELAKSANA, LevelEnum::STAFF);
                break;

            case ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA:
                // Notify Division Head Pelaksana
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PELAKSANA, LevelEnum::DIVISION_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DIVISION_HEAD_PELAKSANA:
                // Notify PCCM
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PCCM, LevelEnum::STAFF);
                break;

            case ProjectStatusEnum::REVIEW_PCCM:
                // Notify Dept Head PCCM
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PCCM, LevelEnum::DEPARTMENT_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DEPT_HEAD_PCCM:
                // Notify Division Head PCCM
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PCCM, LevelEnum::DIVISION_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DIVISION_HEAD_PCCM:
                // Notify Finance
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::FINANCE, LevelEnum::STAFF);
                break;

            case ProjectStatusEnum::REVIEW_FINANCE:
                // Notify Dept Head Finance
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::FINANCE, LevelEnum::DEPARTMENT_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DEPT_HEAD_FINANCE:
                // Notify Division Head Finance
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::FINANCE, LevelEnum::DIVISION_HEAD);
                break;

            case ProjectStatusEnum::REVIEW_DIVISION_HEAD_FINANCE:
                // Notify Direksi
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::DIREKSI);
                break;

            case ProjectStatusEnum::REVISI:
                // Notify Pelaksana with revisi notes
                $recipients = $this->getUsersByRoleAndLevel(RoleEnum::PELAKSANA, LevelEnum::STAFF);
                break;

            case ProjectStatusEnum::DIBATALKAN:
                // Notify creator (Comercil)
                if ($project->creator && $project->creator->is_active) {
                    $recipients[] = $project->creator;
                }
                break;

            case ProjectStatusEnum::RILIS:
                // Notify Pelaksana and Comercil
                $recipients = array_merge(
                    $this->getUsersByRoleAndLevel(RoleEnum::PELAKSANA, LevelEnum::STAFF),
                );
                break;

            default:
                // Notify project creator
                if ($project->creator) {
                    $recipients[] = $project->creator;
                }
                break;
        }

        if ($project->current_approver_id && $status->isApprovalStatus()) {
            $approver = User::find($project->current_approver_id);
            if ($approver && $approver->is_active) {
                Log::info('📌 Menggunakan current_approver: ' . $approver->nip);
                return [$approver];
            }
        }

        return array_filter($recipients, fn($u) => $u->is_active);

    }

    private function getUsersByRoleAndLevel(RoleEnum $role, ?LevelEnum $level = null): array
    {
        $query = User::where('role', $role->value)->where('is_active', true);

        if ($level) {
            $query->where('level', $level->value);
        }
        $users = $query->get();

        return $query->get()->all();
    }
}