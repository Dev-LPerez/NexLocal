@props(['disabled' => false, 'icon' => null, 'iconPosition' => 'left', 'type' => 'text'])

<div class="relative">
    @if($icon && $iconPosition === 'left')
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-400 dark:text-gray-500">
                {!! $icon !!}
            </span>
        </div>
    @endif

    <input
        type="{{ $type }}"
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md shadow-sm transition duration-150 ease-in-out
                       border-gray-300 dark:border-gray-700
                       dark:bg-gray-900 dark:text-gray-300
                       focus:border-indigo-500 dark:focus:border-indigo-600
                       focus:ring-indigo-500 dark:focus:ring-indigo-600
                       disabled:opacity-50 disabled:cursor-not-allowed
                       ' . ($icon && $iconPosition === 'left' ? 'pl-10' : '') .
                       ' ' . ($icon && $iconPosition === 'right' ? 'pr-10' : '')
        ]) }}
    >

    @if($icon && $iconPosition === 'right')
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-400 dark:text-gray-500">
                {!! $icon !!}
            </span>
        </div>
    @endif
</div>

