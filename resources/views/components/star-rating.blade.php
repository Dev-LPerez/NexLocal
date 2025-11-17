@props(['name' => 'rating', 'value' => 0, 'max' => 5, 'size' => 'default'])

@php
    $sizeClasses = [
        'small' => 'h-6 w-6',
        'default' => 'h-10 w-10',
        'large' => 'h-12 w-12'
    ];
    $starSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div x-data="{
    rating: {{ $value }},
    hoverRating: 0,
    labels: {
        1: '😞 Decepcionante',
        2: '😐 Regular',
        3: '🙂 Bueno',
        4: '😃 Muy bueno',
        5: '🤩 ¡Excelente!'
    }
}" class="space-y-2">
    <!-- Star Rating -->
    <div class="flex items-center space-x-1">
        <template x-for="star in {{ $max }}" :key="star">
            <button
                type="button"
                @click="rating = star"
                @mouseenter="hoverRating = star"
                @mouseleave="hoverRating = 0"
                class="focus:outline-none transition-transform duration-150 hover:scale-110"
            >
                <svg
                    class="{{ $starSize }} transition-colors duration-150"
                    :class="(hoverRating >= star || rating >= star) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.445a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.539 1.118l-3.367-2.445a1 1 0 00-1.175 0l-3.367 2.445c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"></path>
                </svg>
            </button>
        </template>
    </div>

    <!-- Rating Label -->
    <div x-show="rating > 0" class="text-sm font-medium text-gray-700 dark:text-gray-300" style="display: none;">
        <span x-text="labels[rating] || ''"></span>
    </div>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" x-model="rating">
</div>

