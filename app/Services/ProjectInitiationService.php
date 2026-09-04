<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectApproval;
use App\Enums\ProjectStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use App\Enums\ApprovalActionEnum;
use App\Events\ProjectStatusChanged;

class ProjectInitiationService
{
    public function initiateProject(array $data): Project
    {
        // 1. Buat proyek
        $project = Project::create([
            'code' => $data['projectCode'],
            'jenis_proyek' => $data['jenisProyek'] ?? null,
            'kode_segmen' => $data['kodeSegmen'] ?? null,

            'title' => $data['title'],
            'client' => $data['client'],
            'nama_manager' => $data['nama_manager'],
            'business_segment_id' => $data['businessSegmentId'],
            'location' => $data['location'],
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'contract_value' => $data['contractValue'],
            'status' => ProjectStatusEnum::DRAFT_INISIASI->value,
            'created_by' => auth()->id(),
        ]);

        // 2. Simpan attachment dengan file_path
        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attachmentData) {
                if (isset($attachmentData['file']) && $attachmentData['file']) {
                    // ✅ Simpan file ke storage
                    $path = $attachmentData['file']->store('project-attachments', 'public');
                    
                    // ✅ Simpan ke database dengan file_path
                    $project->attachments()->create([
                        'document_type' => $attachmentData['type'] ?? 'Dokumen',
                        'document_number' => $attachmentData['number'] ?? '',
                        'document_date' => $attachmentData['date'] ?? null,
                        'description' => $attachmentData['description'] ?? '',
                        'file_path' => $path,  // ✅ WAJIB DIISI
                        'original_filename' => $attachmentData['file']->getClientOriginalName(),
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }
        }

        // 3. Auto approve
        $this->autoApprove($project);

        // 4. Trigger notification
        event(new ProjectStatusChanged($project));

        return $project;
    }

    /**
     * Auto approve by Comercil Staff
     */
    public function autoApprove(Project $project): void
    {
        $user = auth()->user();
        if ($user->role !== RoleEnum::COMERCIL->value || 
            $user->level !== LevelEnum::STAFF->value) {
            return;
        }

        // Buat record approval
        ProjectApproval::create([
            'project_id' => $project->id,
            'approver_id' => $user->id,
            'role' => $user->role,
            'level' => $user->level,
            'action' => ApprovalActionEnum::APPROVE->value,
            'notes' => 'Inisiasi proyek oleh Comercil Staff',
        ]);

        // Set next approver (Dept Head Comercil)
        $nextApprover = User::where('role', RoleEnum::COMERCIL->value)
            ->where('level', LevelEnum::DEPARTMENT_HEAD->value)
            ->where('is_active', true)
            ->first();

        if (!$nextApprover) {
            throw new \Exception('Tidak ada Comercil Department Head aktif untuk proses approval.');
        }

        $project->update([
            'status' => ProjectStatusEnum::REVIEW_DEPT_HEAD_COMERCIL->value,
            'current_approver_id' => $nextApprover->id,
        ]);
        $oldStatus = $project->status;
    // update status...
    event(new ProjectStatusChanged($project, $oldStatus, $project->status));

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