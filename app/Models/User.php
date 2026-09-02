<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role',
        'level',
        'department_id',
        'division_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function approvals()
    {
        return $this->hasMany(ProjectApproval::class, 'approver_id');
    }

    // Accessors - FIXED ✅
    public function getRoleLabelAttribute(): string
    {
        return RoleEnum::tryFrom($this->role)?->label() ?? ($this->role ?? '-');
    }

    public function getLevelLabelAttribute(): string
    {
        return LevelEnum::tryFrom($this->level)?->label() ?? ($this->level ?? '-');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByDivision($query, $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    // Helper methods
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasLevel(string $level): bool
    {
        return $this->level === $level;
    }

    public function isApproverForProject(Project $project): bool
    {
        return $this->id === $project->current_approver_id;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' (' . $this->nip . ')';
    }
}