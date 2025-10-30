@if(request()->ajax() || request()->header('X-Requested-With') == 'XMLHttpRequest')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Aktivitas</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Aksi:</span>
                        <p>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activityLog->action === 'created') bg-green-100 text-green-800
                                @elseif($activityLog->action === 'updated') bg-blue-100 text-blue-800
                                @elseif($activityLog->action === 'deleted') bg-red-100 text-red-800
                                @elseif($activityLog->action === 'submitted') bg-yellow-100 text-yellow-800
                                @elseif($activityLog->action === 'approved') bg-purple-100 text-purple-800
                                @elseif($activityLog->action === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($activityLog->action) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Tabel:</span>
                        <p class="font-medium">{{ $activityLog->table_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Record ID:</span>
                        <p class="font-medium">{{ $activityLog->record_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Waktu:</span>
                        <p class="font-medium">{{ $activityLog->created_at->format('d M Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Pengguna</h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Pengguna:</span>
                        <p class="font-medium">{{ $activityLog->user ? $activityLog->user->name : 'System' }}</p>
                    </div>
                    @if($activityLog->user)
                    <div>
                        <span class="text-sm text-gray-500">Email:</span>
                        <p class="font-medium">{{ $activityLog->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Role:</span>
                        <p class="font-medium">{{ ucfirst($activityLog->user->role) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Detail Teknis</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-sm text-gray-500">IP Address:</span>
                    <p class="font-medium font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">User Agent:</span>
                    <p class="font-medium text-xs break-all">{{ $activityLog->user_agent ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Old Values (if available) -->
        @if($activityLog->old_values)
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Nilai Lama</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        <!-- New Values (if available) -->
        @if($activityLog->new_values)
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Nilai Baru</h4>
            <div class="bg-blue-50 p-4 rounded-lg">
                <pre class="text-sm text-blue-700 whitespace-pre-wrap">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        <!-- Additional Data (if available) -->
        @if($activityLog->additional_data)
        <div class="border-t pt-6">
            <h4 class="text-sm font-medium text-gray-500 mb-4">Data Tambahan</h4>
            <div class="bg-green-50 p-4 rounded-lg">
                <pre class="text-sm text-green-700 whitespace-pre-wrap">{{ json_encode($activityLog->additional_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeCrudModal('activity-log-detail-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Tutup
            </button>
        </div>
    </div>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Log Aktivitas
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Detail Log Aktivitas</h3>
                        <div class="flex space-x-2">
                            <a href="{{ request()->header('referer') ?: route('activity-logs.index') }}"
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
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Aktivitas</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm text-gray-500">Aksi:</span>
                                            <p>
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($activityLog->action === 'created') bg-green-100 text-green-800
                                                    @elseif($activityLog->action === 'updated') bg-blue-100 text-blue-800
                                                    @elseif($activityLog->action === 'deleted') bg-red-100 text-red-800
                                                    @elseif($activityLog->action === 'submitted') bg-yellow-100 text-yellow-800
                                                    @elseif($activityLog->action === 'approved') bg-purple-100 text-purple-800
                                                    @elseif($activityLog->action === 'rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($activityLog->action) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Tabel:</span>
                                            <p class="font-medium">{{ $activityLog->table_name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Record ID:</span>
                                            <p class="font-medium">{{ $activityLog->record_id ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Waktu:</span>
                                            <p class="font-medium">{{ $activityLog->created_at->format('d M Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Informasi Pengguna</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm text-gray-500">Pengguna:</span>
                                            <p class="font-medium">{{ $activityLog->user ? $activityLog->user->name : 'System' }}</p>
                                        </div>
                                        @if($activityLog->user)
                                        <div>
                                            <span class="text-sm text-gray-500">Email:</span>
                                            <p class="font-medium">{{ $activityLog->user->email }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Role:</span>
                                            <p class="font-medium">{{ ucfirst($activityLog->user->role) }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Details -->
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Detail Teknis</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <span class="text-sm text-gray-500">IP Address:</span>
                                        <p class="font-medium font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500">User Agent:</span>
                                        <p class="font-medium text-xs break-all">{{ $activityLog->user_agent ?: 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Old Values (if available) -->
                            @if($activityLog->old_values)
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Nilai Lama</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            @endif

                            <!-- New Values (if available) -->
                            @if($activityLog->new_values)
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Nilai Baru</h4>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <pre class="text-sm text-blue-700 whitespace-pre-wrap">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            @endif

                            <!-- Additional Data (if available) -->
                            @if($activityLog->additional_data)
                            <div class="border-t pt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-4">Data Tambahan</h4>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <pre class="text-sm text-green-700 whitespace-pre-wrap">{{ json_encode($activityLog->additional_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif