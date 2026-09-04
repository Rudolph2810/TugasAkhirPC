@extends('layouts.app')

@section('title', 'Inisiasi Proyek')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Inisiasi Proyek Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi data proyek untuk memulai proses approval</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('project.initiate.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- ============================================ -->
                    <!-- SECTION 1: DATA PROYEK -->
                    <!-- ============================================ -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                            Data Proyek
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Proyek -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Proyek <span
                                        class="text-red-500">*</span></label>
                                <select wire:model.live="projectType"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Jenis Proyek</option>
                                    @foreach($projectTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}
                                        ({{ $type->code }})</option>
                                    @endforeach
                                </select>
                                @error('projectType') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kode Segmen -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Segmen Proyek <span
                                        class="text-red-500">*</span></label>
                                <select wire:model.live="segmentCode"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Segmen</option>
                                    @foreach($segmentCodes as $seg)
                                    <option value="{{ $seg->id }}">{{ $seg->name }}
                                        ({{ $seg->code }})</option>
                                    @endforeach
                                </select>
                                @error('kodeSegmen') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <!-- Kode Proyek -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Kode Proyek <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input name="projectCode" type="text" value="{{ $projectCode }}"
                                        class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                        readonly>
                                    <a href="{{ route('project.initiate') }}"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </a>
                                </div>
                                @error('projectCode') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Judul Pekerjaan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Judul Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input name="title" type="text" value="{{ old('title') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan judul pekerjaan">
                                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Client -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Client <span class="text-red-500">*</span>
                                </label>
                                <input name="client" type="text" value="{{ old('client') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan nama client">
                                @error('client') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Segmen Bisnis -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Segmen Bisnis <span class="text-red-500">*</span>
                                </label>
                                <select name="businessSegmentId"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Segmen Bisnis</option>
                                    @foreach($businessSegments as $segment)
                                    <option value="{{ $segment->id }}"
                                        {{ old('businessSegmentId') == $segment->id ? 'selected' : '' }}>
                                        {{ $segment->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('businessSegmentId') <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Lokasi Pekerjaan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Lokasi Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="location" rows="2"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan lokasi pekerjaan">{{ old('location') }}</textarea>
                                @error('location') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECTION 2: JANGKA WAKTU & NILAI -->
                    <!-- ============================================ -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold mr-2">2</span>
                            Jangka Waktu & Nilai
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input name="startDate" type="date" value="{{ old('startDate') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('startDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input name="endDate" type="date" value="{{ old('endDate') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('endDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Nilai Kontrak -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Nilai Kontrak <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input name="contractValue" type="number" step="0.01" min="0"
                                        value="{{ old('contractValue') }}"
                                        class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="0">
                                </div>
                                @error('contractValue') <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECTION 3: LAMPIRAN -->
                    <!-- ============================================ -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold mr-2">3</span>
                            Lampiran
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">Upload file pendukung proyek (PDF maks 5MB)</p>

                        <div id="attachments-container">
                            <div class="attachment-item bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">File PDF</label>
                                        <input name="attachments[]" type="file" accept=".pdf"
                                            class="mt-1 block w-full text-sm text-gray-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                                        <input name="attachmentTypes[]" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                            placeholder="Kontrak, RAB, dll">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                                        <input name="attachmentNumbers[]" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                            placeholder="Nomor dokumen">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Dokumen</label>
                                        <input name="attachmentDates[]" type="date"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input name="attachmentDescriptions[]" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                            placeholder="Keterangan tambahan">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addAttachment()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Lampiran
                        </button>
                    </div>

                    <!-- ============================================ -->
                    <!-- BUTTONS -->
                    <!-- ============================================ -->
                    <div class="mt-8 flex flex-wrap gap-3 border-t pt-6">
                        <button type="reset"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Reset
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan & Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function addAttachment() {
    const container = document.getElementById('attachments-container');
    const newItem = document.createElement('div');
    newItem.className = 'attachment-item bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200';
    newItem.innerHTML = `
        <div class="flex justify-end mb-2">
            <button type="button" onclick="this.closest('.attachment-item').remove()" class="text-red-600 hover:text-red-800 text-sm">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">File PDF</label>
                <input name="attachments[]" type="file" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                <input name="attachmentTypes[]" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Kontrak, RAB, dll">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                <input name="attachmentNumbers[]" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Nomor dokumen">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Dokumen</label>
                <input name="attachmentDates[]" type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <input name="attachmentDescriptions[]" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Keterangan tambahan">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}
</script>
@endpush
@endsection