<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Surat Perintah Tugas (SPT) Baru
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Buat SPT</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Form -->
            <form method="POST" action="{{ route('spts.store') }}" id="sptForm">
                @csrf
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
                                    <input type="text" id="title" name="title" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Contoh: Kunjungan Kerja ke Jakarta">
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Tempat Tujuan *</label>
                                    <input type="text" id="destination" name="destination" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Contoh: Jakarta, Surabaya, dll">
                                    @error('destination')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                                    <input type="date" id="start_date" name="start_date" required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('start_date')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                                    <input type="date" id="end_date" name="end_date" required
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
                                          placeholder="Jelaskan tujuan dan kegiatan yang akan dilaksanakan selama perjalanan dinas"></textarea>
                                @error('purpose')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Informasi tambahan yang perlu diketahui (opsional)"></textarea>
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
                                <!-- Cost items will be added here dynamically -->
                            </div>
                            <div id="totalCost" class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900">Total Estimasi Biaya:</span>
                                    <span class="text-lg font-bold text-blue-600">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('spts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan SPT
                                </button>
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

    let costItemCount = 0;

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
        costItemCount++;
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

    // Add initial cost item
    addCostItem();

    // Add cost item button click
    addCostBtn.addEventListener('click', addCostItem);

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
});
</script>
@endpush