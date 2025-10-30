<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Surat Perintah Tugas (SPT)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- SPT Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $spt->title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">SPT #{{ $spt->id }}</p>
                    </div>
                    <div class="flex space-x-2">
                        @switch($spt->status)
                            @case('draft')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                @break
                            @case('submitted')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Diajukan</span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Disetujui</span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Ditolak</span>
                                @break
                        @endswitch
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perjalanan</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tujuan:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->destination }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Mulai:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->start_date->format('d F Y') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Selesai:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->end_date->format('d F Y') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Durasi:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->start_date->diffInDays($spt->end_date) + 1 }} hari</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemohon</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Nama:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->user->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">NIP:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->user->nip }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Jabatan:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->user->jabatan }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Unit Kerja:</dt>
                                    <dd class="text-sm text-gray-900">{{ $spt->user->unit_kerja }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Maksud Perjalanan Dinas</h3>
                        <p class="text-gray-700">{{ $spt->purpose }}</p>
                    </div>

                    @if($spt->notes)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Catatan Tambahan</h3>
                        <p class="text-gray-700">{{ $spt->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estimasi Biaya -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estimasi Biaya</h3>
                </div>
                <div class="p-6">
                    @if($spt->estimatedCosts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($spt->estimatedCosts as $cost)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($cost->type) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $cost->description ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            Rp {{ number_format($cost->amount, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                                            Rp {{ number_format($spt->total_estimated_cost, 2, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada estimasi biaya</p>
                    @endif
                </div>
            </div>

            <!-- Status Persetujuan -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status Persetujuan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(['supervisor' => 'Supervisor', 'finance' => 'Finance', 'verifikator' => 'Verifikator'] as $stage => $stageName)
                            @php
                                $approval = $spt->approvals->where('stage', $stage)->first();
                            @endphp
                            <div class="flex items-center justify-between p-4 border rounded-lg @if($approval) @if($approval->status === 'approved') border-green-200 bg-green-50 @elseif($approval->status === 'rejected') border-red-200 bg-red-50 @else border-yellow-200 bg-yellow-50 @endif @else border-gray-200 @endif">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($approval)
                                            @if($approval->status === 'approved')
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($approval->status === 'rejected')
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $stageName }}</div>
                                        @if($approval)
                                            <div class="text-sm text-gray-500">
                                                {{ $approval->approver->name }} • {{ $approval->approved_at->format('d M Y H:i') }}
                                            </div>
                                            @if($approval->comment)
                                                <div class="text-sm text-gray-600 mt-1">"{{ $approval->comment }}"</div>
                                            @endif
                                        @else
                                            <div class="text-sm text-gray-500">Menunggu persetujuan</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm">
                                    @if($approval)
                                        @if($approval->status === 'approved')
                                            <span class="text-green-600 font-medium">Disetujui</span>
                                        @elseif($approval->status === 'rejected')
                                            <span class="text-red-600 font-medium">Ditolak</span>
                                        @else
                                            <span class="text-yellow-600 font-medium">Diproses</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Pending</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <a href="{{ route('spts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Kembali
                            </a>

                            @if($spt->status === 'draft' && (auth()->user()->isAdmin() || $spt->user_id === auth()->id()))
                                <a href="{{ route('spts.edit', $spt) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('spts.submit', $spt) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Ajukan SPT
                                    </button>
                                </form>
                            @endif

                            @if($spt->sppd)
                                <a href="{{ route('sppds.show', $spt->sppd) }}" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Lihat SPPD
                                </a>
                            @endif
                        </div>

                        @if(auth()->user()->hasRole(['supervisor', 'finance', 'verifikator', 'admin']) && $spt->status === 'submitted')
                            <a href="{{ route('approvals.show', $spt) }}" class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md shadow-sm text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Beri Persetujuan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>