@if(request()->ajax() || request()->header('X-Requested-With') == 'XMLHttpRequest')
    <form class="crud-form" action="{{ route('estimated-costs.update', $estimatedCost->id) }}" method="POST" data-method="PUT">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="spt_id" class="block text-sm font-medium text-gray-700">SPT *</label>
                <select id="spt_id" name="spt_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        @if($estimatedCost->spt->status !== 'draft' && !auth()->user()->isAdmin()) disabled @endif>
                    @foreach($spts as $spt)
                        <option value="{{ $spt->id }}" {{ $estimatedCost->spt_id == $spt->id ? 'selected' : '' }}>
                            SPT #{{ $spt->id }} - {{ Str::limit($spt->title, 50) }} ({{ $spt->user->name }})
                        </option>
                    @endforeach
                </select>
                @if($estimatedCost->spt->status !== 'draft' && !auth()->user()->isAdmin())
                    <input type="hidden" name="spt_id" value="{{ $estimatedCost->spt_id }}">
                @endif
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Jenis Biaya *</label>
                <select id="type" name="type" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ $estimatedCost->type == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp) *</label>
                <input type="number" id="amount" name="amount" value="{{ $estimatedCost->amount }}" required min="0" step="0.01"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="0">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Deskripsi biaya (opsional)">{{ $estimatedCost->description ?? '' }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="closeCrudModal('estimated-cost-edit-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Perbarui
            </button>
        </div>
    </form>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Estimasi Biaya
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Edit Estimasi Biaya</h3>
                    </div>
                    <div class="p-6">
                        <!-- Include the same form as above -->
                        <form action="{{ route('estimated-costs.update', $estimatedCost->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="spt_id" class="block text-sm font-medium text-gray-700">SPT *</label>
                                    <select id="spt_id" name="spt_id" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            @if($estimatedCost->spt->status !== 'draft' && !auth()->user()->isAdmin()) disabled @endif>
                                        @foreach($spts as $spt)
                                            <option value="{{ $spt->id }}" {{ $estimatedCost->spt_id == $spt->id ? 'selected' : '' }}>
                                                SPT #{{ $spt->id }} - {{ Str::limit($spt->title, 50) }} ({{ $spt->user->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($estimatedCost->spt->status !== 'draft' && !auth()->user()->isAdmin())
                                        <input type="hidden" name="spt_id" value="{{ $estimatedCost->spt_id }}">
                                    @endif
                                </div>
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis Biaya *</label>
                                    <select id="type" name="type" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ $estimatedCost->type == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp) *</label>
                                    <input type="number" id="amount" name="amount" value="{{ $estimatedCost->amount }}" required min="0" step="0.01"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea id="description" name="description" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="Deskripsi biaya (opsional)">{{ $estimatedCost->description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <a href="{{ route('estimated-costs.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </a>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    Perbarui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif