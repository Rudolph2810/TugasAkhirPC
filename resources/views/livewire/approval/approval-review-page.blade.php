<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Review & Approval Proyek
                            <span class="text-sm font-normal text-gray-500 ml-4">
                                {{ $project->code }}
                            </span>
                        </h2>
                        <p class="text-gray-600 mt-1">{{ $project->title }}</p>
                    </div>
                    <div>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $project->status_badge_color }}-100 text-{{ $project->status_badge_color }}-800">
                            {{ $project->status_label }}
                        </span>
                    </div>
                </div>

                <!-- tambahkan info jika user adalah staff -->
                @if($isStaff)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>📋 Anda adalah Staff {{ auth()->user()->role_label }}</strong><br>
                                Anda dapat melakukan <strong>review</strong> dan <strong>approve</strong> proyek ini.
                                Silakan periksa data proyek dengan teliti sebelum melakukan approve atau revisi.
                            </p>
                        </div>
                    </div>
                </div>
                @endif


                <!-- Project Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 rounded-lg p-4 mb-6">
                    <div>
                        <span class="text-sm text-gray-500">Client</span>
                        <p class="font-medium">{{ $project->client }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Segmen Bisnis</span>
                        <p class="font-medium">{{ $project->businessSegment->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Nilai Kontrak</span>
                        <p class="font-medium">Rp {{ number_format($project->contract_value, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Lokasi</span>
                        <p class="font-medium">{{ $project->location }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Periode</span>
                        <p class="font-medium">{{ $project->start_date->format('d/m/Y') }} -
                            {{ $project->end_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Dibuat oleh</span>
                        <p class="font-medium">{{ $project->creator->name }}</p>
                    </div>
                </div>

                <!-- Project Details -->
                @if($project->detail)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Proyek</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500">Deskripsi</span>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">{{ $project->detail->description }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Scope</span>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">{{ $project->detail->scope }}</div>
                        </div>
                        @if($project->detail->risk_issue)
                        <div>
                            <span class="text-sm text-gray-500">Risk & Issue</span>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">{{ $project->detail->risk_issue }}</div>
                        </div>
                        @endif
                        @if($project->detail->deliverables)
                        <div>
                            <span class="text-sm text-gray-500">Deliverables</span>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">{{ $project->detail->deliverables }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Schedules -->
                @if($project->schedules->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Schedule</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahapan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($project->schedules as $schedule)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $schedule->phase }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $schedule->start_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $schedule->end_date->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Budgets -->
                @if($project->budgets->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Budgeting</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($project->budgets as $budget)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $budget->item }}</td>
                                    <td class="px-6 py-4 text-sm">Rp {{ number_format($budget->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $budget->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Approval History -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Persetujuan</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($approvals as $approval)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                        aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $approval->approver->name }}</span>
                                                <span
                                                    class="text-sm text-gray-500">{{ $approval->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($approval->action === 'approve') bg-green-100 text-green-800
                                                    @elseif($approval->action === 'cancel') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $approval->action_label }}
                                                </span>
                                                <span class="text-sm text-gray-500 ml-2">
                                                    {{ $approval->role_label }}
                                                    @if($approval->level)
                                                    - {{ ucfirst(str_replace('_', ' ', $approval->level)) }}
                                                    @endif
                                                </span>
                                            </div>
                                            @if($approval->notes)
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Catatan:</span> {{ $approval->notes }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Approval Actions -->
                @if($canApprove && !$project->isComplete())
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Aksi Approval</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan (wajib untuk Revisi)</label>
                            <textarea wire:model="notes" rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan catatan jika diperlukan..."></textarea>
                            @error('notes') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button wire:click="confirmAction('approve')"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve
                            </button>
                            <button wire:click="confirmAction('revisi')"
                                class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Revisi
                            </button>
                            <button wire:click="confirmAction('cancel')"
                                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Back Button -->
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <div class="text-center">
                @if($action === 'approve')
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Konfirmasi Approve</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Apakah Anda yakin ingin menyetujui proyek ini?
                </p>
                @elseif($action === 'revisi')
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Konfirmasi Revisi</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Apakah Anda yakin ingin mengembalikan proyek ini untuk revisi?
                </p>
                @if($notes)
                <div class="mt-3 p-3 bg-yellow-50 rounded-md text-sm text-gray-700">
                    <span class="font-medium">Catatan Revisi:</span><br>
                    {{ $notes }}
                </div>
                @endif
                @else
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Konfirmasi Cancel</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Apakah Anda yakin ingin membatalkan proyek ini?
                </p>
                @endif
            </div>

            <div class="mt-6 flex justify-center space-x-3">
                <button wire:click="closeModal"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button wire:click="processApproval" class="px-4 py-2
                    @if($action === 'approve') bg-green-600 hover:bg-green-700
                    @elseif($action === 'revisi') bg-yellow-600 hover:bg-yellow-700
                    @else bg-red-600 hover:bg-red-700 @endif
                    text-white rounded-md">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('message'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('message') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
    @endif
</div>