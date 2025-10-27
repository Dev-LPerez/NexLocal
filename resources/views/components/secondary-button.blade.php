@props(['as' => 'button', 'href' => '#'])

@php
    $commonClasses = 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150';
@endphp

{{-- Renderiza como enlace si 'as' es 'a' --}}
@if ($as === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $commonClasses]) }}>
        {{ $slot }}
    </a>
{{-- Por defecto, renderiza como botón --}}
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $commonClasses]) }}>
        {{ $slot }}
    </button>
@endif

