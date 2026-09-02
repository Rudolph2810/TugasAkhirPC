<div>
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Proyek</h3>

    <div class="space-y-4">
        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Deskripsi Proyek <span class="text-red-500">*</span>
            </label>
            <textarea wire:model="description" rows="4"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('description') ? 'border-red-500' : '' }}"
                placeholder="Deskripsikan proyek secara detail"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Scope -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Lingkup Pekerjaan <span class="text-red-500">*</span>
            </label>
            <textarea wire:model="scope" rows="4"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('scope') ? 'border-red-500' : '' }}"
                placeholder="Jelaskan lingkup pekerjaan yang akan dilakukan"></textarea>
            @error('scope') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Risk & Issue -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Risiko & Isu</label>
            <textarea wire:model="riskIssue" rows="3"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Identifikasi risiko dan isu yang mungkin terjadi"></textarea>
            @error('riskIssue') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Deliverables -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Deliverables</label>
            <textarea wire:model="deliverables" rows="3"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Sebutkan deliverables yang akan diserahkan"></textarea>
            @error('deliverables') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div>