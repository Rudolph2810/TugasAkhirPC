<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ProjectStatusEnum;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'client',
        'business_segment_id',
        'location',
        'start_date',
        'end_date',
        'contract_value',
        'status',
        'created_by',
        'current_approver_id',
        'revisi_notes',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contract_value' => 'decimal:2',
            'released_at' => 'datetime',
        ];
    }

    // Relationships
    public function businessSegment()
    {
        return $this->belongsTo(BusinessSegment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function currentApprover()
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }

    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function detail()
    {
        return $this->hasOne(ProjectDetail::class);
    }

    public function schedules()
    {
        return $this->hasMany(ProjectSchedule::class);
    }

    public function budgets()
    {
        return $this->hasMany(ProjectBudget::class);
    }

    public function billingPlans()
    {
        return $this->hasMany(ProjectBillingPlan::class);
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function rkapItems()
    {
        return $this->hasMany(ProjectRkapItem::class);
    }

    public function approvals()
    {
        return $this->hasMany(ProjectApproval::class);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return ProjectStatusEnum::tryFrom($this->status)?->label() ?? $this->status;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return ProjectStatusEnum::tryFrom($this->status)?->badgeColor() ?? 'gray';
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('code', 'LIKE', "%{$search}%")
            ->orWhere('title', 'LIKE', "%{$search}%")
            ->orWhere('client', 'LIKE', "%{$search}%");
    }

    // Helper methods
    public function getApprovalSteps(): array
    {
        return [
            'review_dept_head_comercil' => [
                'role' => 'comercil',
                'level' => 'department_head',
                'label' => 'Department Head Comercil'
            ],
            'review_division_head_comercil' => [
                'role' => 'comercil',
                'level' => 'division_head',
                'label' => 'Division Head Comercil'
            ],
            'review_dept_head_pelaksana' => [
                'role' => 'pelaksana',
                'level' => 'department_head',
                'label' => 'Department Head Pelaksana'
            ],
            'review_division_head_pelaksana' => [
                'role' => 'pelaksana',
                'level' => 'division_head',
                'label' => 'Division Head Pelaksana'
            ],
            'review_pccm' => [
                'role' => 'pccm',
                'level' => 'staff',
                'label' => 'PCCM'
            ],
            'review_dept_head_pccm' => [
                'role' => 'pccm',
                'level' => 'department_head',
                'label' => 'Department Head PCCM'
            ],
            'review_division_head_pccm' => [
                'role' => 'pccm',
                'level' => 'division_head',
                'label' => 'Division Head PCCM'
            ],
            'review_finance' => [
                'role' => 'finance',
                'level' => 'staff',
                'label' => 'Finance'
            ],
            'review_dept_head_finance' => [
                'role' => 'finance',
                'level' => 'department_head',
                'label' => 'Department Head Finance'
            ],
            'review_division_head_finance' => [
                'role' => 'finance',
                'level' => 'division_head',
                'label' => 'Division Head Finance'
            ],
            'review_direksi' => [
                'role' => 'direksi',
                'level' => null,
                'label' => 'Direksi'
            ],
        ];
    }

    public function getNextApprover(): ?User
    {
        $steps = $this->getApprovalSteps();
        $currentStatus = $this->status;

        // If status is 'menunggu_pengisian_pelaksana', approver is the pelaksana
        if ($currentStatus === 'menunggu_pengisian_pelaksana') {
            return User::byRole('pelaksana')->active()->first();
        }

        if (!isset($steps[$currentStatus])) {
            return null;
        }

        $step = $steps[$currentStatus];
        $query = User::byRole($step['role'])->active();

        if ($step['level']) {
            $query->where('level', $step['level']);
        }

        return $query->first();
    }

    public function isComplete(): bool
    {
        return $this->status === 'rilis' || $this->status === 'dibatalkan';
    }
}