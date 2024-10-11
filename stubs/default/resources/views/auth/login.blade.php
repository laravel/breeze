<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
 
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Heading -->
        <div class="flex justify-center items-center mb-0.5 text-white flex-col flex-wrap">
        <h1 class="font-bold text-2xl">Login</h1>
            <span class="text-sm text-gray-600 dark:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"> {{ __('Don\'t have an account? ') }}<a class="underline font-semibold text-gray-500 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100" href="{{ route('register') }}">{{ __('Register!') }}</a></span>
 
        </div>
 
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
 
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
 
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
 
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
 
        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
            <label for="remember_me">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
 
        <div class="block mt-4 w-full">
            <x-primary-button class="w-full justify-center">
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>