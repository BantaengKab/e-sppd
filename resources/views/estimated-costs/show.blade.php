@if(request()->ajax() || request()->header('X-Requested-With') == 'XMLHttpRequest')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Estimasi</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Jenis Biaya:</span>
                        <p>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($estimatedCost->type === 'transport') bg-blue-100 text-blue-800
                                @elseif($estimatedCost->type === 'daily') bg-green-100 text-green-800
                                @elseif($estimatedCost->type === 'accommodation') bg-yellow-100 text-yellow-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($estimatedCost->type) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Jumlah:</span>
                        <p class="font-medium text-lg">Rp {{ number_format($estimatedCost->amount, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Deskripsi:</span>
                        <p class="font-medium">{{ $estimatedCost->description ?: '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Dibuat:</span>
                        <p class="font-medium">{{ $estimatedCost->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi SPT</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">SPT:</span>
                        <p class="font-medium">SPT #{{ $estimatedCost->spt_id }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Judul:</span>
                        <p class="font-medium">{{ $estimatedCost->spt->title }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Pemohon:</span>
                        <p class="font-medium">{{ $estimatedCost->spt->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status SPT:</span>
                        <p>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($estimatedCost->spt->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($estimatedCost->spt->status === 'submitted') bg-blue-100 text-blue-800
                                @elseif($estimatedCost->spt->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($estimatedCost->spt->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPT Details -->
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Detail Perjalanan Dinas</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-sm text-gray-500">Tujuan:</span>
                    <p class="font-medium">{{ $estimatedCost->spt->destination }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tujuan Perjalanan:</span>
                    <p class="font-medium">{{ $estimatedCost->spt->purpose }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tanggal Mulai:</span>
                    <p class="font-medium">{{ $estimatedCost->spt->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tanggal Selesai:</span>
                    <p class="font-medium">{{ $estimatedCost->spt->end_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Other Cost Estimates for this SPT -->
        @if($estimatedCost->spt->estimatedCosts->count() > 1)
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Estimasi Biaya Lain untuk SPT Ini</h4>
            <div class="space-y-2">
                @foreach($estimatedCost->spt->estimatedCosts as $cost)
                    @if($cost->id !== $estimatedCost->id)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($cost->type === 'transport') bg-blue-100 text-blue-800
                                @elseif($cost->type === 'daily') bg-green-100 text-green-800
                                @elseif($cost->type === 'accommodation') bg-yellow-100 text-yellow-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($cost->type) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-600">{{ $cost->description ?: 'Tanpa deskripsi' }}</span>
                        </div>
                        <div class="font-medium">Rp {{ number_format($cost->amount, 2, ',', '.') }}</div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Total Summary -->
        <div class="border-t pt-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-blue-600 font-medium">Total Estimasi Biaya SPT:</span>
                    </div>
                    <div class="text-xl font-bold text-blue-600">
                        Rp {{ number_format($estimatedCost->spt->estimatedCosts->sum('amount'), 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeCrudModal('estimated-cost-detail-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Tutup
            </button>
            @if($estimatedCost->spt->status === 'draft' || auth()->user()->isAdmin())
            <button onclick="loadModalContent('estimated-cost-edit-modal', '{{ route('estimated-costs.edit', $estimatedCost->id) }}')"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Edit Estimasi
            </button>
            @endif
        </div>
    </div>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Estimasi Biaya
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Detail Estimasi Biaya</h3>
                        <div class="flex space-x-2">
                            @if($estimatedCost->spt->status === 'draft' || auth()->user()->isAdmin())
                            <a href="{{ route('estimated-costs.edit', $estimatedCost->id) }}"
                               class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Edit
                            </a>
                            @endif
                            <a href="{{ route('estimated-costs.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Include the same content as above -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Estimasi</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm text-gray-500">Jenis Biaya:</span>
                                            <p>
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($estimatedCost->type === 'transport') bg-blue-100 text-blue-800
                                                    @elseif($estimatedCost->type === 'daily') bg-green-100 text-green-800
                                                    @elseif($estimatedCost->type === 'accommodation') bg-yellow-100 text-yellow-800
                                                    @else bg-purple-100 text-purple-800
                                                    @endif">
                                                    {{ ucfirst($estimatedCost->type) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Jumlah:</span>
                                            <p class="font-medium text-lg">Rp {{ number_format($estimatedCost->amount, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Deskripsi:</span>
                                            <p class="font-medium">{{ $estimatedCost->description ?: '-' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Dibuat:</span>
                                            <p class="font-medium">{{ $estimatedCost->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi SPT</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm text-gray-500">SPT:</span>
                                            <p class="font-medium">SPT #{{ $estimatedCost->spt_id }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Judul:</span>
                                            <p class="font-medium">{{ $estimatedCost->spt->title }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Pemohon:</span>
                                            <p class="font-medium">{{ $estimatedCost->spt->user->name }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Status SPT:</span>
                                            <p>
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($estimatedCost->spt->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($estimatedCost->spt->status === 'submitted') bg-blue-100 text-blue-800
                                                    @elseif($estimatedCost->spt->status === 'approved') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($estimatedCost->spt->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SPT Details -->
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Detail Perjalanan Dinas</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <span class="text-sm text-gray-500">Tujuan:</span>
                                        <p class="font-medium">{{ $estimatedCost->spt->destination }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Tujuan Perjalanan:</span>
                                        <p class="font-medium">{{ $estimatedCost->spt->purpose }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Tanggal Mulai:</span>
                                        <p class="font-medium">{{ $estimatedCost->spt->start_date->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">Tanggal Selesai:</span>
                                        <p class="font-medium">{{ $estimatedCost->spt->end_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Cost Estimates for this SPT -->
                            @if($estimatedCost->spt->estimatedCosts->count() > 1)
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Estimasi Biaya Lain untuk SPT Ini</h4>
                                <div class="space-y-2">
                                    @foreach($estimatedCost->spt->estimatedCosts as $cost)
                                        @if($cost->id !== $estimatedCost->id)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($cost->type === 'transport') bg-blue-100 text-blue-800
                                                    @elseif($cost->type === 'daily') bg-green-100 text-green-800
                                                    @elseif($cost->type === 'accommodation') bg-yellow-100 text-yellow-800
                                                    @else bg-purple-100 text-purple-800
                                                    @endif">
                                                    {{ ucfirst($cost->type) }}
                                                </span>
                                                <span class="ml-2 text-sm text-gray-600">{{ $cost->description ?: 'Tanpa deskripsi' }}</span>
                                            </div>
                                            <div class="font-medium">Rp {{ number_format($cost->amount, 2, ',', '.') }}</div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Total Summary -->
                            <div class="border-t pt-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-sm text-blue-600 font-medium">Total Estimasi Biaya SPT:</span>
                                        </div>
                                        <div class="text-xl font-bold text-blue-600">
                                            Rp {{ number_format($estimatedCost->spt->estimatedCosts->sum('amount'), 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif