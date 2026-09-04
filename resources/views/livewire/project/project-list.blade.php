@section('title', 'Dahboard')

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Proyek</h1>
                    <p class="text-sm text-gray-500">Total Proyek: <strong>{{ $projects->total() ?? 0 }}</strong></p>
                </div>
                @if(auth()->user()->role === 'comercil' && auth()->user()->level === 'staff')
                <a href="{{ route('project.initiate') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Inisiasi Proyek Baru
                </a>
                @endif
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Total Proyek</p>
                    <p class="text-xl font-bold text-blue-600">{{ $projects->total() ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Rilis</p>
                    <p class="text-xl font-bold text-green-600">
                        {{ App\Models\Project::where('status', 'rilis')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Review</p>
                    <p class="text-xl font-bold text-yellow-600">
                        {{ App\Models\Project::where('status', 'like', 'review%')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500">Menunggu Isi Data</p>
                    <p class="text-xl font-bold text-blue-600">
                        {{ App\Models\Project::where('status', 'menunggu_pengisian_pelaksana')->count() }}
                    </p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input wire:model.live="search" type="text" placeholder="Cari proyek..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="yearFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input wire:model.live="clientFilter" type="text" placeholder="Filter Client..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

            <!-- Tabel Proyek -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($projects as $index => $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $project->code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($project->title, 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $project->client }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-{{ $project->status_badge_color }}-100 text-{{ $project->status_badge_color }}-800">
                                        {{ $project->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <!-- Detail Button -->
                                        <a href="{{ route('project.detail', $project->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            Detail
                                        </a>

                                        <!-- ✅ TOMBOL APPROVE -->
                                        @if(auth()->user()->can('approve', $project) && !$project->isComplete())
                                        @php
                                        $user = auth()->user();
                                        $status = $project->status;
                                        @endphp

                                        <!-- Comercil Division Head Approve -->
                                        @if($user->role === 'comercil' && $user->level === 'division_head' && $status
                                        === 'review_division_head_comercil')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Comercil Dept Head Approve -->
                                        @if($user->role === 'comercil' && $user->level === 'department_head' && $status
                                        === 'review_dept_head_comercil')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Pelaksana Division Head Approve -->
                                        @if($user->role === 'pelaksana' && $user->level === 'division_head' && $status
                                        === 'review_division_head_pelaksana')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Pelaksana Dept Head Approve -->
                                        @if($user->role === 'pelaksana' && $user->level === 'department_head' && $status
                                        === 'review_dept_head_pelaksana')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- PCCM Staff Approve -->
                                        @if($user->role === 'pccm' && $user->level === 'staff' && $status ===
                                        'review_pccm')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- PCCM Dept Head Approve -->
                                        @if($user->role === 'pccm' && $user->level === 'department_head' && $status ===
                                        'review_dept_head_pccm')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- PCCM Div Head Approve -->
                                        @if($user->role === 'pccm' && $user->level === 'division_head' && $status ===
                                        'review_division_head_pccm')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Finance Staff Approve -->
                                        @if($user->role === 'finance' && $user->level === 'staff' && $status ===
                                        'review_finance')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Finance Dept Head Approve -->
                                        @if($user->role === 'finance' && $user->level === 'department_head' && $status
                                        === 'review_dept_head_finance')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Finance Div Head Approve -->
                                        @if($user->role === 'finance' && $user->level === 'division_head' && $status ===
                                        'review_division_head_finance')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve
                                        </a>
                                        @endif

                                        <!-- Direksi Approve -->
                                        @if($user->role === 'direksi' && $status === 'review_direksi')
                                        <a href="{{ route('project.approve', $project->id) }}"
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            ✅ Approve Final
                                        </a>
                                        @endif
                                        @endif

                                        <!-- ✅ TOMBOL ISI DATA - Pelaksana Staff -->
                                        @if(($project->status === 'menunggu_pengisian_pelaksana' || $project->status ===
                                        'revisi') &&
                                        auth()->user()->role === 'pelaksana' &&
                                        auth()->user()->level === 'staff')
                                        <a href="{{ route('project.fill', $project->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            📝 Isi Data
                                        </a>
                                        @endif

                                        <!-- Download RKAP -->
                                        @if($project->status === 'rilis')
                                        <a href="{{ route('project.export-rkap', $project->id) }}"
                                            class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            Download RKAP
                                        </a>
                                        @endif

                                        <!-- Download Surat Rilis -->
                                        @if($project->status === 'rilis')
                                        <a href="{{ route('project.download-release', $project->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md transition text-xs font-medium">
                                            PDF
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <p class="mt-2">Belum ada proyek</p>
                                    @if(auth()->user()->role === 'comercil' && auth()->user()->level === 'staff')
                                    <a href="{{ route('project.initiate') }}"
                                        class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                        + Inisiasi proyek baru
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    @endif
</div>