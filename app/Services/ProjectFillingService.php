<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectDetail;
use App\Models\ProjectSchedule;
use App\Models\ProjectBudget;
use App\Models\ProjectBillingPlan;
use App\Models\ProjectMilestone;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use App\Events\ProjectStatusChanged;

class ProjectFillingService
{
    public function saveProjectData(Project $project, array $data): void
    {
        // Save or create project detail
        $detail = $project->detail;
        if ($detail) {
            $detail->update([
                'description' => $data['description'],
                'scope' => $data['scope'],
                'risk_issue' => $data['riskIssue'],
                'deliverables' => $data['deliverables'],
            ]);
        } else {
            ProjectDetail::create([
                'project_id' => $project->id,
                'description' => $data['description'],
                'scope' => $data['scope'],
                'risk_issue' => $data['riskIssue'],
                'deliverables' => $data['deliverables'],
            ]);
        }

        // Save schedules
        $project->schedules()->delete();
        foreach ($data['schedules'] as $schedule) {
            ProjectSchedule::create([
                'project_id' => $project->id,
                'phase' => $schedule['phase'],
                'start_date' => $schedule['start_date'],
                'end_date' => $schedule['end_date'],
                'order' => $schedule['order'],
            ]);
        }

        // Save budgets
        $project->budgets()->delete();
        foreach ($data['budgets'] as $budget) {
            ProjectBudget::create([
                'project_id' => $project->id,
                'item' => $budget['item'],
                'amount' => $budget['amount'],
                'description' => $budget['description'] ?? null,
                'order' => $budget['order'],
            ]);
        }

        // Save billing plans
        $project->billingPlans()->delete();
        foreach ($data['billingPlans'] as $billing) {
            ProjectBillingPlan::create([
                'project_id' => $project->id,
                'termin' => $billing['termin'],
                'percentage' => $billing['percentage'],
                'planned_date' => $billing['planned_date'],
                'amount' => $billing['amount'],
                'order' => $billing['order'],
            ]);
        }

        // Save milestones
        $project->milestones()->delete();
        foreach ($data['milestones'] as $milestone) {
            ProjectMilestone::create([
                'project_id' => $project->id,
                'name' => $milestone['name'],
                'target_date' => $milestone['target_date'],
                'status' => $milestone['status'],
                'order' => $milestone['order'],
            ]);
        }
    }

    /**
     * Submit project for review - Lanjut ke Dept Head Pelaksana
     */
    public function submitForReview(Project $project): void
    {
        $nextApprover = $this->getApproverForRole(RoleEnum::PELAKSANA, LevelEnum::DEPARTMENT_HEAD);
        if (!$nextApprover) {
            throw new \Exception('Tidak ada Pelaksana Department Head aktif untuk proses review.');
        }

        // Update status to review dept head pelaksana
        $project->update([
            'status' => ProjectStatusEnum::REVIEW_DEPT_HEAD_PELAKSANA->value,
            'revisi_notes' => null,
            'current_approver_id' => $nextApprover->id,
        ]);

        // Trigger notification
        $oldStatus = $project->status;
    // update status...
    event(new ProjectStatusChanged($project, $oldStatus, $project->status));

    }

    /**
     * Get approver for specific role and level
     */
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