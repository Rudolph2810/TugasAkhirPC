<?php

namespace Tests\Unit;

use App\Enums\LevelEnum;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Project;
use App\Models\User;
use App\Policies\ProjectPolicy;
use App\Services\ApprovalService;
use Tests\TestCase;

class ApprovalAuthorizationTest extends TestCase
{
    public function test_comercil_dept_head_can_approve_without_current_approver_assignment(): void
    {
        $user = $this->makeUser(RoleEnum::COMERCIL->value, LevelEnum::DEPARTMENT_HEAD->value, 10);
        $project = $this->makeProject(ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL->value, null);

        $policy = new ProjectPolicy();
        $service = new ApprovalService();

        $this->assertTrue($policy->approve($user, $project));
        $this->assertTrue($service->canApprove($project, $user));
    }

    public function test_admin_is_not_normal_approver_for_review_flow(): void
    {
        $user = $this->makeUser(RoleEnum::ADMIN->value, null, 11);
        $project = $this->makeProject(ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL->value, null);

        $policy = new ProjectPolicy();
        $service = new ApprovalService();

        $this->assertFalse($policy->approve($user, $project));
        $this->assertFalse($service->canApprove($project, $user));
    }

    public function test_pelaksana_staff_can_still_fill_when_waiting_for_filling(): void
    {
        $user = $this->makeUser(RoleEnum::PELAKSANA->value, LevelEnum::STAFF->value, 12);
        $project = $this->makeProject(ProjectStatusEnum::MENUNGGU_PENGISIAN_PELAKSANA->value, null);

        $policy = new ProjectPolicy();

        $this->assertTrue($policy->fill($user, $project));
    }

    public function test_direksi_can_approve_on_direksi_stage(): void
    {
        $user = $this->makeUser(RoleEnum::DIREKSI->value, null, 13);
        $project = $this->makeProject(ProjectStatusEnum::REVIEW_DIREKSI->value, null);

        $policy = new ProjectPolicy();
        $service = new ApprovalService();

        $this->assertTrue($policy->approve($user, $project));
        $this->assertTrue($service->canApprove($project, $user));
        $this->assertSame(
            ProjectStatusEnum::RILIS,
            ProjectStatusEnum::REVIEW_DIREKSI->nextStatus()
        );
    }

    public function test_pccm_staff_not_trapped_by_current_approver_id(): void
    {
        $user = $this->makeUser(RoleEnum::PCCM->value, LevelEnum::STAFF->value, 14);
        $project = $this->makeProject(ProjectStatusEnum::REVIEW_PCCM->value, 99);

        $policy = new ProjectPolicy();
        $service = new ApprovalService();

        $this->assertTrue($policy->approve($user, $project));
        $this->assertTrue($service->canApprove($project, $user));
    }

    private function makeUser(string $role, ?string $level, int $id, bool $isActive = true): User
    {
        return new User([
            'id' => $id,
            'role' => $role,
            'level' => $level,
            'is_active' => $isActive,
        ]);
    }

    private function makeProject(string $status, ?int $currentApproverId): Project
    {
        return new Project([
            'status' => $status,
            'current_approver_id' => $currentApproverId,
        ]);
    }
}
