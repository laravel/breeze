<x-guest-layout>
    <x-auth-card>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" />

        <x-splade-form :default="['email' => $request->email, 'token' => $request->route('token')]" action="{{ route('password.update') }}" class="space-y-4">
            <x-splade-input id="email" type="email" name="email" :label="__('Email')" required autofocus />
            <x-splade-input id="password" type="password" name="password" :label="__('Password')" required />
            <x-splade-input id="password_confirmation" type="password" name="password_confirmation" :label="__('Confirm Password')" required />

            <div class="flex items-center justify-end">
                <x-splade-submit :label="__('Reset Password')" />
            </div>
        </x-splade-form>
    </x-auth-card>
</x-guest-layout>
