@props(['disabled' => false, 'maxlength' => null, 'showCounter' => false])

<div class="relative" x-data="{ count: {{ strlen(old($attributes->get('name'), '')) }} }">
    <textarea
        @disabled($disabled)
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($showCounter) x-on:input="count = $el.value.length" @endif
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md shadow-sm transition duration-150 ease-in-out
                       border-gray-300 dark:border-gray-700
                       dark:bg-gray-900 dark:text-gray-300
                       focus:border-indigo-500 dark:focus:border-indigo-600
                       focus:ring-indigo-500 dark:focus:ring-indigo-600
                       disabled:opacity-50 disabled:cursor-not-allowed
                       resize-y'
        ]) }}
    >{{ $slot }}</textarea>

    @if($showCounter && $maxlength)
        <div class="absolute bottom-2 right-2 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-2 py-1 rounded">
            <span x-text="count"></span> / {{ $maxlength }}
        </div>
    @endif
</div>

