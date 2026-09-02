<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola user, aktivasi status, role dan level</p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                    <button wire:click="bulkActivate"
                        class="px-3 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-sm">
                        Aktifkan Semua
                    </button>
                    <button wire:click="bulkDeactivate"
                        class="px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm">
                        Nonaktifkan Semua
                    </button>
                    <button wire:click="create"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
                    </button>
                </div>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Total User</p>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Aktif</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Nonaktif</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['inactive'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Admin</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['admin'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Level Statistik -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-3">
                    <p class="text-xs text-gray-500">Staff</p>
                    <p class="text-lg font-bold text-blue-600">{{ $levelStats['staff'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-3">
                    <p class="text-xs text-gray-500">Dept Head</p>
                    <p class="text-lg font-bold text-yellow-600">{{ $levelStats['department_head'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-3">
                    <p class="text-xs text-gray-500">Div Head</p>
                    <p class="text-lg font-bold text-green-600">{{ $levelStats['division_head'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-3">
                    <p class="text-xs text-gray-500">Direksi</p>
                    <p class="text-lg font-bold text-red-600">{{ $stats['direksi'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Statistik Level per Role -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Comercil</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Staff</p>
                            <p class="text-lg font-bold text-blue-600">{{ $roleLevelStats['comercil_staff'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Dept Head</p>
                            <p class="text-lg font-bold text-yellow-600">
                                {{ $roleLevelStats['comercil_department_head'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Div Head</p>
                            <p class="text-lg font-bold text-green-600">
                                {{ $roleLevelStats['comercil_division_head'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Pelaksana</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Staff</p>
                            <p class="text-lg font-bold text-blue-600">{{ $roleLevelStats['pelaksana_staff'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Dept Head</p>
                            <p class="text-lg font-bold text-yellow-600">
                                {{ $roleLevelStats['pelaksana_department_head'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Div Head</p>
                            <p class="text-lg font-bold text-green-600">
                                {{ $roleLevelStats['pelaksana_division_head'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">PCCM</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Staff</p>
                            <p class="text-lg font-bold text-blue-600">{{ $roleLevelStats['pccm_staff'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Dept Head</p>
                            <p class="text-lg font-bold text-yellow-600">
                                {{ $roleLevelStats['pccm_department_head'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Div Head</p>
                            <p class="text-lg font-bold text-green-600">{{ $roleLevelStats['pccm_division_head'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Finance</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Staff</p>
                            <p class="text-lg font-bold text-blue-600">{{ $roleLevelStats['finance_staff'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Dept Head</p>
                            <p class="text-lg font-bold text-yellow-600">
                                {{ $roleLevelStats['finance_department_head'] ?? 0 }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Div Head</p>
                            <p class="text-lg font-bold text-green-600">
                                {{ $roleLevelStats['finance_division_head'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Legenda Level -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Keterangan Level</h4>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Staff - Input data</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Department Head - Approval Level 1</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Division Head - Approval Level 2</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Direksi - Approval Final</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                        <span class="text-sm text-gray-600">Admin - Manajemen Sistem</span>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input wire:model.live="search" type="text" placeholder="Cari user..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <select wire:model.live="roleFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="levelFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Level</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->value }}">{{ $level->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button wire:click="resetFilters" class="text-sm text-gray-500 hover:text-gray-700">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Tabel User -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->nip }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                    <span
                                        class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Anda</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                                            @elseif($user->role === 'direksi') bg-red-100 text-red-800
                                            @elseif($user->role === 'comercil') bg-blue-100 text-blue-800
                                            @elseif($user->role === 'pelaksana') bg-green-100 text-green-800
                                            @elseif($user->role === 'pccm') bg-yellow-100 text-yellow-800
                                            @elseif($user->role === 'finance') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                        {{ $user->role_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->level)
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                        {{ $user->level_label }}
                                    </span>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded text-xs">
                                            Edit
                                        </button>

                                        @if($user->id !== auth()->id())
                                        <button wire:click="toggleStatus({{ $user->id }})"
                                            class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100' : 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100' }} px-2 py-1 rounded text-xs">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                        @endif

                                        <button wire:click="confirmResetPassword({{ $user->id }})"
                                            class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 px-2 py-1 rounded text-xs">
                                            Reset PW
                                        </button>

                                        @if($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 rounded text-xs">
                                            Hapus
                                        </button>
                                        @endif

                                        <!-- Change Role Dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded text-xs">
                                                Role
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute z-10 mt-1 w-40 bg-white rounded-md shadow-lg border">
                                                @foreach($roles as $role)
                                                <button wire:click="changeRole({{ $user->id }}, '{{ $role->value }}')"
                                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $user->role === $role->value ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                                    {{ $role->label() }}
                                                    @if($user->role === $role->value)
                                                    <span class="float-right text-blue-600">✓</span>
                                                    @endif
                                                </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Change Level Dropdown -->
                                        @if(in_array($user->role, ['comercil', 'pelaksana', 'pccm', 'finance']))
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 px-2 py-1 rounded text-xs">
                                                Level
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute z-10 mt-1 w-40 bg-white rounded-md shadow-lg border">
                                                @foreach($levels as $level)
                                                <button wire:click="changeLevel({{ $user->id }}, '{{ $level->value }}')"
                                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $user->level === $level->value ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                                    {{ $level->label() }}
                                                    @if($user->level === $level->value)
                                                    <span class="float-right text-blue-600">✓</span>
                                                    @endif
                                                </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="mt-2">Tidak ada user yang ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE/EDIT -->
    @if($showModal)
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2 border-b">
                <h3 class="text-lg font-bold">
                    {{ $isEditing ? 'Edit User' : 'Tambah User Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP <span
                                class="text-red-500">*</span></label>
                        <input wire:model="nip" type="text"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('nip') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama <span
                                class="text-red-500">*</span></label>
                        <input wire:model="name" type="text"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email <span
                                class="text-red-500">*</span></label>
                        <input wire:model="email" type="email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Password @if(!$isEditing) <span class="text-red-500">*</span> @endif
                        </label>
                        <input wire:model="password" type="password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ $isEditing ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input wire:model="password_confirmation" type="password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role <span
                                class="text-red-500">*</span></label>
                        <select wire:model.live="role"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Level -->
                    @if(in_array($role, ['comercil', 'pelaksana', 'pccm', 'finance']))
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Level <span
                                class="text-red-500">*</span></label>
                        <select wire:model="level"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Level</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->value }}">{{ $level->label() }}</option>
                            @endforeach
                        </select>
                        @error('level') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Departemen</label>
                        <select wire:model.live="department_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Division -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Divisi</label>
                        <select wire:model="division_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Divisi</option>
                            @foreach($this->getDivisions() as $div)
                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                            @endforeach
                        </select>
                        @error('division_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-700">Aktif</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 border-t pt-4">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $isEditing ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- CONFIRM DIALOG -->
    @if($showConfirmDialog)
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full 
                    @if($confirmAction === 'delete') bg-red-100
                    @else bg-orange-100 @endif">
                    @if($confirmAction === 'delete')
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    @else
                    <svg class="h-10 w-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    @endif
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">
                    {{ $confirmAction === 'delete' ? 'Konfirmasi Hapus' : 'Konfirmasi Reset Password' }}
                </h3>
                <p class="mt-2 text-sm text-gray-500">
                    {!! $confirmMessage !!}
                </p>
            </div>

            <div class="mt-6 flex justify-center space-x-3">
                <button wire:click="closeConfirmDialog"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button wire:click="{{ $confirmAction === 'delete' ? 'deleteUser' : 'resetPassword' }}"
                    class="px-4 py-2 {{ $confirmAction === 'delete' ? 'bg-red-600 hover:bg-red-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white rounded-md">
                    {{ $confirmAction === 'delete' ? 'Ya, Hapus' : 'Ya, Reset' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- FLASH MESSAGES -->
    @if(session()->has('message'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {!! session('message') !!}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('error') }}
    </div>
    @endif
</div>