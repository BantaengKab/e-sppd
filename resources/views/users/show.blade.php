@if(request()->ajax() || request()->header('X-Requested-With') == 'XMLHttpRequest')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Pribadi</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Nama Lengkap:</span>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">NIP:</span>
                        <p class="font-medium">{{ $user->nip }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Email:</span>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Jabatan:</span>
                        <p class="font-medium">{{ $user->jabatan }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Sistem</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Role:</span>
                        <p>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'supervisor') bg-purple-100 text-purple-800
                                @elseif($user->role === 'finance') bg-green-100 text-green-800
                                @elseif($user->role === 'verifikator') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Unit Kerja:</span>
                        <p class="font-medium">{{ $user->unit_kerja }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <p>
                            @if($user->status === 'active' || !$user->status)
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Bergabung:</span>
                        <p class="font-medium">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Statistik Aktivitas</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $user->spts()->count() }}</div>
                    <div class="text-sm text-blue-600">SPT Dibuat</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $user->approvals()->count() }}</div>
                    <div class="text-sm text-green-600">Persetujuan</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $user->activityLogs()->count() }}</div>
                    <div class="text-sm text-purple-600">Aktivitas Log</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        @if($user->activityLogs->count() > 0)
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Aktivitas Terkini</h4>
            <div class="space-y-3">
                @foreach($user->activityLogs as $log)
                <div class="flex items-start space-x-3 text-sm">
                    <div class="flex-shrink-0">
                        @if($log->action === 'created')
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                        @elseif($log->action === 'updated')
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        @elseif($log->action === 'deleted')
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                        @else
                            <div class="w-2 h-2 bg-gray-500 rounded-full mt-1.5"></div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900">{{ ucfirst($log->action) }} {{ $log->table_name ?? 'data' }}</p>
                        <p class="text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeCrudModal('user-detail-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Tutup
            </button>
            <button id="editFromDetail" data-edit-url="{{ route('users.edit', $user) }}"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Edit Pengguna
            </button>
        </div>
    </div>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengguna
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Detail Pengguna</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Edit
                            </a>
                            <a href="{{ route('users.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- User Header -->
                        <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex-shrink-0 h-20 w-20">
                                <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-2xl">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $user->jabatan }}</p>
                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'supervisor') bg-purple-100 text-purple-800
                                        @elseif($user->role === 'finance') bg-green-100 text-green-800
                                        @elseif($user->role === 'verifikator') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    @if($user->status === 'active' || !$user->status)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                                <p class="text-base text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $user->email }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">NIP</h4>
                                <p class="text-base text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    {{ $user->nip }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Jabatan</h4>
                                <p class="text-base text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $user->jabatan }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Unit Kerja</h4>
                                <p class="text-base text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $user->unit_kerja }}
                                </p>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Bergabung</h4>
                                    <p class="text-base text-gray-900 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $user->created_at->format('d F Y') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</h4>
                                    <p class="text-base text-gray-900 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $user->updated_at->format('d F Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics (Optional - if you have related data) -->
                        @if(isset($statistics))
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Statistik</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-blue-600">Total SPPD</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ $statistics['total_sppd'] ?? 0 }}</p>
                                        </div>
                                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-green-600">Disetujui</p>
                                            <p class="text-2xl font-bold text-green-900">{{ $statistics['approved'] ?? 0 }}</p>
                                        </div>
                                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-yellow-600">Pending</p>
                                            <p class="text-2xl font-bold text-yellow-900">{{ $statistics['pending'] ?? 0 }}</p>
                                        </div>
                                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-6 mt-6 flex justify-end space-x-3">
                            <button type="button" id="closeDetailModal" data-close-modal data-target="user-detail-modal" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Tutup
                            </button>
                            <button type="button"
                                    id="editFromDetail"
                                    data-close-modal
                                    data-target="user-detail-modal"
                                    data-edit-url="{{ route('users.edit', $user) }}"
                                    onclick="window.closeCrudModal('user-detail-modal'); if(window.editRecord){ window.editRecord('user-edit-modal', this.dataset.editUrl, {title: 'Edit Pengguna', size: 'lg'}); }"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Pengguna
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif

<script>
(function() {
    console.log('Show view script executing...');
    
    // Close button
    const closeBtn = document.getElementById('closeDetailModal');
    console.log('Close button found:', closeBtn);
    
    if (closeBtn) {
        console.log('Attaching click event to close button');
        closeBtn.addEventListener('click', function() {
            console.log('Close button clicked!');
            console.log('window.closeCrudModal exists:', typeof window.closeCrudModal);
            if (window.closeCrudModal) {
                console.log('Calling closeCrudModal for user-detail-modal...');
                window.closeCrudModal('user-detail-modal');
            } else {
                console.error('window.closeCrudModal not found!');
            }
        });
    } else {
        console.error('Close button not found!');
    }
    
    // Edit button
    const editBtn = document.getElementById('editFromDetail');
    console.log('Edit button found:', editBtn);
    
    if (editBtn) {
        console.log('Attaching click event to edit button');
        editBtn.addEventListener('click', function() {
            console.log('Edit button clicked!');
            console.log('window.closeCrudModal exists:', typeof window.closeCrudModal);
            console.log('window.editRecord exists:', typeof window.editRecord);
            
            if (window.closeCrudModal) {
                console.log('Closing detail modal...');
                window.closeCrudModal('user-detail-modal');
            }
            if (window.editRecord) {
                console.log('Opening edit modal...');
                window.editRecord('user-edit-modal', '{{ route('users.edit', $user) }}', {title: 'Edit Pengguna', size: 'lg'});
            } else {
                console.error('window.editRecord not found!');
            }
        });
    } else {
        console.error('Edit button not found!');
    }
    
    console.log('Show view script completed');
})();
</script>