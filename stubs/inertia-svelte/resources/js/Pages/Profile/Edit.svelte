<script>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.svelte';
    import DeleteUserForm from './Partials/DeleteUserForm.svelte';
    import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
    import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';

    export let mustVerifyEmail;
    export let status;
</script>

<svelte:head>
    <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
    <h2 slot="header" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdateProfileInformationForm
                    {mustVerifyEmail}
                    {status}
                    class="max-w-xl"
                />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</AuthenticatedLayout>
