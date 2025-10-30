<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Persetujuan SPT
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
                            <a href="{{ route('approvals.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Persetujuan</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Review SPT #{{ $spt->id }}</span>
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
                                <h1 class="text-2xl font-bold text-gray-900">Review SPT #{{ $spt->id }}</h1>
                                <p class="mt-1 text-sm text-gray-500">
                                    Diajukan oleh {{ $spt->user->name }} pada {{ $spt->created_at->format('d F Y H:i') }}
                                </p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('approvals.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SPT Information -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi SPT</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Perjalanan Dinas</label>
                                <p class="text-gray-900">{{ $spt->title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Tujuan</label>
                                <p class="text-gray-900">{{ $spt->destination }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <p class="text-gray-900">{{ $spt->start_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                <p class="text-gray-900">{{ $spt->end_date->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maksud Perjalanan Dinas</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $spt->purpose }}</p>
                        </div>
                        @if($spt->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $spt->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Information Pemohon -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Pemohon</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <p class="text-gray-900">{{ $spt->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                                <p class="text-gray-900">{{ $spt->user->nip }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                                <p class="text-gray-900">{{ $spt->user->jabatan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                                <p class="text-gray-900">{{ $spt->user->unit_kerja }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Biaya -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Rincian Estimasi Biaya</h3>
                    </div>
                    <div class="p-6">
                        @if($spt->estimatedCosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Biaya</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($spt->estimatedCosts as $cost)
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
                                        <td colspan="2" class="px-6 py-4 text-sm font-medium text-gray-900">Total Estimasi Biaya</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-blue-600 text-right">
                                            Rp {{ number_format($spt->total_estimated_cost, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-4">Tidak ada estimasi biaya yang ditambahkan.</p>
                        @endif
                    </div>
                </div>

                <!-- Status Persetujuan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Status Persetujuan</h3>
                    </div>
                    <div class="p-6">
                        @if($spt->approvals->count() > 0)
                        <div class="space-y-4">
                            @foreach($spt->approvals as $approval)
                            <div class="border rounded-lg p-4 @if($approval->status === 'approved') border-green-200 bg-green-50 @elseif($approval->status === 'rejected') border-red-200 bg-red-50 @else border-yellow-200 bg-yellow-50 @endif">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($approval->status === 'approved')
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            @elseif($approval->status === 'rejected')
                                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $approval->approver->name }} - {{ $approval->approver->role }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                @switch($approval->stage)
                                                    @case('supervisor')
                                                        Supervisor
                                                        @break
                                                    @case('finance')
                                                        Verifikator Keuangan
                                                        @break
                                                    @case('final')
                                                        Pejabat Berwenang
                                                        @break
                                                @endswitch
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium">
                                            @if($approval->status === 'approved')
                                                <span class="text-green-600">Disetujui</span>
                                            @elseif($approval->status === 'rejected')
                                                <span class="text-red-600">Ditolak</span>
                                            @else
                                                <span class="text-yellow-600">Menunggu</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if($approval->approved_at)
                                                {{ $approval->approved_at->format('d F Y H:i') }}
                                            @else
                                                Menunggu persetujuan
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($approval->comment)
                                <div class="mt-3 pt-3 border-t @if($approval->status === 'approved') border-green-200 @elseif($approval->status === 'rejected') border-red-200 @else border-yellow-200 @endif">
                                    <p class="text-sm text-gray-700">
                                        <strong>Catatan:</strong> {{ $approval->comment }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-4">Belum ada proses persetujuan.</p>
                        @endif
                    </div>
                </div>

                <!-- Form Approval -->
                @if($spt->status === 'submitted')
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Beri Persetujuan</h3>
                    </div>
                    <form method="POST" action="{{ route('approvals.approve', $spt) }}" class="p-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Keputusan *</label>
                                <select id="status" name="status" required
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Keputusan</option>
                                    <option value="approved">Setuju</option>
                                    <option value="rejected">Tolak</option>
                                </select>
                            </div>
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Catatan/Komentar</label>
                                <textarea id="comment" name="comment" rows="4"
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Tambahkan catatan atau komentar terkait persetujuan ini"></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="submit" name="status" value="rejected"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak SPT
                                </button>
                                <button type="submit" name="status" value="approved"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setujui SPT
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const rejectBtn = document.querySelector('button[name="status"][value="rejected"]');
        const approveBtn = document.querySelector('button[name="status"][value="approved"]');

        // Auto-submit when clicking approval buttons
        rejectBtn.addEventListener('click', function() {
            statusSelect.value = 'rejected';
            // Form will submit automatically
        });

        approveBtn.addEventListener('click', function() {
            statusSelect.value = 'approved';
            // Form will submit automatically
        });

        // Status change handler
        statusSelect.addEventListener('change', function() {
            const commentField = document.getElementById('comment');
            if (this.value === 'rejected') {
                commentField.required = true;
                commentField.placeholder = 'Harap cantumkan alasan penolakan...';
            } else {
                commentField.required = false;
                commentField.placeholder = 'Tambahkan catatan atau komentar terkait persetujuan ini';
            }
        });
    });
    </script>
    @endpush
</x-app-layout>