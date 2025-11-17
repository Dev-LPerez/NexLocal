@props(['steps' => []])

<div class="mb-8">
    <!-- Progress Bar -->
    <div class="relative">
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
            <div
                x-bind:style="`width: ${((currentStep - 1) / {{ count($steps) - 1 }}) * 100}%`"
                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-500"
            ></div>
        </div>

        <!-- Steps -->
        <div class="flex justify-between">
            @foreach($steps as $index => $step)
                <div class="flex flex-col items-center flex-1">
                    <!-- Circle -->
                    <div
                        x-bind:class="{
                            'bg-gradient-to-r from-indigo-600 to-purple-600 border-indigo-600': currentStep > {{ $index + 1 }},
                            'border-indigo-600 bg-white dark:bg-gray-800': currentStep === {{ $index + 1 }},
                            'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800': currentStep < {{ $index + 1 }}
                        }"
                        class="w-10 h-10 flex items-center justify-center rounded-full border-2 transition-all duration-300"
                    >
                        <!-- Completed Icon -->
                        <svg
                            x-show="currentStep > {{ $index + 1 }}"
                            class="w-6 h-6 text-white"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            style="display: none;"
                        >
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>

                        <!-- Number -->
                        <span
                            x-show="currentStep <= {{ $index + 1 }}"
                            x-bind:class="{
                                'text-indigo-600 dark:text-indigo-400': currentStep === {{ $index + 1 }},
                                'text-gray-400 dark:text-gray-500': currentStep !== {{ $index + 1 }}
                            }"
                            class="text-sm font-semibold"
                        >
                            {{ $index + 1 }}
                        </span>
                    </div>

                    <!-- Label -->
                    <div
                        x-bind:class="{
                            'text-indigo-600 dark:text-indigo-400': currentStep === {{ $index + 1 }},
                            'text-gray-500 dark:text-gray-400': currentStep !== {{ $index + 1 }}
                        }"
                        class="text-xs font-medium mt-2 text-center transition-colors duration-300"
                    >
                        {{ $step }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

