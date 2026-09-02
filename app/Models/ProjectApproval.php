<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ApprovalActionEnum;

class ProjectApproval extends Model
{
    protected $fillable = [
        'project_id',
        'approver_id',
        'role',
        'level',
        'action',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getRoleLabelAttribute()
    {
        return \App\Enums\RoleEnum::tryFrom($this->role)?->label() ?? $this->role;
    }

    public function getActionLabelAttribute()
    {
        return ApprovalActionEnum::tryFrom($this->action)?->label() ?? $this->action;
    }
}