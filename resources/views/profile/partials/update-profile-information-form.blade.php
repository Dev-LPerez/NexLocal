<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" value="Foto de Perfil" />
            <div class="mt-2 flex items-center space-x-6">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto de perfil actual" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600 shadow-md">
                @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <x-file-upload name="profile_photo" accept="image/*" maxSize="2MB" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG o GIF (máx. 2MB)</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre Completo" />
            <x-input-with-icon id="name" name="name" type="text" class="mt-1" :value="old('name', $user->name)" required autofocus autocomplete="name">
                <x-slot name="icon">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </x-slot>
            </x-input-with-icon>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-input-with-icon id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email)" required autocomplete="username">
                <x-slot name="icon">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </x-slot>
            </x-input-with-icon>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" value="Biografía" />
            <x-textarea-input id="bio" name="bio" class="mt-1" rows="3" showCounter maxlength="500" placeholder="Cuéntanos un poco sobre ti...">{{ old('bio', $user->bio) }}</x-textarea-input>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Comparte algo interesante sobre ti</p>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- Additional Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="age" value="Edad" />
                <x-text-input id="age" name="age" type="number" min="0" max="120" class="mt-1 block w-full" :value="old('age', $user->age)" placeholder="25" />
                <x-input-error class="mt-2" :messages="$errors->get('age')" />
            </div>

            <div>
                <x-input-label for="occupation" value="Ocupación" />
                <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $user->occupation)" placeholder="Ej: Desarrollador" />
                <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
            </div>
        </div>

        <!-- Hobbies -->
        <div>
            <x-input-label for="hobbies" value="Intereses y Hobbies" />
            <x-textarea-input id="hobbies" name="hobbies" class="mt-1" rows="2" placeholder="Fotografía, senderismo, gastronomía...">{{ old('hobbies', $user->hobbies) }}</x-textarea-input>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ayuda a conectar con personas de intereses similares</p>
            <x-input-error class="mt-2" :messages="$errors->get('hobbies')" />
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4 border-t dark:border-gray-700">
            <x-primary-button>
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400 flex items-center"
                >
                    <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
