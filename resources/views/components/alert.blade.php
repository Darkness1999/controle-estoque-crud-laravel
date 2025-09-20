@props(['type' => 'sucesso', 'message'])

@php
    $colors = [
        'sucesso' => 'bg-green-100 dark:bg-green-800 border-green-400 text-green-700 dark:text-green-300',
        'erro' => 'bg-red-100 dark:bg-red-800 border-red-400 text-red-700 dark:text-red-300',
    ];
@endphp

<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 5000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-90"
     class="p-4 mb-4 border rounded {{ $colors[$type] }}"
     role="alert">

    <div class="flex items-center justify-between">
        <div>
            {{ $message }}
        </div>
        <button @click="show = false" class="ml-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>