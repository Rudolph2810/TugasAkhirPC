<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\Division;
use App\Models\BusinessSegment;

class MasterDataManagement extends Component
{
    public $activeTab = 'departments';

    // Department properties
    public $deptId;
    public $deptName;
    public $deptCode;
    public $deptDescription;
    public $deptIsActive = true;
    public $showDeptModal = false;

    // Division properties
    public $divId;
    public $divDepartmentId;
    public $divName;
    public $divCode;
    public $divDescription;
    public $divIsActive = true;
    public $showDivModal = false;

    // Business Segment properties
    public $segId;
    public $segName;
    public $segCode;
    public $segIsActive = true;
    public $showSegModal = false;

    protected $rules = [];

    public function render()
    {
        return view('livewire.admin.master-data-management', [
            'departments' => Department::orderBy('name')->get(),
            'divisions' => Division::with('department')->orderBy('name')->get(),
            'businessSegments' => BusinessSegment::orderBy('name')->get(),
        ]);
    }

    // Department Methods
    public function createDepartment()
    {
        $this->resetDeptForm();
        $this->showDeptModal = true;
    }

    public function editDepartment($id)
    {
        $dept = Department::findOrFail($id);
        $this->deptId = $dept->id;
        $this->deptName = $dept->name;
        $this->deptCode = $dept->code;
        $this->deptDescription = $dept->description;
        $this->deptIsActive = $dept->is_active;
        $this->showDeptModal = true;
    }

    public function saveDepartment()
    {
        $this->validate([
            'deptName' => 'required|string|max:255',
            'deptCode' => 'nullable|string|max:50',
            'deptDescription' => 'nullable|string',
            'deptIsActive' => 'boolean',
        ]);

        $data = [
            'name' => $this->deptName,
            'code' => $this->deptCode,
            'description' => $this->deptDescription,
            'is_active' => $this->deptIsActive,
        ];

        if ($this->deptId) {
            Department::find($this->deptId)->update($data);
            session()->flash('message', 'Departemen berhasil diupdate.');
        } else {
            Department::create($data);
            session()->flash('message', 'Departemen berhasil dibuat.');
        }

        $this->showDeptModal = false;
        $this->resetDeptForm();
    }

    public function deleteDepartment($id)
    {
        Department::findOrFail($id)->delete();
        session()->flash('message', 'Departemen berhasil dihapus.');
    }

    private function resetDeptForm()
    {
        $this->deptId = null;
        $this->deptName = '';
        $this->deptCode = '';
        $this->deptDescription = '';
        $this->deptIsActive = true;
    }

    // Division Methods
    public function createDivision()
    {
        $this->resetDivForm();
        $this->showDivModal = true;
    }

    public function editDivision($id)
    {
        $div = Division::findOrFail($id);
        $this->divId = $div->id;
        $this->divDepartmentId = $div->department_id;
        $this->divName = $div->name;
        $this->divCode = $div->code;
        $this->divDescription = $div->description;
        $this->divIsActive = $div->is_active;
        $this->showDivModal = true;
    }

    public function saveDivision()
    {
        $this->validate([
            'divDepartmentId' => 'required|exists:departments,id',
            'divName' => 'required|string|max:255',
            'divCode' => 'nullable|string|max:50',
            'divDescription' => 'nullable|string',
            'divIsActive' => 'boolean',
        ]);

        $data = [
            'department_id' => $this->divDepartmentId,
            'name' => $this->divName,
            'code' => $this->divCode,
            'description' => $this->divDescription,
            'is_active' => $this->divIsActive,
        ];

        if ($this->divId) {
            Division::find($this->divId)->update($data);
            session()->flash('message', 'Divisi berhasil diupdate.');
        } else {
            Division::create($data);
            session()->flash('message', 'Divisi berhasil dibuat.');
        }

        $this->showDivModal = false;
        $this->resetDivForm();
    }

    public function deleteDivision($id)
    {
        Division::findOrFail($id)->delete();
        session()->flash('message', 'Divisi berhasil dihapus.');
    }

    private function resetDivForm()
    {
        $this->divId = null;
        $this->divDepartmentId = null;
        $this->divName = '';
        $this->divCode = '';
        $this->divDescription = '';
        $this->divIsActive = true;
    }

    // Business Segment Methods
    public function createSegment()
    {
        $this->resetSegForm();
        $this->showSegModal = true;
    }

    public function editSegment($id)
    {
        $seg = BusinessSegment::findOrFail($id);
        $this->segId = $seg->id;
        $this->segName = $seg->name;
        $this->segCode = $seg->code;
        $this->segIsActive = $seg->is_active;
        $this->showSegModal = true;
    }

    public function saveSegment()
    {
        $this->validate([
            'segName' => 'required|string|max:255',
            'segCode' => 'nullable|string|max:50',
            'segIsActive' => 'boolean',
        ]);

        $data = [
            'name' => $this->segName,
            'code' => $this->segCode,
            'is_active' => $this->segIsActive,
        ];

        if ($this->segId) {
            BusinessSegment::find($this->segId)->update($data);
            session()->flash('message', 'Segmen Bisnis berhasil diupdate.');
        } else {
            BusinessSegment::create($data);
            session()->flash('message', 'Segmen Bisnis berhasil dibuat.');
        }

        $this->showSegModal = false;
        $this->resetSegForm();
    }

    public function deleteSegment($id)
    {
        BusinessSegment::findOrFail($id)->delete();
        session()->flash('message', 'Segmen Bisnis berhasil dihapus.');
    }

    private function resetSegForm()
    {
        $this->segId = null;
        $this->segName = '';
        $this->segCode = '';
        $this->segIsActive = true;
    }
}