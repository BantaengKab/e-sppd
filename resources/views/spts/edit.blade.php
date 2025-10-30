<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Surat Perintah Tugas (SPT)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('spts.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Daftar SPT</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('spts.show', $spt->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                SPT #{{ $spt->id }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit SPT</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Status Alert -->
            @if($spt->status === 'draft')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Status: Draft</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>SPT ini masih dalam status draft dan dapat diedit. Setelah disimpan, status akan tetap draft hingga Anda mengirimkannya untuk persetujuan.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('spts.update', $spt->id) }}" id="sptForm">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Perjalanan Dinas *</label>
                                    <input type="text" id="title" name="title" value="{{ old('title', $spt->title) }}" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Contoh: Kunjungan Kerja ke Jakarta">
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Tempat Tujuan *</label>
                                    <input type="text" id="destination" name="destination" value="{{ old('destination', $spt->destination) }}" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Contoh: Jakarta, Surabaya, dll">
                                    @error('destination')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $spt->start_date->format('Y-m-d')) }}" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('start_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $spt->end_date->format('Y-m-d')) }}" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('end_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">Maksud Perjalanan Dinas *</label>
                                <textarea id="purpose" name="purpose" rows="4" required
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Jelaskan tujuan dan kegiatan yang akan dilaksanakan selama perjalanan dinas">{{ old('purpose', $spt->purpose) }}</textarea>
                                @error('purpose')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Informasi tambahan yang perlu diketahui (opsional)">{{ old('notes', $spt->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Biaya -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Estimasi Biaya</h3>
                            <button type="button" id="addCostBtn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Biaya
                            </button>
                        </div>
                        <div class="p-6">
                            <div id="costsList" class="space-y-4">
                                @forelse($spt->estimatedCosts as $index => $cost)
                                <div class="cost-item bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Biaya *</label>
                                            <select name="cost_types[]" class="cost-type block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                                <option value="">Pilih Jenis</option>
                                                <option value="transport" {{ $cost->type === 'transport' ? 'selected' : '' }}>Transportasi</option>
                                                <option value="daily" {{ $cost->type === 'daily' ? 'selected' : '' }}>Uang Harian</option>
                                                <option value="accommodation" {{ $cost->type === 'accommodation' ? 'selected' : '' }}>Akomodasi</option>
                                                <option value="other" {{ $cost->type === 'other' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                                            <input type="number" name="cost_amounts[]" class="cost-amount block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   value="{{ $cost->amount }}" min="0" step="1000" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                            <input type="text" name="cost_descriptions[]" class="cost-description block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   value="{{ $cost->description }}" placeholder="Contoh: Tiket pesawat, hotel, dll">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-cost-btn inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <!-- No existing costs, will be populated by JavaScript -->
                                @endforelse
                            </div>
                            <div id="totalCost" class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900">Total Estimasi Biaya:</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($spt->estimatedCosts->sum('amount'), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <span>Dibuat: {{ $spt->created_at->format('d/m/Y H:i') }}</span>
                                    @if($spt->updated_at->gt($spt->created_at))
                                        <span class="ml-4">Diperbarui: {{ $spt->updated_at->format('d/m/Y H:i') }}</span>
                                    @endif
                                </div>
                                <div class="flex space-x-4">
                                    <a href="{{ route('spts.show', $spt->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Kembali
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cost Item Template (Hidden) -->
    <template id="costItemTemplate">
        <div class="cost-item bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Biaya *</label>
                    <select name="cost_types[]" class="cost-type block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Pilih Jenis</option>
                        <option value="transport">Transportasi</option>
                        <option value="daily">Uang Harian</option>
                        <option value="accommodation">Akomodasi</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                    <input type="number" name="cost_amounts[]" class="cost-amount block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" min="0" step="1000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <input type="text" name="cost_descriptions[]" class="cost-description block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: Tiket pesawat, hotel, dll">
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-cost-btn inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </template>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const costsList = document.getElementById('costsList');
    const costItemTemplate = document.getElementById('costItemTemplate');
    const addCostBtn = document.getElementById('addCostBtn');
    const totalCostElement = document.querySelector('#totalCost span:last-child');

    function addCostItem() {
        const templateClone = costItemTemplate.content.cloneNode(true);
        const costItem = templateClone.querySelector('.cost-item');

        // Add unique IDs if needed
        const typeSelect = costItem.querySelector('.cost-type');
        const amountInput = costItem.querySelector('.cost-amount');
        const descriptionInput = costItem.querySelector('.cost-description');
        const removeBtn = costItem.querySelector('.remove-cost-btn');

        // Add event listeners
        amountInput.addEventListener('input', updateTotalCost);

        removeBtn.addEventListener('click', function() {
            costItem.remove();
            updateTotalCost();
        });

        costsList.appendChild(templateClone);
    }

    function updateTotalCost() {
        let total = 0;
        const amountInputs = document.querySelectorAll('.cost-amount');

        amountInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        totalCostElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Add cost item button click
    addCostBtn.addEventListener('click', addCostItem);

    // Initialize existing cost items
    document.querySelectorAll('.cost-amount').forEach(input => {
        input.addEventListener('input', updateTotalCost);
    });

    // Set up initial total
    updateTotalCost();

    // Date validation
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
    });

    endDate.addEventListener('change', function() {
        if (this.value && this.value < startDate.value) {
            startDate.value = this.value;
        }
    });

    // Form submission confirmation
    const form = document.getElementById('sptForm');
    form.addEventListener('submit', function(e) {
        const amountInputs = document.querySelectorAll('.cost-amount');
        let hasValidCost = false;

        amountInputs.forEach(input => {
            if (input.value && parseFloat(input.value) > 0) {
                hasValidCost = true;
            }
        });

        if (!hasValidCost) {
            e.preventDefault();
            alert('Harap tambahkan setidaknya satu estimasi biaya.');
            return false;
        }
    });
});
</script>
@endpush