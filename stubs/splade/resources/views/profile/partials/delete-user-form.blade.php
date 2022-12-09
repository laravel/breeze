<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <Link href="#confirm-user-deletion" dusk="open-delete-modal" class="inline-flex rounded-md shadow-sm bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline">
        {{ __('Delete Account') }}
    </Link>

    <x-splade-modal name="confirm-user-deletion">
        <x-splade-form dusk="confirm-user-deletion" method="delete" :action="route('profile.destroy')">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure your want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-splade-input id="password" name="password" type="password"  :placeholder="__('Password')" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click.prevent="modal.close" class="inline-flex rounded-md shadow-sm border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 focus:outline-none focus:shadow-outline">
                    {{ __('Cancel') }}
                </button>

                <button dusk="confirm-delete-account" type="submit" class="ml-3 inline-flex rounded-md shadow-sm bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </x-splade-form>
    </x-modal>
</section>
