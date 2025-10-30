@props(['user', 'roles', 'isEdit' => false])

<form class="crud-form" action="{{ $isEdit ? route('users.update', $user->id) : route('users.store') }}" method="POST" @if($isEdit) data-method="PUT" @endif>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
            <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP *</label>
                <input type="text" id="nip" name="nip" value="{{ $user->nip ?? '' }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
            <select id="role" name="role" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ ($user->role ?? '') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan *</label>
            <input type="text" id="jabatan" name="jabatan" value="{{ $user->jabatan ?? '' }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div>
            <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja *</label>
            <input type="text" id="unit_kerja" name="unit_kerja" value="{{ $user->unit_kerja ?? '' }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '*' }}
                </label>
                <input type="password" id="password" name="password" {{ !$isEdit ? 'required' : '' }}
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '*' }}
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" {{ !$isEdit ? 'required' : '' }}
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
    </div>
    <div class="mt-6 flex justify-end space-x-3">
        <button type="button" onclick="closeCrudModal('{{ $isEdit ? 'user-edit-modal' : 'user-create-modal' }}')"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal
        </button>
        <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</form>