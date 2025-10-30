@props(['id', 'title' => 'Modal', 'size' => 'md'])

@php
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-2xl',
    'lg' => 'max-w-4xl',
    'xl' => 'max-w-6xl',
];
$modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div id="{{ $id }}" data-modal-root class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" data-close-modal data-target="{{ $id }}" onclick="window.closeCrudModal('{{ $id }}')"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $modalSize }} w-full">
            <!-- Modal header -->
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                    <button type="button" data-close-modal data-target="{{ $id }}" onclick="window.closeCrudModal('{{ $id }}')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-white px-6 py-4 modal-body" data-modal-content>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>