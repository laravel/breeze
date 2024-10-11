<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Heading -->
        <div class="flex justify-center items-center mb-0.5 text-white flex-col flex-wrap">
            <h1 class="font-bold text-2xl">Register</h1>
            <span class="text-sm text-gray-600 dark:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"> {{ __('Already have an account? ') }}<a class="underline font-semibold text-gray-500 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100" href="{{ route('login') }}">{{ __('Login!') }}</a></span>
        </div>
 
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
 
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
 
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
 
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
 
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
 
        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
 
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
 
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
 
        <div class="block mt-4 w-full">
            <x-primary-button class="w-full justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>