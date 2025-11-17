<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            Crea tu cuenta
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Únete a la comunidad NexLocal
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ role: 'tourist' }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre Completo" />
            <x-input-with-icon
                id="name"
                name="name"
                type="text"
                class="mt-1"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Juan Pérez"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </x-slot>
            </x-input-with-icon>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-input-with-icon
                id="email"
                name="email"
                type="email"
                class="mt-1"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="tu@email.com"
            >
                <x-slot name="icon">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </x-slot>
            </x-input-with-icon>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div>
            <x-input-label value="¿Cómo quieres usar NexLocal?" class="mb-3" />
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Turista Card -->
                <label
                    class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200"
                    :class="role === 'tourist' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700'"
                >
                    <input
                        type="radio"
                        name="role"
                        value="tourist"
                        x-model="role"
                        class="sr-only"
                    >
                    <div class="text-4xl mb-2">🎒</div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">Turista</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                        Quiero vivir experiencias
                    </div>
                    <div
                        x-show="role === 'tourist'"
                        class="absolute top-2 right-2 text-indigo-600 dark:text-indigo-400"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </label>

                <!-- Guía Card -->
                <label
                    class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200"
                    :class="role === 'guide' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700'"
                >
                    <input
                        type="radio"
                        name="role"
                        value="guide"
                        x-model="role"
                        class="sr-only"
                    >
                    <div class="text-4xl mb-2">🌟</div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">Guía</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                        Quiero ofrecer experiencias
                    </div>
                    <div
                        x-show="role === 'guide'"
                        class="absolute top-2 right-2 text-indigo-600 dark:text-indigo-400"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-password-input
                id="password"
                name="password"
                class="mt-1"
                required
                autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
            <x-password-input
                id="password_confirmation"
                name="password_confirmation"
                class="mt-1"
                required
                autocomplete="new-password"
                placeholder="Repite tu contraseña"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" value="Foto de Perfil" />
            <x-file-upload
                name="profile_photo"
                accept="image/*"
                maxSize="2MB"
                class="mt-1"
                required
            />
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                Crear Cuenta
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition duration-150">
                Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>
