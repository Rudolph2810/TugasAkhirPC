<div>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Manajemen RKAP
                <span class="text-sm font-normal text-gray-500 ml-4">
                    {{ $project->code }} - {{ $project->title }}
                </span>
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Import Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Import Data RKAP</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input wire:model="file" type="file" accept=".xlsx,.xls" class="hidden" id="fileInput">
                        <label for="fileInput" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600">
                                <span class="font-medium text-blue-600">Klik untuk upload</span> atau drag & drop
                            </p>
                            <p class="text-xs text-gray-500">Excel (.xlsx, .xls) maks 5MB</p>
                        </label>
                        @if($file)
                        <p class="mt-2 text-sm text-gray-600">File: {{ $file->getClientOriginalName() }}</p>
                        @endif
                    </div>
                    <!-- {{-- PERBAIKAN: Gunakan @error --}} -->
                    @if(isset($errors) && is_object($errors) && $errors->has('file'))
                    <span class="text-red-600 text-sm">{{ $errors->first('file') }}</span>
                    @endif
                </div>

                <div class="flex flex-col justify-center space-y-3">
                    <button wire:click="downloadTemplate"
                        class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Template
                    </button>
                    <button wire:click="importRkap"
                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import Data
                    </button>
                    <button wire:click="downloadRkap"
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export RKAP
                    </button>
                </div>
            </div>

            @if($showErrors && !empty($errors))
            <div class="mt-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="text-red-800 font-medium mb-2">Error pada import:</h4>
                    @foreach($errors as $errorGroup)
                    @foreach($errorGroup as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                    @endforeach
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- RKAP Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-700">Data RKAP</h3>
                @if($rkapItems->isNotEmpty())
                <button wire:click="clearRkap" wire:confirm="Apakah Anda yakin ingin menghapus semua data RKAP?"
                    class="text-red-600 hover:text-red-800 text-sm">
                    Hapus Semua
                </button>
                @endif
            </div>

            @if($rkapItems->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                <p class="mt-2">Belum ada data RKAP. Silahkan import data.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Anggaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Rencana
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai RKAP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rkapItems as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $item->no }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tahun }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->kode_anggaran }}</td>
                            <td class="px-6 py-4 text-sm">{{ Str::limit($item->detail_rencana, 50) }}</td>
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($item->nilai_rkap, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button wire:click="deleteRkapItem({{ $item->id }})" wire:confirm="Apakah Anda yakin?"
                                    class="text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right font-medium">Total</td>
                            <td class="px-6 py-3 font-medium">
                                Rp {{ number_format($rkapItems->sum('nilai_rkap'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

        <!-- Flash Messages -->
        @if(session('message'))
        <div
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('message') }}
        </div>
        @endif

        @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
        @endif

        @if(session('warning'))
        <div
            class="fixed bottom-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('warning') }}
        </div>
        @endif
    </div>
</div>