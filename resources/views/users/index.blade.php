<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Daftar Pengguna</h1>
                    <p class="mt-2 text-gray-600">Kelola semua pengguna sistem E-SPPD</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="createRecord('user-create-modal', '{{ route('users.create') }}', {title: 'Tambah Pengguna Baru', size: 'lg'})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Pengguna
                    </button>
                    <a href="{{ route('users.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Nama, email, atau NIP">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select id="role" name="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Semua Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="unit_kerja" class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                            <input type="text" id="unit_kerja" name="unit_kerja" value="{{ request('unit_kerja') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Unit kerja">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>
                            <a href="{{ route('users.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            @if($users->count() > 0)
            <form method="POST" action="{{ route('users.bulk') }}" id="bulkForm">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700">Pilih Semua</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select name="action" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                                <option value="">Pilih Aksi</option>
                                <option value="activate">Aktifkan</option>
                                <option value="deactivate">Nonaktifkan</option>
                                <option value="delete">Hapus</option>
                            </select>
                            <button type="button" onclick="handleBulkAction()" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Jalankan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @endif

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Pengguna ({{ $users->total() }})</h3>
                </div>

                @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="bulk-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Kerja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="bulk-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->jabatan }}</div>
                                            <div class="text-xs text-gray-400">NIP: {{ $user->nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'supervisor') bg-purple-100 text-purple-800
                                        @elseif($user->role === 'finance') bg-green-100 text-green-800
                                        @elseif($user->role === 'verifikator') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->unit_kerja }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status === 'active' || !$user->status)
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="viewRecord('user-detail-modal', '{{ route('users.show', $user) }}', {title: 'Detail Pengguna'})" class="text-blue-600 hover:text-blue-900" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="editRecord('user-edit-modal', '{{ route('users.edit', $user) }}', {title: 'Edit Pengguna', size: 'lg'})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline" title="Toggle Status">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                    @if($user->status === 'active' || !$user->status)
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <button onclick="deleteRecord('{{ route('users.destroy', $user) }}', {confirmMessage: 'Apakah Anda yakin ingin menghapus pengguna ini? Semua data terkait akan hilang.', successMessage: 'Pengguna berhasil dihapus!'})" class="text-red-600 hover:text-red-900" title="Hapus">
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
                        {{ $users->links() }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> hingga
                                <span class="font-medium">{{ $users->lastItem() }}</span> dari
                                <span class="font-medium">{{ $users->total() }}</span> hasil
                            </p>
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengguna</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pengguna baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-crud-modal id="user-create-modal" title="Tambah Pengguna Baru" size="lg">
        <form class="crud-form" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" id="email" name="email" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP *</label>
                        <input type="text" id="nip" name="nip" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan *</label>
                    <input type="text" id="jabatan" name="jabatan" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja *</label>
                    <input type="text" id="unit_kerja" name="unit_kerja" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                        <input type="password" id="password" name="password" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" data-close-modal data-target="user-create-modal" onclick="window.closeCrudModal('user-create-modal')"
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

    <x-crud-modal id="user-edit-modal" title="Edit Pengguna" size="lg">
        <!-- Content will be loaded via AJAX -->
    </x-crud-modal>

    <x-crud-modal id="user-detail-modal" title="Detail Pengguna" size="lg">
        <!-- Content will be loaded via AJAX -->
    </x-crud-modal>

    <script>
    // Define all CRUD functions globally before DOM loads
    window.viewRecord = function(modalId, url, options = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error('Modal not found:', modalId);
            return;
        }
        
        // Show the modal (Tailwind)
        modal.classList.remove('hidden');
        
        // Load content via AJAX
        const modalContent = modal.querySelector('[data-modal-content]') || modal.querySelector('.modal-body');
        if (modalContent) {
            modalContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-600">Loading...</p></div>';
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            if (modalContent) {
                modalContent.innerHTML = html;
                
                // Set up event delegation for dynamically loaded buttons
                setTimeout(() => {
                    const closeBtn = modal.querySelector('#closeDetailModal');
                    if (closeBtn) {
                        console.log('Found close button, attaching event');
                        closeBtn.onclick = null;
                        closeBtn.onclick = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Close clicked via delegation');
                            window.closeCrudModal(modalId);
                        };
                    }
                    
                    const editBtn = modal.querySelector('#editFromDetail');
                    if (editBtn) {
                        console.log('Found edit from detail button, attaching event');
                        editBtn.onclick = null;
                        editBtn.onclick = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Edit from detail clicked via delegation');
                            window.closeCrudModal(modalId);
                            const editUrl = this.getAttribute('data-edit-url') || editBtn.dataset.editUrl;
                            if (editUrl && window.editRecord) {
                                window.editRecord('user-edit-modal', editUrl, {title: 'Edit Pengguna', size: 'lg'});
                            }
                        };
                    }
                }, 100);
            }
        })
        .catch(error => {
            console.error('Error loading content:', error);
            if (modalContent) {
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">Error loading content</div>';
            }
        });
    };

    window.editRecord = function(modalId, url, options = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error('Modal not found:', modalId);
            return;
        }

        // Re-entrancy guard to avoid double-loading
        if (modal.dataset.loading === 'true') {
            console.warn('Edit modal is already loading, ignoring duplicate call');
            return;
        }
        modal.dataset.loading = 'true';
        
        // Show the modal (Tailwind)
        modal.classList.remove('hidden');
        
        // Load content via AJAX
        const modalContent = modal.querySelector('[data-modal-content]') || modal.querySelector('.modal-body');
        if (modalContent) {
            modalContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-600">Loading...</p></div>';
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            if (modalContent) {
                modalContent.innerHTML = html;
                
                // Set up event delegation for dynamically loaded buttons
                setTimeout(() => {
                    const cancelBtn = modal.querySelector('#cancelEditBtn');
                    if (cancelBtn) {
                        console.log('Found cancel button, attaching event');
                        cancelBtn.onclick = null;
                        cancelBtn.onclick = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Cancel clicked via delegation');
                            window.closeCrudModal(modalId);
                        };
                    }
                    
                    const form = modal.querySelector('.crud-form');
                    if (form) {
                        console.log('Found form, attaching submit event');
                        form.onsubmit = null;
                        form.onsubmit = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Form submitted via delegation');
                            
                            const formData = new FormData(this);
                            const submitButton = this.querySelector('button[type="submit"]');
                            const originalText = submitButton.innerHTML;
                            submitButton.disabled = true;
                            submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';
                            
                            fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.reload();
                                } else {
                                    alert(data.message || 'Terjadi kesalahan');
                                    submitButton.disabled = false;
                                    submitButton.innerHTML = originalText;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat menyimpan data');
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            });
                        };
                    }
                }, 100);
            }
        })
        .catch(error => {
            console.error('Error loading content:', error);
            if (modalContent) {
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">Error loading content</div>';
            }
        })
        .finally(() => {
            modal.dataset.loading = 'false';
        });
    };

    window.createRecord = function(modalId, url, options = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error('Modal not found:', modalId);
            return;
        }
        
        // Show the modal (Tailwind)
        modal.classList.remove('hidden');
    };

    // Missing deleteRecord used by delete buttons
    window.deleteRecord = function(url, options = {}) {
        const confirmMessage = options?.confirmMessage || 'Apakah Anda yakin ingin menghapus data ini?';
        if (!confirm(confirmMessage)) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    };

    window.closeCrudModal = function(modalId) {
        console.log('Closing modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            console.log('Modal found, closing...');
            modal.classList.add('hidden');
        } else {
            console.error('Modal not found:', modalId);
        }
    };
    
    // Global delegation: close modals on any element with data-close-modal
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('[data-close-modal]');
        if (!trigger) return;

        e.preventDefault();
        e.stopPropagation();

        let id = trigger.getAttribute('data-target');
        if (!id) {
            // Fallback: find nearest modal root
            const modalRoot = trigger.closest('[data-modal-root]');
            if (modalRoot?.id) id = modalRoot.id;
        }

        if (id) {
            console.log('Delegated close for modal:', id);
            window.closeCrudModal(id);
        } else {
            console.warn('Could not resolve modal id to close');
        }
    });

    // DOM Ready handlers
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                bulkCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        // Update select all checkbox when individual checkboxes change
        bulkCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(bulkCheckboxes).every(cb => cb.checked);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = ['user-create-modal', 'user-edit-modal', 'user-detail-modal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        window.closeCrudModal(modalId);
                    }
                });
            }
        });
    });
    </script>
</x-app-layout>