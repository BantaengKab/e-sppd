<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Surat Perjalanan Dinas (SPPD)
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
                            <a href="{{ route('spts.show', $sppd->spt->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                SPT #{{ $sppd->spt->id }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">SPPD {{ $sppd->number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="space-y-6">
                <!-- Header Actions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">SPPD {{ $sppd->number }}</h1>
                                <p class="mt-1 text-sm text-gray-500">
                                    Diterbitkan pada {{ $sppd->issue_date->format('d F Y') }}
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('sppds.download', $sppd->id) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                    Download PDF
                                </a>
                                @if($sppd->status === 'issued' && $sppd->spt->user_id === auth()->id())
                                <button onclick="openRealizationModal()" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Tambah Realisasi
                                </button>
                                @endif
                                <a href="{{ route('spts.show', $sppd->spt->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Kembali ke SPT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($sppd->status === 'issued')
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                @elseif($sppd->status === 'completed')
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">Status:
                                    @if($sppd->status === 'issued')
                                        <span class="text-blue-600">Diterbitkan</span>
                                    @elseif($sppd->status === 'completed')
                                        <span class="text-green-600">Selesai</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500">
                                    @if($sppd->status === 'issued')
                                        SPPD telah diterbitkan dan siap untuk dilaksanakan
                                    @elseif($sppd->status === 'completed')
                                        Perjalanan dinas telah selesai dan semua biaya telah direalisasi
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SPT Information -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Perjalanan Dinas</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Perjalanan Dinas</label>
                                <p class="text-gray-900">{{ $sppd->spt->title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SPPD</label>
                                <p class="text-gray-900 font-mono">{{ $sppd->number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Tujuan</label>
                                <p class="text-gray-900">{{ $sppd->spt->destination }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penerbitan</label>
                                <p class="text-gray-900">{{ $sppd->issue_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <p class="text-gray-900">{{ $sppd->spt->start_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                <p class="text-gray-900">{{ $sppd->spt->end_date->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maksud Perjalanan Dinas</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $sppd->spt->purpose }}</p>
                        </div>
                        @if($sppd->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan SPPD</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $sppd->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Information Pegawai -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Pegawai</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <p class="text-gray-900">{{ $sppd->spt->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                                <p class="text-gray-900 font-mono">{{ $sppd->spt->user->nip }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                                <p class="text-gray-900">{{ $sppd->spt->user->jabatan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                                <p class="text-gray-900">{{ $sppd->spt->user->unit_kerja }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Biaya yang Disetujui -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Rincian Biaya yang Disetujui</h3>
                    </div>
                    <div class="p-6">
                        @if($sppd->spt->estimatedCosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Biaya</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sppd->spt->estimatedCosts as $cost)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @switch($cost->type)
                                                @case('transport')
                                                    Transportasi
                                                    @break
                                                @case('daily')
                                                    Uang Harian
                                                    @break
                                                @case('accommodation')
                                                    Akomodasi
                                                    @break
                                                @case('other')
                                                    Lainnya
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $cost->description ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">
                                            Rp {{ number_format($cost->amount, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-sm font-medium text-gray-900">Total yang Disetujui</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-blue-600 text-right">
                                            Rp {{ number_format($sppd->spt->total_estimated_cost, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-4">Tidak ada rincian biaya yang disetujui.</p>
                        @endif
                    </div>
                </div>

                <!-- Realisasi Biaya -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Realisasi Biaya</h3>
                        @if($sppd->status === 'issued' && $sppd->spt->user_id === auth()->id())
                        <button onclick="openRealizationModal()" class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Realisasi
                        </button>
                        @endif
                    </div>
                    <div class="p-6">
                        @if($sppd->realizations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Biaya</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi (Rp)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sppd->realizations as $realization)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @switch($realization->type)
                                                @case('transport')
                                                    Transportasi
                                                    @break
                                                @case('daily')
                                                    Uang Harian
                                                    @break
                                                @case('accommodation')
                                                    Akomodasi
                                                    @break
                                                @case('other')
                                                    Lainnya
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $realization->description ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">
                                            Rp {{ number_format($realization->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($realization->file_path)
                                                <a href="{{ asset('storage/' . $realization->file_path) }}" target="_blank"
                                                   class="text-blue-600 hover:text-blue-800 underline">
                                                    Lihat Bukti
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $realization->notes ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-sm font-medium text-gray-900">Total Realisasi</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600 text-right">
                                            Rp {{ number_format($sppd->getTotalRealizedCostAttribute(), 2, ',', '.') }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada realisasi biaya</h3>
                            <p class="mt-1 text-sm text-gray-500">Tambahkan realisasi biaya setelah perjalanan selesai.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Realisasi -->
    <div id="realizationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Realisasi Biaya</h3>
                <form method="POST" action="{{ route('realizations.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="sppd_id" value="{{ $sppd->id }}">

                    <div class="space-y-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Biaya *</label>
                            <select id="type" name="type" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Pilih Jenis</option>
                                <option value="transport">Transportasi</option>
                                <option value="daily">Uang Harian</option>
                                <option value="accommodation">Akomodasi</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                            <input type="number" id="amount" name="amount" required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   min="0" step="1000">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <input type="text" id="description" name="description"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="file_path" class="block text-sm font-medium text-gray-700 mb-2">Bukti Pengeluaran</label>
                            <input type="file" id="file_path" name="file_path" accept=".pdf,.jpg,.jpeg,.png"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeRealizationModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function openRealizationModal() {
        document.getElementById('realizationModal').classList.remove('hidden');
    }

    function closeRealizationModal() {
        document.getElementById('realizationModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('realizationModal');
        if (event.target == modal) {
            closeRealizationModal();
        }
    }

    // Auto-format currency input
    document.getElementById('amount')?.addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            this.value = parseInt(value).toLocaleString('id-ID');
        }
    });
    </script>
    @endpush
</x-app-layout>