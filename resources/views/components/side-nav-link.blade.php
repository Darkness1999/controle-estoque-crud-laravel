@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center px-3 py-2 text-sm font-medium rounded-md 
           text-white bg-indigo-600 shadow-sm
           transition-colors duration-200 ease-in-out'
        : 'flex items-center px-3 py-2 text-sm font-medium rounded-md 
           text-gray-700 dark:text-gray-300 
           hover:bg-gray-100 dark:hover:bg-gray-700 
           hover:text-gray-900 dark:hover:text-gray-100
           transition-colors duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
