<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

     <x-splade-form
        method="delete"
        :action="route('profile.destroy')"
        :confirm="__('Are you sure you want to delete your account?')"
        :confirm-text="__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')"
        :confirm-button="__('Delete Account')"
        require-password
    >
        <x-splade-submit danger :label="__('Delete Account')" />
    </x-splade-form>
</section>
