<script>
    import InputError from '@/Components/InputError.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import { useForm } from '@inertiajs/svelte';
    import { route } from "@/ziggy";

    let clazz;
    export { clazz as class };

    let passwordInput = null;
    let currentPasswordInput = null;

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword = () => {
        $form.put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => $form.reset(),
            onError: () => {
                if ($form.errors.password) {
                    $form.reset('password', 'password_confirmation');
                    passwordInput.focus();
                }
                if ($form.errors.current_password) {
                    $form.reset('current_password');
                    currentPasswordInput.focus();
                }
            },
        });
    };
</script>

<section class={clazz}>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form on:submit|preventDefault={updatePassword} class="mt-6 space-y-6">
        <div>
            <InputLabel for="current_password" value="Current Password" />

            <TextInput
                id="current_password"
                ref="currentPasswordInput"
                bind:value={$form.current_password}
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <InputError message={$form.errors.current_password} class="mt-2" />
        </div>

        <div>
            <InputLabel for="password" value="New Password" />

            <TextInput
                id="password"
                ref="passwordInput"
                bind:value={$form.password}
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
                id="password_confirmation"
                bind:value={$form.password_confirmation}
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password_confirmation} class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton processing={$form.processing}>Save</PrimaryButton>

            {#if $form.recentlySuccessful}
            <p class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            {/if}
        </div>
    </form>
</section>
