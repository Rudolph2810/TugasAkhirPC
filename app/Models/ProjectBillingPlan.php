<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBillingPlan extends Model
{
    protected $fillable = [
        'project_id',
        'termin',
        'percentage',
        'planned_date',
        'amount',
        'order',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'planned_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}