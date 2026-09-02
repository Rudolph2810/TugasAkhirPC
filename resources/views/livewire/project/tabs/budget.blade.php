<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Budget Proyek</h3>
        <button type="button" wire:click="addBudget"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Budget
        </button>
    </div>

    @if(empty($budgets))
    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-2 text-sm text-gray-500">Belum ada budget. Klik "Tambah Budget" untuk menambahkan.</p>
    </div>
    @else
    @foreach($budgets as $index => $budget)
    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
        <div class="flex justify-between items-start mb-3">
            <span class="text-sm font-medium text-gray-700">Budget #{{ $index + 1 }}</span>
            <button type="button" wire:click="removeBudget({{ $index }})"
                class="text-red-600 hover:text-red-800 text-sm">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Item</label>
                <input wire:model="budgetItems.{{ $index }}" type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Nama item">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input wire:model="budgetAmounts.{{ $index }}" type="number" step="0.01"
                        class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input wire:model="budgetDescriptions.{{ $index }}" type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Deskripsi item">
            </div>
        </div>
    </div>
    @endforeach

    <!-- Total Budget -->
    <div class="bg-blue-50 rounded-lg p-4 mt-4">
        <div class="flex justify-between items-center">
            <span class="font-semibold text-gray-700">Total Budget</span>
            <span class="text-lg font-bold text-blue-600">
                Rp {{ number_format(array_sum(array_filter($budgetAmounts)), 0, ',', '.') }}
            </span>
        </div>
    </div>
    @endif
</div>