<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectApproval;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $recentProjects = [];
    public $pendingApprovals = [];

    public function mount()
    {
        $this->loadStats();
        $this->recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $this->recentProjects = Project::orderBy('created_at', 'desc')->limit(5)->get();
        $this->pendingApprovals = Project::where('status', 'like', 'review%')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_users' => User::where('role', 'pending')->count(),
            'total_projects' => Project::count(),
            'projects_rilis' => Project::where('status', 'rilis')->count(),
            'projects_review' => Project::where('status', 'like', 'review%')->count(),
            'projects_cancelled' => Project::where('status', 'dibatalkan')->count(),
            'approvals_today' => ProjectApproval::whereDate('created_at', Carbon::today())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.app');
    }
}