@props(['disabled' => false, 'options' => [], 'placeholder' => 'Selecciona una opción'])

<div class="relative" x-data="{ open: false, selected: '{{ old($attributes->get('name'), '') }}' }">
    <select
        @disabled($disabled)
        x-model="selected"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md shadow-sm transition duration-150 ease-in-out
                       border-gray-300 dark:border-gray-700
                       dark:bg-gray-900 dark:text-gray-300
                       focus:border-indigo-500 dark:focus:border-indigo-600
                       focus:ring-indigo-500 dark:focus:ring-indigo-600
                       disabled:opacity-50 disabled:cursor-not-allowed
                       appearance-none pr-10'
        ]) }}
    >
        @if($placeholder)
            <option value="" disabled {{ old($attributes->get('name')) ? '' : 'selected' }}>{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    <!-- Custom Arrow Icon -->
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </div>
</div>

