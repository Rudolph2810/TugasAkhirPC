<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRkapItem extends Model
{
    protected $fillable = [
        'project_id',
        'no',
        'tahun',
        'kode_anggaran',
        'detail_rencana',
        'nilai_rkap',
        'order',
    ];

    protected $casts = [
        'nilai_rkap' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}