<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" 
                            name="name" 
                            type="text" 
                            class="block mt-1 w-full" 
                            wire:model="form.name" 
                            autofocus autocomplete="name" />

            <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" 
                            name="email" 
                            type="email" 
                            class="block mt-1 w-full"
                            wire:model="form.email" 
                            autocomplete="username" />

            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                            name="password"
                            type="password"
                            class="block mt-1 w-full"
                            wire:model="form.password"
                            autocomplete="new-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" 
                            name="password_confirmation"
                            type="password"
                            class="block mt-1 w-full"
                            wire:model="form.password_confirmation"
                            autocomplete="new-password" />

            <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
