<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Pengguna
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Profil</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Profil</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-blue-500 mb-4">
                                    <span class="text-3xl font-bold text-white">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h4>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">NIP:</span> {{ auth()->user()->nip }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Jabatan:</span> {{ auth()->user()->jabatan }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Unit Kerja:</span> {{ auth()->user()->unit_kerja }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Role:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ auth()->user()->role }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mt-6">
                                    <p class="text-xs text-gray-500">
                                        Bergabung sejak {{ auth()->user()->created_at->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile Information -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Update Informasi Profil</h3>
                            <p class="mt-1 text-sm text-gray-500">Perbarui informasi akun dan data pribadi Anda</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Update Password</h3>
                            <p class="mt-1 text-sm text-gray-500">Pastikan akun Anda menggunakan password yang aman</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Activity Statistics (Optional - for demo purposes) -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Statistik Aktivitas</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ auth()->user()->spts()->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">SPT Diajukan</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ auth()->user()->approvals()->where('status', 'approved')->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Disetujui</div>
                                </div>
                                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ auth()->user()->approvals()->where('status', 'pending')->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Menunggu Persetujuan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 border-red-200">
                            <h3 class="text-lg font-medium text-red-900">Hapus Akun</h3>
                            <p class="mt-1 text-sm text-red-500">Permanently delete your account and all associated data</p>
                        </div>
                        <div class="p-6 bg-red-50">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
