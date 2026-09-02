<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Rencana Penagihan</h3>
        <button type="button" wire:click="addBillingPlan"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Termin
        </button>
    </div>

    @if(empty($billingPlans))
    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>
        <p class="mt-2 text-sm text-gray-500">Belum ada termin penagihan. Klik "Tambah Termin" untuk menambahkan.</p>
    </div>
    @else
    @foreach($billingPlans as $index => $billing)
    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
        <div class="flex justify-between items-start mb-3">
            <span class="text-sm font-medium text-gray-700">Termin #{{ $index + 1 }}</span>
            <button type="button" wire:click="removeBillingPlan({{ $index }})"
                class="text-red-600 hover:text-red-800 text-sm">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Termin</label>
                <input wire:model="billingTermins.{{ $index }}" type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Termin 1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Persentase (%)</label>
                <input wire:model="billingPercentages.{{ $index }}" type="number" step="0.01" min="0" max="100"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Rencana Tagih</label>
                <input wire:model="billingDates.{{ $index }}" type="date"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input wire:model="billingAmounts.{{ $index }}" type="number" step="0.01"
                        class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Total Billing -->
    <div class="bg-green-50 rounded-lg p-4 mt-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="font-semibold text-gray-700">Total Termin</span>
                <span class="block text-lg font-bold text-gray-800">{{ count($billingPlans) }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Total Nominal</span>
                <span class="block text-lg font-bold text-green-600">
                    Rp {{ number_format(array_sum(array_filter($billingAmounts)), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
    @endif
</div>