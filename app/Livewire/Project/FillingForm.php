<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\ProjectSchedule;
use App\Models\ProjectBudget;
use App\Models\ProjectBillingPlan;
use App\Models\ProjectMilestone;
use App\Enums\ProjectStatusEnum;
use App\Services\ProjectFillingService;
use Illuminate\Support\Facades\Gate;

class FillingForm extends Component
{
    use WithFileUploads;
    public $projectId;
    public $project;

    // Project Detail
    public $description;
    public $scope;
    public $riskIssue;
    public $deliverables;

    // Schedules
    public $schedules = [];
    public $schedulePhases = [];
    public $scheduleStartDates = [];
    public $scheduleEndDates = [];

    // Budgets
    public $budgets = [];
    public $budgetItems = [];
    public $budgetAmounts = [];
    public $budgetDescriptions = [];

    // Billing Plans
    public $billingPlans = [];
    public $billingTermins = [];
    public $billingPercentages = [];
    public $billingDates = [];
    public $billingAmounts = [];

    // Milestones
    public $milestones = [];
    public $milestoneNames = [];
    public $milestoneDates = [];
    public $milestoneStatuses = [];

    // UI State
    public $activeTab = 'detail';
    public $isSubmitting = false;
    public $showSuccessModal = false;

    protected $rules = [
        'description' => 'required|string|min:10|max:5000',
        'scope' => 'required|string|min:10|max:5000',
        'riskIssue' => 'nullable|string|max:5000',
        'deliverables' => 'nullable|string|max:5000',
        'schedulePhases.*' => 'nullable|string|max:255',
        'scheduleStartDates.*' => 'nullable|date',
        'scheduleEndDates.*' => 'nullable|date|after_or_equal:scheduleStartDates.*',
        'budgetItems.*' => 'nullable|string|max:255',
        'budgetAmounts.*' => 'nullable|numeric|min:0',
        'budgetDescriptions.*' => 'nullable|string|max:500',
        'billingTermins.*' => 'nullable|string|max:255',
        'billingPercentages.*' => 'nullable|numeric|min:0|max:100',
        'billingDates.*' => 'nullable|date',
        'billingAmounts.*' => 'nullable|numeric|min:0',
        'milestoneNames.*' => 'nullable|string|max:255',
        'milestoneDates.*' => 'nullable|date',
        'milestoneStatuses.*' => 'nullable|string|in:pending,in_progress,completed,cancelled',
    ];

    protected $messages = [
        'description.required' => 'Deskripsi proyek wajib diisi.',
        'description.min' => 'Deskripsi proyek minimal 10 karakter.',
        'scope.required' => 'Scope proyek wajib diisi.',
        'scope.min' => 'Scope proyek minimal 10 karakter.',
        'scheduleEndDates.*.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        'budgetAmounts.*.min' => 'Jumlah budget tidak boleh negatif.',
        'billingPercentages.*.min' => 'Persentase minimal 0%.',
        'billingPercentages.*.max' => 'Persentase maksimal 100%.',
        'billingAmounts.*.min' => 'Nominal tidak boleh negatif.',
    ];

    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::with([
            'detail', 
            'schedules', 
            'budgets', 
            'billingPlans', 
            'milestones'
        ])->findOrFail($project);

        // Check if user can fill this project
        if (!Gate::allows('fill', $this->project)) {
            abort(403, 'Anda tidak memiliki akses untuk mengisi data proyek ini.');
        }

        // Load existing data
        $this->loadExistingData();
    }

    private function loadExistingData()
    {
        // Load detail
        if ($this->project->detail) {
            $this->description = $this->project->detail->description;
            $this->scope = $this->project->detail->scope;
            $this->riskIssue = $this->project->detail->risk_issue;
            $this->deliverables = $this->project->detail->deliverables;
        }

        // Load schedules
        foreach ($this->project->schedules->sortBy('order') as $schedule) {
            $this->schedules[] = $schedule;
            $this->schedulePhases[] = $schedule->phase;
            $this->scheduleStartDates[] = $schedule->start_date->format('Y-m-d');
            $this->scheduleEndDates[] = $schedule->end_date->format('Y-m-d');
        }

        // Load budgets
        foreach ($this->project->budgets->sortBy('order') as $budget) {
            $this->budgets[] = $budget;
            $this->budgetItems[] = $budget->item;
            $this->budgetAmounts[] = $budget->amount;
            $this->budgetDescriptions[] = $budget->description;
        }

        // Load billing plans
        foreach ($this->project->billingPlans->sortBy('order') as $billing) {
            $this->billingPlans[] = $billing;
            $this->billingTermins[] = $billing->termin;
            $this->billingPercentages[] = $billing->percentage;
            $this->billingDates[] = $billing->planned_date->format('Y-m-d');
            $this->billingAmounts[] = $billing->amount;
        }

        // Load milestones
        foreach ($this->project->milestones->sortBy('order') as $milestone) {
            $this->milestones[] = $milestone;
            $this->milestoneNames[] = $milestone->name;
            $this->milestoneDates[] = $milestone->target_date->format('Y-m-d');
            $this->milestoneStatuses[] = $milestone->status;
        }
    }

    public function render()
    {
        return view('livewire.project.filling-form')
            ->layout('layouts.app');
    }

    // Schedule Methods
    public function addSchedule()
    {
        $this->schedules[] = null;
        $this->schedulePhases[] = '';
        $this->scheduleStartDates[] = '';
        $this->scheduleEndDates[] = '';
    }

    public function removeSchedule($index)
    {
        if (isset($this->schedules[$index]) && $this->schedules[$index]) {
            $this->schedules[$index]->delete();
        }
        unset($this->schedules[$index]);
        unset($this->schedulePhases[$index]);
        unset($this->scheduleStartDates[$index]);
        unset($this->scheduleEndDates[$index]);
        
        $this->schedules = array_values($this->schedules);
        $this->schedulePhases = array_values($this->schedulePhases);
        $this->scheduleStartDates = array_values($this->scheduleStartDates);
        $this->scheduleEndDates = array_values($this->scheduleEndDates);
    }

    // Budget Methods
    public function addBudget()
    {
        $this->budgets[] = null;
        $this->budgetItems[] = '';
        $this->budgetAmounts[] = '';
        $this->budgetDescriptions[] = '';
    }

    public function removeBudget($index)
    {
        if (isset($this->budgets[$index]) && $this->budgets[$index]) {
            $this->budgets[$index]->delete();
        }
        unset($this->budgets[$index]);
        unset($this->budgetItems[$index]);
        unset($this->budgetAmounts[$index]);
        unset($this->budgetDescriptions[$index]);
        
        $this->budgets = array_values($this->budgets);
        $this->budgetItems = array_values($this->budgetItems);
        $this->budgetAmounts = array_values($this->budgetAmounts);
        $this->budgetDescriptions = array_values($this->budgetDescriptions);
    }

    // Billing Plan Methods
    public function addBillingPlan()
    {
        $this->billingPlans[] = null;
        $this->billingTermins[] = '';
        $this->billingPercentages[] = '';
        $this->billingDates[] = '';
        $this->billingAmounts[] = '';
    }

    public function removeBillingPlan($index)
    {
        if (isset($this->billingPlans[$index]) && $this->billingPlans[$index]) {
            $this->billingPlans[$index]->delete();
        }
        unset($this->billingPlans[$index]);
        unset($this->billingTermins[$index]);
        unset($this->billingPercentages[$index]);
        unset($this->billingDates[$index]);
        unset($this->billingAmounts[$index]);
        
        $this->billingPlans = array_values($this->billingPlans);
        $this->billingTermins = array_values($this->billingTermins);
        $this->billingPercentages = array_values($this->billingPercentages);
        $this->billingDates = array_values($this->billingDates);
        $this->billingAmounts = array_values($this->billingAmounts);
    }

    // Milestone Methods
    public function addMilestone()
    {
        $this->milestones[] = null;
        $this->milestoneNames[] = '';
        $this->milestoneDates[] = '';
        $this->milestoneStatuses[] = 'pending';
    }

    public function removeMilestone($index)
    {
        if (isset($this->milestones[$index]) && $this->milestones[$index]) {
            $this->milestones[$index]->delete();
        }
        unset($this->milestones[$index]);
        unset($this->milestoneNames[$index]);
        unset($this->milestoneDates[$index]);
        unset($this->milestoneStatuses[$index]);
        
        $this->milestones = array_values($this->milestones);
        $this->milestoneNames = array_values($this->milestoneNames);
        $this->milestoneDates = array_values($this->milestoneDates);
        $this->milestoneStatuses = array_values($this->milestoneStatuses);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            $service = new ProjectFillingService();
            
            $data = [
                'description' => $this->description,
                'scope' => $this->scope,
                'riskIssue' => $this->riskIssue,
                'deliverables' => $this->deliverables,
                'schedules' => $this->getSchedulesData(),
                'budgets' => $this->getBudgetsData(),
                'billingPlans' => $this->getBillingPlansData(),
                'milestones' => $this->getMilestonesData(),
            ];

            $service->saveProjectData($this->project, $data);

            $this->showSuccessModal = true;
            session()->flash('success', 'Data proyek berhasil disimpan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function submitForReview()
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            $service = new ProjectFillingService();
            
            $data = [
                'description' => $this->description,
                'scope' => $this->scope,
                'riskIssue' => $this->riskIssue,
                'deliverables' => $this->deliverables,
                'schedules' => $this->getSchedulesData(),
                'budgets' => $this->getBudgetsData(),
                'billingPlans' => $this->getBillingPlansData(),
                'milestones' => $this->getMilestonesData(),
            ];

            $service->saveProjectData($this->project, $data);
            $service->submitForReview($this->project);

            $this->showSuccessModal = true;
            session()->flash('success', 'Data proyek berhasil disimpan dan dikirim untuk review!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getSchedulesData(): array
    {
        $schedules = [];
        foreach ($this->schedulePhases as $index => $phase) {
            if ($phase && isset($this->scheduleStartDates[$index]) && isset($this->scheduleEndDates[$index])) {
                $schedules[] = [
                    'phase' => $phase,
                    'start_date' => $this->scheduleStartDates[$index],
                    'end_date' => $this->scheduleEndDates[$index],
                    'order' => $index,
                ];
            }
        }
        return $schedules;
    }

    private function getBudgetsData(): array
    {
        $budgets = [];
        foreach ($this->budgetItems as $index => $item) {
            if ($item && isset($this->budgetAmounts[$index])) {
                $budgets[] = [
                    'item' => $item,
                    'amount' => $this->budgetAmounts[$index],
                    'description' => $this->budgetDescriptions[$index] ?? '',
                    'order' => $index,
                ];
            }
        }
        return $budgets;
    }

    private function getBillingPlansData(): array
    {
        $billingPlans = [];
        foreach ($this->billingTermins as $index => $termin) {
            if ($termin && isset($this->billingPercentages[$index]) && isset($this->billingAmounts[$index])) {
                $billingPlans[] = [
                    'termin' => $termin,
                    'percentage' => $this->billingPercentages[$index],
                    'planned_date' => $this->billingDates[$index],
                    'amount' => $this->billingAmounts[$index],
                    'order' => $index,
                ];
            }
        }
        return $billingPlans;
    }

    private function getMilestonesData(): array
    {
        $milestones = [];
        foreach ($this->milestoneNames as $index => $name) {
            if ($name && isset($this->milestoneDates[$index])) {
                $milestones[] = [
                    'name' => $name,
                    'target_date' => $this->milestoneDates[$index],
                    'status' => $this->milestoneStatuses[$index] ?? 'pending',
                    'order' => $index,
                ];
            }
        }
        return $milestones;
    }

    public function goToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function goToDetail()
    {
        return redirect()->route('project.detail', $this->project->id);
    }
}