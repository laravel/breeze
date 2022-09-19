<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <div class="space-y-6">
                <div class="p-6 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="p-6 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="p-6 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
