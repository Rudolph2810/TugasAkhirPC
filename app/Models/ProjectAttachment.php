<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    protected $fillable = [
        'project_id',
        'document_type',
        'document_number',
        'document_date',
        'description',
        'file_path',        // ✅ PASTIKAN ADA
        'original_filename',
        'uploaded_at',
        'uploaded_by',
    ];

    protected $casts = [
        'document_date' => 'date',
        'uploaded_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}