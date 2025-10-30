<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Estimasi Biaya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Estimasi Biaya</h1>
                    <p class="mt-2 text-gray-600">Kelola semua estimasi biaya perjalanan dinas</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openCrudModal('estimated-cost-create-modal')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Estimasi
                    </button>
                    <a href="{{ route('estimated-costs.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Filter Pencarian</h3>
                </div>
                <form method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Deskripsi atau SPT">
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Biaya</label>
                            <select id="type" name="type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Jenis</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="amount_min" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Minimum</label>
                            <input type="number" id="amount_min" name="amount_min" value="{{ request('amount_min') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Rp 0">
                        </div>
                        <div>
                            <label for="amount_max" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Maksimum</label>
                            <input type="number" id="amount_max" name="amount_max" value="{{ request('amount_max') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Rp 0">
                        </div>
                        <div>
                            <label for="spt_status" class="block text-sm font-medium text-gray-700 mb-2">Status SPT</label>
                            <select id="spt_status" name="spt_status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                @foreach($sptStatuses as $status)
                                    <option value="{{ $status }}" {{ request('spt_status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>
                        <a href="{{ route('estimated-costs.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Estimated Costs Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Estimasi Biaya ({{ $estimatedCosts->total() }})</h3>
                </div>

                @if($estimatedCosts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SPT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status SPT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($estimatedCosts as $cost)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="cost_ids[]" value="{{ $cost->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">SPT #{{ $cost->spt_id }}</div>
                                        <div class="text-gray-500">{{ Str::limit($cost->spt->title, 50) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($cost->type === 'transport') bg-blue-100 text-blue-800
                                        @elseif($cost->type === 'daily') bg-green-100 text-green-800
                                        @elseif($cost->type === 'accommodation') bg-yellow-100 text-yellow-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($cost->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $cost->description ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                    Rp {{ number_format($cost->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $cost->spt->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($cost->spt->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($cost->spt->status === 'submitted') bg-blue-100 text-blue-800
                                        @elseif($cost->spt->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($cost->spt->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $cost->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="loadModalContent('estimated-cost-detail-modal', '{{ route('estimated-costs.show', $cost) }}')" class="text-blue-600 hover:text-blue-900" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        @if($cost->spt->status === 'draft' || auth()->user()->isAdmin())
                                        <button onclick="loadModalContent('estimated-cost-edit-modal', '{{ route('estimated-costs.edit', $cost) }}')" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteEstimatedCost({{ $cost->id }})" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $estimatedCosts->links() }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $estimatedCosts->firstItem() }}</span> hingga
                                <span class="font-medium">{{ $estimatedCosts->lastItem() }}</span> dari
                                <span class="font-medium">{{ $estimatedCosts->total() }}</span> hasil
                            </p>
                        </div>
                        <div>
                            {{ $estimatedCosts->links() }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada estimasi biaya</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan estimasi biaya baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('estimated-costs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Estimasi
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-crud-modal id="estimated-cost-create-modal" title="Tambah Estimasi Biaya Baru" size="lg">
        <form class="crud-form" action="{{ route('estimated-costs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="spt_id" class="block text-sm font-medium text-gray-700">SPT *</label>
                    <select id="spt_id" name="spt_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Pilih SPT</option>
                        @foreach($spts as $spt)
                            <option value="{{ $spt->id }}">
                                SPT #{{ $spt->id }} - {{ Str::limit($spt->title, 50) }} ({{ $spt->user->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis Biaya *</label>
                    <select id="type" name="type" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Pilih Jenis Biaya</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp) *</label>
                    <input type="number" id="amount" name="amount" required min="0" step="0.01"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="0">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Deskripsi biaya (opsional)"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeCrudModal('estimated-cost-create-modal')"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </x-crud-modal>

    <x-crud-modal id="estimated-cost-edit-modal" title="Edit Estimasi Biaya" size="lg">
        <!-- Content will be loaded via AJAX -->
    </x-crud-modal>

    <x-crud-modal id="estimated-cost-detail-modal" title="Detail Estimasi Biaya" size="lg">
        <!-- Content will be loaded via AJAX -->
    </x-crud-modal>

    @push('scripts')
    <script src="{{ asset('js/crud-modal.js') }}"></script>
    <script>
    // Delete estimated cost function
    function deleteEstimatedCost(costId) {
        if (confirm('Apakah Anda yakin ingin menghapus estimasi biaya ini?')) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/estimated-costs/${costId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    crudModal.showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    crudModal.showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                crudModal.showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            });
        }
    }
    </script>
    @endpush
</x-app-layout>