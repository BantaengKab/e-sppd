<form class="crud-form" action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 gap-4">
        <div>
            <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
            <input type="text" id="edit_name" name="name" value="{{ old('name', $user->name) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="edit_email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input type="email" id="edit_email" name="email" value="{{ old('email', $user->email) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="edit_nip" class="block text-sm font-medium text-gray-700">NIP *</label>
                <input type="text" id="edit_nip" name="nip" value="{{ old('nip', $user->nip) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                @error('nip')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div>
            <label for="edit_role" class="block text-sm font-medium text-gray-700">Role *</label>
            <select id="edit_role" name="role" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">Pilih Role</option>
                @foreach(['admin', 'supervisor', 'finance', 'verifikator', 'user'] as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="edit_jabatan" class="block text-sm font-medium text-gray-700">Jabatan *</label>
            <input type="text" id="edit_jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('jabatan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="edit_unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja *</label>
            <input type="text" id="edit_unit_kerja" name="unit_kerja" value="{{ old('unit_kerja', $user->unit_kerja) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('unit_kerja')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="edit_status" class="block text-sm font-medium text-gray-700">Status *</label>
            <select id="edit_status" name="status" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="border-t border-gray-200 pt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Ubah Password (Opsional)</h4>
            <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah password</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" id="edit_password" name="password"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" id="edit_password_confirmation" name="password_confirmation"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end space-x-3">
        <button type="button" id="cancelEditBtn" data-close-modal data-target="user-edit-modal" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Batal
        </button>
        <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Update Pengguna
        </button>
    </div>
</form>

<script>
// Note: Scripts inside AJAX-loaded HTML may not run when inserted via innerHTML.
// Closing is handled globally by a delegated click listener on [data-close-modal].
</script>