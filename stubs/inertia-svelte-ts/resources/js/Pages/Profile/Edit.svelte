<script>
    import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.svelte";

    import DeleteUserForm from "./Partials/DeleteUserForm.svelte";
    import UpdatePasswordForm from "./Partials/UpdatePasswordForm.svelte";
    import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.svelte";

    export let mustVerifyEmail = false,
        status = null;
</script>

<svelte:head>
    <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
    <svelte:fragment slot="header">
        <h2
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
        >
            Profile
        </h2>
    </svelte:fragment>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
                <UpdateProfileInformationForm
                    {mustVerifyEmail}
                    {status}
                    classes="max-w-xl"
                />
            </div>

            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
                <UpdatePasswordForm classes="max-w-xl" />
            </div>

            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
            >
                <DeleteUserForm classes="max-w-xl" />
            </div>
        </div>
    </div>
</AuthenticatedLayout>
