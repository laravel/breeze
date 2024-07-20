<script>
    import { useForm } from "@inertiajs/svelte";
    import {route} from '@/route';


    import TextInput from "../../../Components/TextInput.svelte";
    import InputLabel from "../../../Components/InputLabel.svelte";
    import InputError from "../../../Components/InputError.svelte";
    import PrimaryButton from "../../../Components/PrimaryButton.svelte";

    export let classes;

    const form = useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
    });

    const updatePassword = () => {
        $form.put(route("password.update"), {
            preserveScroll: true,
            onSuccess: () => $form.reset(),
            onError: () => {
                if ($form.errors.password) {
                    $form.reset("password", "password_confirmation");
                    // passwordInput.value.focus();
                }
                if ($form.errors.current_password) {
                    $form.reset("current_password");
                    // currentPasswordInput.value.focus();
                }
            },
        });
    };
</script>

<section class={classes}>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form on:submit|preventDefault={updatePassword} class="mt-6 space-y-6">
        <div>
            <InputLabel for="current_password" value="Current Password" />

            <TextInput
                id="current_password"
                bind:value={$form.current_password}
                type="password"
                classes="mt-1 block w-full"
                autocomplete="current-password"
            />

            <InputError message={$form.errors.current_password} />
        </div>

        <div>
            <InputLabel for="password" value="New Password" />

            <TextInput
                id="password"
                bind:value={$form.password}
                type="password"
                classes="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password} />
        </div>

        <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
                id="password_confirmation"
                bind:value={$form.password_confirmation}
                type="password"
                classes="mt-1 block w-full"
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password_confirmation} />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton disabled={$form.processing}>Save</PrimaryButton>

            {#if $form.recentlySuccessful}
                <p class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            {/if}
        </div>
    </form>
</section>
