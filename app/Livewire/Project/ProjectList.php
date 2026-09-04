<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Enums\ProjectStatusEnum;
use Illuminate\Support\Facades\Auth;

class ProjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $yearFilter = '';
    public $clientFilter = '';
    public $managerFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'yearFilter' => ['except' => ''],
        'clientFilter' => ['except' => ''],
        'managerFilter' => ['except' => '']
    ];

    public function render()
    {
        // ✅ AMBIL SEMUA PROYEK (termasuk yang baru dibuat)
        $projects = Project::with(['creator', 'currentApprover', 'businessSegment'])
            ->when($this->search, function ($query) {
                $query->where('code', 'like', "%{$this->search}%")
                    ->orWhere('title', 'like', "%{$this->search}%")
                    ->orWhere('client', 'like', "%{$this->search}%")
                    ->orWhere('nama_manager', 'like', "%{$this->search}%");
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->yearFilter, function ($query) {
                $query->whereYear('created_at', $this->yearFilter);
            })
            ->when($this->clientFilter, function ($query) {
                $query->where('client', 'like', "%{$this->clientFilter}%");
            })
            ->when($this->managerFilter, function ($query) {
                $query->where('nama_manager', 'like', "%{$this->managerFilter}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // ✅ DEBUG - Cek apakah ada data
        // dd($projects->count());

        $statuses = ProjectStatusEnum::cases();
        $years = Project::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('livewire.project.project-list', [
            'projects' => $projects,
            'statuses' => $statuses,
            'years' => $years,
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->yearFilter = '';
        $this->clientFilter = '';
    }

    public function getStatusBadgeClass($status)
    {
        return ProjectStatusEnum::tryFrom($status)?->badgeColor() ?? 'gray';
    }
}