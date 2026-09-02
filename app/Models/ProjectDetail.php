<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    protected $fillable = [
        'project_id',
        'description',
        'scope',
        'risk_issue',
        'deliverables',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}