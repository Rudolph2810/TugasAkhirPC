<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $project->code }}
                            <span class="text-sm font-normal text-gray-500 ml-2">
                                {{ $project->title }}
                            </span>
                        </h2>
                        <div class="mt-2 flex items-center space-x-2">
                            <span
                                class="px-2 py-1 text-xs rounded-full bg-{{ $project->status_badge_color }}-100 text-{{ $project->status_badge_color }}-800">
                                {{ $project->status_label }}
                            </span>
                            @if($project->status === 'rilis')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                ✅ Rilis pada {{ $project->released_at?->format('d/m/Y H:i') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($project->status === 'rilis')
                        <button wire:click="downloadSuratRilis"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Surat Rilis
                        </button>
                        @endif

                        @if($canFill && ($project->status === 'menunggu_pengisian_pelaksana' || $project->status ===
                        'revisi'))
                        <a href="{{ route('project.fill', $project->id) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            Isi Data Proyek
                        </a>
                        @endif

                        @if($canApprove && !$project->isComplete())
                        <a href="{{ route('project.approve', $project->id) }}"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Review & Approval
                        </a>
                        @endif

                        @if($canImportRkap && ($project->status === 'menunggu_pengisian_pelaksana' || $project->status
                        === 'revisi' || $project->status === 'rilis'))
                        <a href="{{ route('project.rkap', $project->id) }}"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                            </svg>
                            Manajemen RKAP
                        </a>
                        @endif

                        @if($canExportRkap && $project->rkapItems->isNotEmpty())
                        <button wire:click="downloadRkap"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download RKAP
                        </button>
                        @endif

                        <!-- Back Button -->
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm flex items-center transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Info -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Proyek</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Client</span>
                        <p class="font-medium">{{ $project->client }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Segmen Bisnis</span>
                        <p class="font-medium">{{ $project->businessSegment?->name ?? '-' }}</p>
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
                    <div>
                        <span class="text-sm text-gray-500">Dibuat pada</span>
                        <p class="font-medium">{{ $project->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($project->current_approver)
                    <div>
                        <span class="text-sm text-gray-500">Approver saat ini</span>
                        <p class="font-medium">{{ $project->current_approver->name }}</p>
                    </div>
                    @endif
                    @if($project->revisi_notes)
                    <div class="col-span-full">
                        <span class="text-sm text-gray-500">Catatan Revisi</span>
                        <p class="mt-1 p-3 bg-yellow-50 rounded-md text-yellow-800">{{ $project->revisi_notes }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        @if($project->detail)
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
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
        </div>
        @endif

        <!-- Schedules -->
        @if($project->schedules->isNotEmpty())
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Schedule</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahapan</th>
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
        </div>
        @endif

        <!-- Budgets -->
        @if($project->budgets->isNotEmpty())
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Budgeting</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->budgets as $budget)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $budget->item }}</td>
                                <td class="px-6 py-4 text-sm">{{ $budget->description }}</td>
                                <td class="px-6 py-4 text-sm">Rp {{ number_format($budget->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="2" class="px-6 py-3 text-right font-medium">Total</th>
                                <th class="px-6 py-3 font-medium">Rp
                                    {{ number_format($project->budgets->sum('amount'), 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Billing Plans -->
        @if($project->billingPlans->isNotEmpty())
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Rencana Penagihan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Termin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                    Rencana Tagih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->billingPlans as $billing)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $billing->termin }}</td>
                                <td class="px-6 py-4 text-sm">{{ $billing->percentage }}%</td>
                                <td class="px-6 py-4 text-sm">{{ $billing->planned_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm">Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-6 py-3 text-right font-medium">Total</th>
                                <th class="px-6 py-3 font-medium">Rp
                                    {{ number_format($project->billingPlans->sum('amount'), 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Milestones -->
        @if($project->milestones->isNotEmpty())
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Milestone</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                    Milestone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->milestones as $milestone)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $milestone->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $milestone->target_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($milestone->status === 'completed') bg-green-100 text-green-800
                                        @elseif($milestone->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($milestone->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- RKAP Items -->
        @if($project->rkapItems->isNotEmpty())
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Data RKAP</h3>
                    <div class="flex items-center gap-3">
                        @if($canExportRkap && $project->rkapItems->isNotEmpty())
                        <button wire:click="downloadRkap" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Download RKAP
                        </button>
                        @endif

                        @if($canImportRkap && ($project->status === 'menunggu_pengisian_pelaksana' || $project->status
                        === 'revisi' || $project->status === 'rilis'))
                        <a href="{{ route('project.rkap', $project->id) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm">
                            Kelola RKAP
                        </a>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                    Anggaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail
                                    Rencana</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai RKAP
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->rkapItems as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $item->no }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->tahun }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->kode_anggaran }}</td>
                                <td class="px-6 py-4 text-sm">{{ Str::limit($item->detail_rencana, 50) }}</td>
                                <td class="px-6 py-4 text-sm">Rp {{ number_format($item->nilai_rkap, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="4" class="px-6 py-3 text-right font-medium">Total</th>
                                <th class="px-6 py-3 font-medium">Rp
                                    {{ number_format($project->rkapItems->sum('nilai_rkap'), 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Attachments -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Lampiran</h3>
                    @if($canFill || auth()->user()->role === 'admin' || auth()->user()->role === 'comercil')
                    <button wire:click="openAttachmentModal"
                        class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        + Tambah Lampiran
                    </button>
                    @endif
                </div>

                @if($project->attachments->isEmpty())
                <div class="text-center py-6 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2">Belum ada lampiran</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($project->attachments as $attachment)
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $attachment->document_type }}</p>
                                @if($attachment->document_number)
                                <p class="text-sm text-gray-500">No: {{ $attachment->document_number }}</p>
                                @endif
                                @if($attachment->document_date)
                                <p class="text-sm text-gray-500">Tanggal:
                                    {{ $attachment->document_date->format('d/m/Y') }}</p>
                                @endif
                                @if($attachment->description)
                                <p class="text-sm text-gray-500">{{ Str::limit($attachment->description, 100) }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    Upload: {{ $attachment->uploaded_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('project.attachment.download', $attachment->id) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    Download
                                </a>
                                <a href="{{ route('project.attachment.preview', $attachment->id) }}" target="_blank"
                                    class="text-green-600 hover:text-green-800 text-sm">
                                    Preview
                                </a>
                                @if($canFill || auth()->user()->role === 'admin')
                                <button wire:click="editAttachment({{ $attachment->id }})"
                                    class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    Edit
                                </button>
                                <button wire:click="deleteAttachment({{ $attachment->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus lampiran ini?"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    Hapus
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Approval History -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Persetujuan</h3>

                @if($project->approvals->isEmpty())
                <div class="text-center py-6 text-gray-500">
                    <p>Belum ada riwayat persetujuan</p>
                </div>
                @else
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($project->approvals as $approval)
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                                {{ \App\Enums\ApprovalActionEnum::tryFrom($approval->action)?->label() ?? $approval->action }}
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
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Attachment Modal -->
    @if($showAttachmentModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">{{ $editingAttachmentId ? 'Edit Lampiran' : 'Tambah Lampiran' }}</h3>
                <button wire:click="closeAttachmentModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="saveAttachment">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                        <input wire:model="attachmentType" type="text"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('attachmentType') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                        <input wire:model="attachmentNumber" type="text"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('attachmentNumber') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Dokumen</label>
                        <input wire:model="attachmentDate" type="date"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('attachmentDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea wire:model="attachmentDescription" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('attachmentDescription') <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(!$editingAttachmentId || ($editingAttachmentId && !$attachmentFile))
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File PDF</label>
                        <input wire:model="attachmentFile" type="file" accept=".pdf" class="mt-1 block w-full">
                        @error('attachmentFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="closeAttachmentModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $editingAttachmentId ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
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