<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegmentCode extends Model
{
    protected $fillable = ['name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'segment_code', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}