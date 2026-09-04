<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Project $project;
    public $oldStatus;
    public $newStatus;

    public function __construct(Project $project, $oldStatus = null, $newStatus = null)
    {
        $this->project = $project;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}