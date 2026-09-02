<div>
    <!-- ✅ TEST MARKER - Pastikan ini muncul -->
    < <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-2">Inisiasi Proyek Baru</h1>
                    <p class="text-gray-500 mb-6">Lengkapi data proyek untuk memulai proses approval</p>

                    <form wire:submit="save" enctype="multipart/form-data" class="space-y-6">
                        <!-- ============================================ -->
                        <!-- SECTION 1: DATA PROYEK -->
                        <!-- ============================================ -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <span
                                    class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                                Data Proyek
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kode Proyek -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Kode Proyek <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input wire:model="projectCode" type="text"
                                            class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                                            readonly>
                                        <button type="button" wire:click="generateProjectCode"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('projectCode') <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Judul Pekerjaan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Judul Pekerjaan <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="title" type="text"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan judul pekerjaan">
                                    @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Client -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Client <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="client" type="text"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan nama client">
                                    @error('client') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Segmen Bisnis -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Segmen Bisnis <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="businessSegmentId"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Segmen Bisnis</option>
                                        @foreach($businessSegments as $segment)
                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
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
                                    <textarea wire:model="location" rows="2"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan lokasi pekerjaan"></textarea>
                                    @error('location') <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- SECTION 2: JANGKA WAKTU & NILAI -->
                        <!-- ============================================ -->
                        <div class="border-b pb-6">
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
                                    <input wire:model="startDate" type="date"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('startDate') <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="endDate" type="date"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('endDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Nilai Kontrak -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Nilai Kontrak <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input wire:model="contractValue" type="number" step="0.01" min="0"
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
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <span
                                    class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold mr-2">3</span>
                                Lampiran
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Upload file pendukung proyek (PDF maks 5MB)</p>

                            <div class="mb-4">
                                <button type="button" wire:click="addAttachment"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Lampiran
                                </button>
                            </div>

                            @if(!empty($attachments))
                            @foreach($attachments as $index => $attachment)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-sm font-medium text-gray-700">Lampiran #{{ $index + 1 }}</span>
                                    <button type="button" wire:click="removeAttachment({{ $index }})"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">File PDF</label>
                                        <input wire:model="attachments.{{ $index }}" type="file" accept=".pdf"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @error('attachments.' . $index) <span
                                            class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                                        <input wire:model="attachmentTypes.{{ $index }}" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Contoh: Kontrak, RAB">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                                        <input wire:model="attachmentNumbers.{{ $index }}" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Nomor dokumen">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Dokumen</label>
                                        <input wire:model="attachmentDates.{{ $index }}" type="date"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <input wire:model="attachmentDescriptions.{{ $index }}" type="text"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Keterangan tambahan">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Belum ada lampiran. Klik tombol "Tambah Lampiran"
                                    untuk menambahkan.</p>
                            </div>
                            @endif
                        </div>

                        <!-- ============================================ -->
                        <!-- BUTTONS -->
                        <!-- ============================================ -->
                        <div class="mt-8 flex flex-wrap gap-3 border-t pt-6">
                            <button type="button" wire:click="resetForm"
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

<!-- Success Modal -->
@if($showSuccessModal && $createdProject)
<div
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="mt-4 text-xl font-bold text-gray-900">Proyek Berhasil Diinisiasi!</h3>

            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Proyek <strong class="text-blue-600">{{ $createdProject->code }}</strong>
                </p>
                <p class="text-sm text-gray-500">
                    {{ $createdProject->title }}
                </p>
            </div>

            <!-- ✅ Informasi Auto Approve -->
            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                <div class="flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-blue-700">✅ Otomatis disetujui oleh Anda</span>
                </div>
                <p class="text-xs text-blue-600 mt-1">
                    Proyek telah otomatis masuk ke approval Dept Head Comercil
                </p>
            </div>

            <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-700">
                    Status: <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Menunggu Review
                        Dept Head Comercil</span>
                </p>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                <button wire:click="goToDashboard"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Ke Dashboard
                </button>
                <button wire:click="goToDetail" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Lihat Detail Proyek
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Flash Messages -->
@if(session()->has('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session()->has('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('error') }}
</div>
@endif
</div>