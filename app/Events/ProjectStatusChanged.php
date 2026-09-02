<?php

namespace App\Events;

use App\Models\Project;  // ✅ PASTIKAN ADA
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Project $project;  

    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}