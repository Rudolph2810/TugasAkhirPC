<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Department;
use App\Models\Division;
use App\Enums\RoleEnum;
use App\Enums\LevelEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    // ============================================================
    // FILTERS
    // ============================================================
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $levelFilter = '';

    // ============================================================
    // FORM PROPERTIES
    // ============================================================
    public $showModal = false;
    public $isEditing = false;
    public $userId = null;

    public $nip;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;
    public $level;
    public $department_id;
    public $division_id;
    public $is_active = true;

    // ============================================================
    // CONFIRM DIALOG
    // ============================================================
    public $showConfirmDialog = false;
    public $confirmAction = '';
    public $confirmUserId = null;
    public $confirmMessage = '';

    // ... semua properties yang sudah ada ...

    // ============================================================
    // GET LEVELS FOR ROLE
    // ============================================================
    public function getLevelsForRole($role)
    {
        return LevelEnum::getLevelsForRole($role);
    }

    public function roleRequiresLevel($role)
    {
        return LevelEnum::roleRequiresLevel($role);
    }
    
    // ============================================================
    // GET USERS BY ROLE AND LEVEL (untuk dropdown)
    // ============================================================
    public function getUsersByRoleAndLevel($role, $level)
    {
        return User::where('role', $role)
            ->where('level', $level)
            ->where('is_active', true)
            ->select('id', 'name', 'nip')
            ->get();
    }

    // ============================================================
    // GET DEPARTMENT HEAD FOR A ROLE
    // ============================================================
    public function getDepartmentHeadForRole($role)
    {
        return User::where('role', $role)
            ->where('level', LevelEnum::DEPARTMENT_HEAD->value)
            ->where('is_active', true)
            ->first();
    }

    // ============================================================
    // GET DIVISION HEAD FOR A ROLE
    // ============================================================
    public function getDivisionHeadForRole($role)
    {
        return User::where('role', $role)
            ->where('level', LevelEnum::DIVISION_HEAD->value)
            ->where('is_active', true)
            ->first();
    }


    // ============================================================
    // RULES
    // ============================================================
    protected function rules()
    {
        $rules = [
            'nip' => 'required|string|max:50|unique:users,nip',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string|in:admin,comercil,pelaksana,pccm,finance,direksi,pending',
            'level' => 'nullable|string|in:staff,department_head,division_head',
            'department_id' => 'required_if:role,comercil,pelaksana,pccm,finance|exists:departments,id',
            'division_id' => 'required_if:role,comercil,pelaksana,pccm,finance|exists:divisions,id',
            'is_active' => 'boolean',
        ];

        if ($this->isEditing) {
            $rules['nip'] = 'required|string|max:50|unique:users,nip,' . $this->userId;
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->userId;
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'nip.required' => 'NIP wajib diisi.',
        'nip.unique' => 'NIP sudah digunakan.',
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.unique' => 'Email sudah digunakan.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Role wajib dipilih.',
        'level.in' => 'Level wajib diisi.',
        'department_id.required_if' => 'Departemen wajib diisi untuk role ini.',
        'division_id.required_if' => 'Divisi wajib diisi untuk role ini.',
    ];

    // ============================================================
    // MOUNT
    // ============================================================
    public function mount()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengakses halaman ini.');
        }
    }

    // ============================================================
    // RENDER
    // ============================================================
   public function render()
    {
        $users = User::with(['department', 'division'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('nip', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->levelFilter, function ($query) {
                $query->where('level', $this->levelFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistik
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admin' => User::where('role', 'admin')->count(),
            'pending' => User::where('role', 'pending')->count(),
            'direksi' => User::where('role', 'direksi')->count(),
        ];

        // Level statistik per role
        $levelStats = [];
        foreach (LevelEnum::cases() as $level) {
            $levelStats[$level->value] = User::where('level', $level->value)->count();
        }

        // Statistik per role dengan level
        $roleLevelStats = [];
        foreach (RoleEnum::getRolesWithLevel() as $role) {
            foreach (LevelEnum::cases() as $level) {
                $key = $role . '_' . $level->value;
                $roleLevelStats[$key] = User::where('role', $role)
                    ->where('level', $level->value)
                    ->count();
            }
        }

        return view('livewire.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
            'levelStats' => $levelStats,
            'roleLevelStats' => $roleLevelStats,
            'roles' => RoleEnum::cases(),
            'levels' => LevelEnum::cases(),
            'rolesWithLevel' => RoleEnum::getRolesWithLevel(),
            'departments' => Department::where('is_active', true)->orderBy('name')->get(),
            'divisions' => Division::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    // ============================================================
    // CREATE / EDIT
    // ============================================================
    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->isEditing = true;
        $this->nip = $user->nip;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->level = $user->level;
        $this->department_id = $user->department_id;
        $this->division_id = $user->division_id;
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nip' => $this->nip,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'level' => $this->level,
            'department_id' => $this->department_id,
            'division_id' => $this->division_id,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            session()->flash('message', "User {$user->name} berhasil diupdate.");
        } else {
            $user = User::create($data);
            session()->flash('message', "User {$user->name} berhasil dibuat.");
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetPage();
    }

    // ============================================================
    // TOGGLE STATUS
    // ============================================================
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            return;
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        session()->flash('message', "Status user {$user->name} diubah menjadi " . ($newStatus ? 'Aktif' : 'Nonaktif'));
    }

    // ============================================================
    // CHANGE ROLE
    // ============================================================
    public function changeRole($id, $newRole)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id() && $newRole !== 'admin') {
            session()->flash('error', 'Anda tidak dapat mengubah role sendiri.');
            return;
        }

        $user->update(['role' => $newRole]);

        // Reset level jika role tidak membutuhkan level
        if (!in_array($newRole, ['comercil', 'pelaksana', 'pccm', 'finance'])) {
            $user->update(['level' => null]);
        }

        session()->flash('message', "Role user {$user->name} diubah menjadi " . RoleEnum::tryFrom($newRole)?->label());
    }

    // ============================================================
    // CHANGE LEVEL
    // ============================================================
    public function changeLevel($id, $newLevel)
    {
        $user = User::findOrFail($id);

        // Cek apakah role membutuhkan level
        if (!in_array($user->role, ['comercil', 'pelaksana', 'pccm', 'finance'])) {
            session()->flash('error', "Role {$user->role_label} tidak membutuhkan level.");
            return;
        }

        $user->update(['level' => $newLevel]);

        session()->flash('message', "Level user {$user->name} diubah menjadi " . LevelEnum::tryFrom($newLevel)?->label());
    }

    // ============================================================
    // RESET PASSWORD
    // ============================================================
    public function confirmResetPassword($id)
    {
        $user = User::findOrFail($id);
        $this->confirmUserId = $id;
        $this->confirmAction = 'reset_password';
        $this->confirmMessage = "Apakah Anda yakin ingin mereset password user <strong>{$user->name}</strong> ke default?";
        $this->showConfirmDialog = true;
    }

    public function resetPassword()
    {
        $user = User::findOrFail($this->confirmUserId);
        $defaultPassword = 'password123';
        $user->update(['password' => Hash::make($defaultPassword)]);

        session()->flash('message', "Password user {$user->name} telah direset menjadi '<strong>password123</strong>'.");
        $this->showConfirmDialog = false;
    }

    // ============================================================
    // DELETE USER
    // ============================================================
    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }

        if ($user->projects()->count() > 0) {
            session()->flash('error', "User {$user->name} memiliki proyek dan tidak dapat dihapus.");
            return;
        }

        $this->confirmUserId = $id;
        $this->confirmAction = 'delete';
        $this->confirmMessage = "Apakah Anda yakin ingin menghapus user <strong>{$user->name}</strong>?";
        $this->showConfirmDialog = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->confirmUserId);
        $userName = $user->name;
        $user->delete();

        session()->flash('message', "User {$userName} berhasil dihapus.");
        $this->showConfirmDialog = false;
        $this->resetPage();
    }

    // ============================================================
    // BULK ACTIONS
    // ============================================================
    public function bulkActivate()
    {
        User::where('is_active', false)->update(['is_active' => true]);
        session()->flash('message', 'Semua user berhasil diaktifkan.');
    }

    public function bulkDeactivate()
    {
        User::where('role', '!=', 'admin')->update(['is_active' => false]);
        session()->flash('message', 'Semua user (kecuali admin) berhasil dinonaktifkan.');
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================
    public function updatedRole()
    {
        // Reset level jika role tidak membutuhkan level
        if (!in_array($this->role, ['comercil', 'pelaksana', 'pccm', 'finance'])) {
            $this->level = null;
        }
    }

    public function updatedDepartmentId()
    {
        $this->division_id = null;
    }

    public function getDivisions()
    {
        if ($this->department_id) {
            return Division::where('department_id', $this->department_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }
        return collect();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->isEditing = false;
        $this->nip = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->level = null;
        $this->department_id = null;
        $this->division_id = null;
        $this->is_active = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeConfirmDialog()
    {
        $this->showConfirmDialog = false;
        $this->confirmUserId = null;
        $this->confirmAction = '';
        $this->confirmMessage = '';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roleFilter = '';
        $this->statusFilter = '';
        $this->levelFilter = '';
        $this->resetPage();
    }
}